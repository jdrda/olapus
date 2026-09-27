<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RuntimeException;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Explicit interactive command; never provision a known default password.
        if ($this->command === null || $this->command->option('no-interaction')) {
            throw new RuntimeException(
                'Run php artisan db:seed --class=AdminUserSeeder in an interactive terminal.'
            );
        }

        $email = trim((string) $this->command->ask('Administrator email'));

        Validator::make(['email' => $email], [
            'email' => ['required', 'email:rfc', 'max:255'],
        ])->validate();

        // Includes soft-deleted users. No password reset or privilege escalation.
        if (DB::table('users')->where('email', $email)->exists()) {
            $this->command->warn('This email already exists. The account was left unchanged.');
            return;
        }

        $name = trim((string) $this->command->ask('Administrator name', 'Administrator'));

        // Disable visible fallback: a terminal must be able to hide the password.
        $password = (string) $this->command->secret('Password (at least 15 characters)', false);
        $confirmation = (string) $this->command->secret('Confirm password', false);

        Validator::make([
            'name' => $name,
            'password' => $password,
            'password_confirmation' => $confirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:15', 'confirmed'],
        ])->validate();

        // bcrypt truncates input beyond 72 bytes; reject instead of truncating.
        if (config('hashing.driver', 'bcrypt') === 'bcrypt' && strlen($password) > 72) {
            throw new RuntimeException('For bcrypt, use a password of at most 72 UTF-8 bytes.');
        }

        DB::transaction(function () use ($email, $name, $password): void {
            $this->call(SystemUserSeeder::class);

            $role = Role::findOrCreate('admin', 'web');

            // Direct insert intentionally avoids unknown application mutators.
            // Hash once using Laravel; keep the existing session / Laravel UI setup.
            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            User::where('email', $email)->firstOrFail()->assignRole($role);
        });

        $this->command->info('Administrator created and assigned to the Spatie role "admin". Sign in using the existing login form.');
    }
}
