<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SystemUserSeeder extends Seeder
{
    public function run(): void
    {
        // article.user_id and page.user_id historically default to user ID 1.
        // Do not take over ID 1 if an existing installation uses it differently.
        if (DB::table('users')->where('id', 1)->exists()) {
            return;
        }

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'system',
            'email' => 'system@olapus.invalid',
            // This random password is never displayed or distributed.
            'password' => Hash::make(Str::random(64)),
            'active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
