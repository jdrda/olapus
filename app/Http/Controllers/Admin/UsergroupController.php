<?php
/**
 * Usergroup module controller
 *
 * Administration UI for Spatie roles. The module name stays Usergroup in Olapus,
 * while persistence and assignments use Spatie's standard Role model and tables.
 *
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsergroupController extends AdminModuleController
{
    protected $modelClass = Role::class;

    public function index(Request $request)
    {
        $query = Role::query()
            ->where('guard_name', 'web')
            ->withCount('users');

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $sortable = ['id', 'name', 'description', 'updated_at'];
        $orderBy = (string) $request->query('orderbycolumn', 'id');
        $orderType = strtolower((string) $request->query('orderbytype', 'desc'));

        if (! in_array($orderBy, $sortable, true)) {
            $orderBy = 'id';
        }
        if (! in_array($orderType, ['asc', 'desc'], true)) {
            $orderType = 'desc';
        }

        return view($this->viewFor('index'), [
            'results' => $query->orderBy($orderBy, $orderType)->paginate($this->getRowsToPaginate()),
        ]);
    }

    public function create()
    {
        return view($this->viewFor('create'));
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, $this->rules());

        Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect(route($this->moduleBasicRoute.'.index'));
    }

    public function edit($id)
    {
        $results = Role::query()
            ->where('guard_name', 'web')
            ->find($id);

        if ($results === null) {
            return redirect(route($this->moduleBasicRoute.'.index'))
                ->withInput()
                ->withErrors(['edit' => trans('validation.row_not_exist')]);
        }

        $results['_method'] = 'PUT';

        return view($this->viewFor('edit'), ['results' => $results]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::query()
            ->where('guard_name', 'web')
            ->find($id);

        if ($role === null) {
            return redirect(route($this->moduleBasicRoute.'.index'))
                ->withInput()
                ->withErrors(['edit' => trans('validation.row_not_exist')]);
        }

        $validated = $this->validate($request, $this->rules((int) $role->getKey()));

        $role->name = $validated['name'];
        $role->description = $validated['description'] ?? null;
        $role->save();

        return redirect(route($this->moduleBasicRoute.'.index'));
    }

    public function destroy($id)
    {
        $role = Role::query()
            ->where('guard_name', 'web')
            ->find($id);

        if ($role !== null) {
            $role->delete();
        }

        return redirect(route($this->moduleBasicRoute.'.index'));
    }

    private function rules(?int $ignoreId = null): array
    {
        $name = Rule::unique(config('permission.table_names.roles', 'roles'), 'name')
            ->where(fn ($query) => $query->where('guard_name', 'web'));

        if ($ignoreId !== null) {
            $name->ignore($ignoreId);
        }

        return [
            'name' => ['required', 'string', 'max:255', $name],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
