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
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Spatie\Permission\Models\Role;

class UserController extends AdminModuleController
{
    public function create()
    {
        return view($this->viewFor('create'), [
            'roles' => $this->roleOptions(),
        ]);
    }

    /**
     * Store function override.
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->roleValidationRules());

        DB::transaction(function () use ($request): void {
            /** @var User $user */
            $user = app(CreatesNewUsers::class)->create(
                $request->only([
                    'name',
                    'email',
                    'password',
                    'password_confirmation',
                ])
            );

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
     * Update function override.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array_merge([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id),
            ],
        ], $this->roleValidationRules()));

        $user = User::find($id);

        if ($user === null) {
            return redirect(route($this->moduleBasicRoute.'.index'))
                ->withInput()
                ->withErrors(['edit' => trans('validation.row_not_exist')]);
        }

        DB::transaction(function () use ($request, $user): void {
            $user->name = $request->string('name')->toString();
            $user->email = $request->string('email')->toString();
            $user->save();

            if ($request->filled('password') || $request->filled('password_confirmation')) {
                app(ResetsUserPasswords::class)->reset($user, [
                    'password' => (string) $request->input('password'),
                    'password_confirmation' => (string) $request->input('password_confirmation'),
                ]);
            }

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
