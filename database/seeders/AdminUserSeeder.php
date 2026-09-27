<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use RuntimeException;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->command === null || $this->command->option('no-interaction')) {
            throw new RuntimeException(
                'Run php artisan db:seed --class=AdminUserSeeder in an interactive terminal.'
            );
        }

        $email = trim((string) $this->command->ask('Administrator email'));

        Validator::make(['email' => $email], [
            'email' => ['required', 'email:rfc', 'max:255'],
        ])->validate();

        if (DB::table('users')->where('email', $email)->exists()) {
            $this->command->warn('This email already exists. The account was left unchanged.');
            return;
        }

        $name = trim((string) $this->command->ask('Administrator name', 'Administrator'));
        $password = (string) $this->command->secret('Password', false);
        $confirmation = (string) $this->command->secret('Confirm password', false);

        DB::transaction(function () use ($email, $name, $password, $confirmation): void {
            $this->call(SystemUserSeeder::class);

            $role = Role::findOrCreate('admin', 'web');

            $user = app(CreatesNewUsers::class)->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $confirmation,
            ]);

            $user->active = true;
            $user->save();
            $user->assignRole($role);
        });

        $this->command->info('Administrator created and assigned to the Spatie role "admin".');
    }
}
