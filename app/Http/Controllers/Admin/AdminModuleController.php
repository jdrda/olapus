<?php

namespace App\Http\Controllers\Admin;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Support\AdminFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use LogicException;

/** Shared CRUD controller. Existing module hooks and naming conventions are retained. */
class AdminModuleController extends Controller
{
    protected $moduleName;
    protected $moduleBasicRoute;
    protected $moduleBasicTemplatePath;
    protected $modelClass;
    protected $paginateRows = null;
    protected $viewVariables = [];
    protected $hiddenFieldsOnAction = [];
    protected $binaryFields = [];
    protected $dateTimeLocalFields = [];
    protected $customView = null;

    /** Request field => Eloquent relationship. No table-name guessing. */
    protected $relationFields = [];

    public function __construct()
    {
        $this->paginateRows ??= config('olapus.admin.paginate', 10);
        $this->moduleName = str_replace('Controller', '', class_basename(static::class));
        $this->modelClass ??= '\\App\\Models\\'.$this->moduleName;
        $this->moduleBasicRoute = 'admin.'.lcfirst($this->moduleName);
        $this->moduleBasicTemplatePath = 'admin.modules.'.strtolower($this->moduleName);
        View::share([
            'moduleBasicRoute' => $this->moduleBasicRoute,
            'moduleBasicTemplatePath' => $this->moduleBasicTemplatePath,
            'moduleName' => $this->moduleName,
            'moduleNameBlade' => 'admin_module_'.strtolower($this->moduleName),
        ]);
    }

    protected function afterStore($request, $model) {}
    protected function afterUpdate($request, $model) {}
    public function associateRelationships($object, Request $request) {}
    public function associateRelationshipsWithID($object, Request $request) {}
    public function resetCache($object) {}
    public function changeEditResultField($result) { return $result; }
    public function saveMediaToStorage($object, $request, $update = false) { return false; }

    public function getRowsToPaginate()
    {
        return max(1, (int) ($this->paginateRows ?? config('olapus.admin.paginate', 10)));
    }

    public function index(Request $request)
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass();
        $redirect = Helpers::resetSaveIndexParameters(
            $this->moduleBasicRoute,
            ['search', 'search_mode', 'orderbycolumn', 'orderbytype', 'relation', 'filters'],
            $model->getAdminFilters(),
            $model->getAdminColumnFilters(),
            $model->getAdminSearchMode()
        );
        if ($redirect !== false) {
            return redirect($redirect);
        }

        $results = $modelClass::where(fn (Builder $query) => $query->fulltextAllColumns())
            ->relationships()->orderByColumns()->excludeFromIndex()
            ->externalTablesFilter()->columnFilters()->paginate($this->getRowsToPaginate());
        $filters = new AdminFilters($model, $this->moduleBasicRoute, $request->query());

        return view($this->viewFor('index'), [
            'results' => $results,
            'media' => null,
            'indexFilters' => $filters,
            'filterControls' => $filters->controls(),
            'filterBadges' => $filters->badges(),
            'columnFilterControls' => $filters->columnControls(),
            'filterPresets' => $filters->presets(),
        ]);
    }

    public function create()
    {
        return view($this->viewFor('create'));
    }

    public function store(Request $request)
    {
        $modelClass = $this->modelClass;
        $object = new $modelClass();
        $rules = $this->validationRules($object);
        $this->validate($request, $rules);

        $object->getConnection()->transaction(function () use ($object, $request, $rules) {
            $this->assignFields($object, $request, $rules);
            $this->associateRelationships($object, $request);
            $this->applyDeclaredRelations($object, $request, false);
            $object->save();
            $this->saveMediaToStorage($object, $request);
            $this->associateRelationshipsWithID($object, $request);
            $this->applyDeclaredRelations($object, $request, true);
            $this->afterStore($request, $object);
        });

        return $this->redirectAfterSave($request);
    }

    public function edit($id)
    {
        $modelClass = $this->modelClass;
        $result = $modelClass::whereKey($id)->relationships()->excludeFromFind()->first();
        if (!$result) {
            return redirect()->route($this->moduleBasicRoute.'.index')
                ->withInput()->withErrors(['edit' => trans('validation.row_not_exist')]);
        }
        $result['_method'] = 'PUT';
        return view($this->viewFor('edit'), ['results' => $this->changeEditResultField($result)]);
    }

    public function show($id) {}

    public function update(Request $request, $id)
    {
        $modelClass = $this->modelClass;
        $object = $modelClass::find($id);
        if (!$object) {
            return redirect()->route($this->moduleBasicRoute.'.index')
                ->withInput()->withErrors(['edit' => trans('validation.row_not_exist')]);
        }
        $rules = $this->validationRules($object);
        $this->validate($request, $rules);

        $object->getConnection()->transaction(function () use ($object, $request, $rules) {
            $this->resetCache($object);
            $this->assignFields($object, $request, $rules, true);
            $this->associateRelationships($object, $request);
            $this->applyDeclaredRelations($object, $request, false);
            if ($this->saveMediaToStorage($object, $request, true)) {
                foreach ($this->binaryFields as $field) {
                    $object->{$field} = $request->input($field);
                }
            }
            $object->save();
            $this->associateRelationshipsWithID($object, $request);
            $this->applyDeclaredRelations($object, $request, true);
            $this->afterUpdate($request, $object);
        });

        return $this->redirectAfterSave($request);
    }

    public function destroy($id)
    {
        $modelClass = $this->modelClass;
        $modelClass::destroy($id);
        return redirect()->route($this->moduleBasicRoute.'.index');
    }

    /** Keep the small extension points used by existing controllers. */
    protected function viewFor(string $action): string
    {
        if (is_string($this->customView) && $this->customView !== '') {
            return $this->customView;
        }
        $template = $action === 'index' ? 'index' : 'create_edit';
        return $this->customView[$action] ?? $this->customView[$template]
            ?? $this->moduleBasicTemplatePath.'.'.$template;
    }

    /** Build rules afresh; repeated calls must not append duplicate unique clauses. */
    protected function validationRules(Model $model): array
    {
        $rules = $this->arValidationArray ?? [];
        foreach ($rules as $field => $definition) {
            $parts = is_string($definition) ? explode('|', $definition) : (array) $definition;
            foreach ($parts as &$part) {
                if (!is_string($part) || !str_starts_with($part, 'unique:')) {
                    continue;
                }
                $arguments = explode(',', substr($part, 7));
                if (count($arguments) > 2) {
                    continue; // A controller already supplied its own unique rule.
                }
                $rule = Rule::unique($arguments[0], $arguments[1] ?? $field);
                if ($model->exists) {
                    $rule->ignore($model->getKey(), $model->getKeyName());
                }
                if ($arguments[0] === $model->getTable()
                    && in_array(SoftDeletes::class, class_uses_recursive($model), true)) {
                    $rule->whereNull($model->getDeletedAtColumn());
                }
                $part = $rule;
            }
            unset($part);
            $rules[$field] = $parts;
        }
        return $rules;
    }

    /** Arrays of relation IDs and nested validation rules are not database columns. */
    protected function assignFields(Model $model, Request $request, array $rules, bool $updating = false): void
    {
        foreach (array_keys($rules) as $field) {
            if (str_contains($field, '.') || isset($this->relationFields[$field])
                || ($updating && in_array($field, $this->binaryFields, true))) {
                continue;
            }
            $value = $request->input($field);
            if ($value === '') {
                $value = null;
            }
            if ($value !== null && in_array($field, $this->dateTimeLocalFields, true)) {
                $value = Carbon::parse($value)->format('Y-m-d H:i:s');
            }
            $model->{$field} = $value;
        }
    }

    /**
     * Single relations before save; pivots after save. Missing fields are preserved.
     * A hidden _relations_present[field]=1 lets a cleared multi-select detach all.
     */
    protected function applyDeclaredRelations(Model $model, Request $request, bool $afterSave): void
    {
        foreach ($this->relationFields as $field => $name) {
            $present = $request->has($field) || $request->input('_relations_present.'.$field) === '1';
            if (!$present) {
                continue;
            }
            $relation = $model->{$name}();
            if ($relation instanceof BelongsTo && !$afterSave) {
                $model->associateAdminRelation($name, $request->input($field), $field);
            } elseif ($relation instanceof BelongsToMany && $afterSave) {
                $model->syncAdminRelation($name, $request->input($field, []), $field);
            } elseif (!$relation instanceof BelongsTo && !$relation instanceof BelongsToMany) {
                throw new LogicException("The editable relationship {$name} must be belongsTo or belongsToMany.");
            }
        }
    }

    protected function redirectAfterSave(Request $request)
    {
        return $request->filled('custom_route')
            ? redirect($request->input('custom_route'))
            : redirect()->route($this->moduleBasicRoute.'.index');
    }

}
