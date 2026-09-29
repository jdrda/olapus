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
use DateTimeZone;
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
            'timezones' => $this->timezoneOptions(),
        ]);
    }

    /**
     * Store function override.
     */
    public function store(Request $request)
    {
        $this->validate($request, array_merge(
            $this->profileValidationRules(),
            $this->roleValidationRules()
        ));

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

            $user->fill($this->profileData($request));
            $user->save();
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
            'timezones' => $this->timezoneOptions(),
        ]);
    }

    /**
     * Update function override.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array_merge(
            $this->profileValidationRules((int) $id),
            $this->roleValidationRules()
        ));

        $user = User::find($id);

        if ($user === null) {
            return redirect(route($this->moduleBasicRoute.'.index'))
                ->withInput()
                ->withErrors(['edit' => trans('validation.row_not_exist')]);
        }

        DB::transaction(function () use ($request, $user): void {
            $user->fill($this->profileData($request));
            $user->save();

            $this->resetPasswordWhenRequested($request, $user);
            $user->syncRoles($this->selectedRoles($request));
        });

        return redirect(route($this->moduleBasicRoute.'.index'));
    }

    /**
     * Edit the currently authenticated user's own profile.
     */
    public function selfEdit(Request $request)
    {
        return view('admin.profile.edit', [
            'results' => $request->user(),
            'timezones' => $this->timezoneOptions(),
        ]);
    }

    /**
     * Update the currently authenticated user's own profile.
     */
    public function selfUpdate(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $this->validate($request, $this->profileValidationRules($user->id, false));

        DB::transaction(function () use ($request, $user): void {
            $user->fill($this->profileData($request, false));
            $user->save();

            $this->resetPasswordWhenRequested($request, $user);
        });

        return redirect(route('admin.profile.edit'))
            ->with('status', trans('admin_module_user.profile_saved'));
    }

    private function profileValidationRules(?int $userId = null, bool $allowActive = true): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'company_name' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email:rfc', 'max:255'],
            'contact_phone' => $this->phoneValidationRules(),
            'company_id' => ['nullable', 'string', 'max:64'],
            'vat_id' => ['nullable', 'string', 'max:64'],
            'website' => ['nullable', 'url:http,https', 'max:2048'],
            'customer_number' => ['nullable', 'string', 'max:64'],
            'external_id' => ['nullable', 'string', 'max:128'],
            'locale' => ['nullable', 'string', 'max:16', 'regex:/^[A-Za-z]{2,3}(?:[-_][A-Za-z0-9]{2,8}){0,2}$/'],
            'timezone' => ['nullable', 'timezone'],

            'billing_name' => ['nullable', 'string', 'max:255'],
            'billing_contact_name' => ['nullable', 'string', 'max:255'],
            'billing_street' => ['nullable', 'string', 'max:255'],
            'billing_house_number' => ['nullable', 'string', 'max:32'],
            'billing_city' => ['nullable', 'string', 'max:128'],
            'billing_zip' => ['nullable', 'string', 'max:32'],
            'billing_state' => ['nullable', 'string', 'max:128'],
            'billing_country' => ['nullable', 'alpha', 'size:2'],
            'billing_email' => ['nullable', 'email:rfc', 'max:255'],
            'billing_phone' => $this->phoneValidationRules(),

            'shipping_name' => ['nullable', 'string', 'max:255'],
            'shipping_contact_name' => ['nullable', 'string', 'max:255'],
            'shipping_street' => ['nullable', 'string', 'max:255'],
            'shipping_house_number' => ['nullable', 'string', 'max:32'],
            'shipping_city' => ['nullable', 'string', 'max:128'],
            'shipping_zip' => ['nullable', 'string', 'max:32'],
            'shipping_state' => ['nullable', 'string', 'max:128'],
            'shipping_country' => ['nullable', 'alpha', 'size:2'],
            'shipping_email' => ['nullable', 'email:rfc', 'max:255'],
            'shipping_phone' => $this->phoneValidationRules(),
            'notice' => ['nullable', 'string', 'max:10000'],
        ];

        if ($allowActive) {
            $rules['active'] = ['sometimes', 'boolean'];
        }

        return $rules;
    }

    private function phoneValidationRules(): array
    {
        return [
            'nullable',
            'string',
            'max:32',
            'regex:/^\\+[1-9][0-9]{7,14}$/',
        ];
    }

    private function profileData(Request $request, bool $allowActive = true): array
    {
        $fields = [
            'name',
            'email',
            'company_name',
            'contact_name',
            'contact_email',
            'contact_phone',
            'company_id',
            'vat_id',
            'website',
            'customer_number',
            'external_id',
            'locale',
            'timezone',
            'billing_name',
            'billing_contact_name',
            'billing_street',
            'billing_house_number',
            'billing_city',
            'billing_zip',
            'billing_state',
            'billing_country',
            'billing_email',
            'billing_phone',
            'shipping_name',
            'shipping_contact_name',
            'shipping_street',
            'shipping_house_number',
            'shipping_city',
            'shipping_zip',
            'shipping_state',
            'shipping_country',
            'shipping_email',
            'shipping_phone',
            'notice',
        ];

        if ($allowActive && $request->has('active')) {
            $fields[] = 'active';
        }

        $data = $request->only($fields);

        foreach (['billing_country', 'shipping_country'] as $field) {
            if (! empty($data[$field])) {
                $data[$field] = strtoupper((string) $data[$field]);
            }
        }

        return $data;
    }

    private function resetPasswordWhenRequested(Request $request, User $user): void
    {
        if (! $request->filled('password') && ! $request->filled('password_confirmation')) {
            return;
        }

        app(ResetsUserPasswords::class)->reset($user, [
            'password' => (string) $request->input('password'),
            'password_confirmation' => (string) $request->input('password_confirmation'),
        ]);
    }

    private function timezoneOptions(): array
    {
        return DateTimeZone::listIdentifiers();
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
