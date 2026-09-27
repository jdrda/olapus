<?php
/**
 * User module controller
 *
 * Controller for module User
 *
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends AdminModuleController
{
    /**
     * Validation rules
     *
     * @var array
     */
    protected $arValidationArray = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|confirmed|min:6',
    ];

    public function create()
    {
        return view($this->viewFor('create'), [
            'roles' => $this->roleOptions(),
        ]);
    }

    /**
     * Store function override
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, array_merge($this->arValidationArray, $this->roleValidationRules()));

        DB::transaction(function () use ($request): void {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);

            $user->syncRoles($this->selectedRoles($request));
        });

        return redirect(route($this->moduleBasicRoute.'.index'));
    }

    public function edit($id)
    {
        $results = User::with('roles')->find($id);

        if ($results === null) {
            return redirect(route($this->moduleBasicRoute.'.index'))
                ->withInput()
                ->withErrors(['edit' => trans('validation.row_not_exist')]);
        }

        $results['_method'] = 'PUT';

        return view($this->viewFor('edit'), [
            'results' => $results,
            'roles' => $this->roleOptions(),
        ]);
    }

    /**
     * Update function override
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array_merge([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'password' => 'required|confirmed|min:6',
        ], $this->roleValidationRules()));

        $user = User::find($id);

        if ($user === null) {
            return redirect(route($this->moduleBasicRoute.'.index'))
                ->withInput()
                ->withErrors(['edit' => trans('validation.row_not_exist')]);
        }

        DB::transaction(function () use ($request, $user): void {
            $user->name = $request['name'];
            $user->email = $request['email'];

            if ($request['password'] !== '######') {
                $user->password = Hash::make($request['password']);
            }

            $user->save();
            $user->syncRoles($this->selectedRoles($request));
        });

        return redirect(route($this->moduleBasicRoute.'.index'));
    }

    private function roleOptions()
    {
        return Role::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get();
    }

    private function roleValidationRules(): array
    {
        return [
            'roles' => ['nullable', 'array'],
            'roles.*' => [
                'integer',
                Rule::exists(config('permission.table_names.roles', 'roles'), 'id')
                    ->where(fn ($query) => $query->where('guard_name', 'web')),
            ],
        ];
    }

    private function selectedRoles(Request $request)
    {
        $ids = array_values(array_unique(array_map(
            'intval',
            $request->input('roles', [])
        )));

        return Role::query()
            ->where('guard_name', 'web')
            ->whereIn('id', $ids)
            ->get();
    }
}
