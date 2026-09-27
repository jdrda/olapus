<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupSeeder extends Seeder
{
    public function run(): void
    {
        // These existing IDs are part of the Olapus authorization contract.
        $groups = [1 => 'Superadmin', 2 => 'Admin', 3 => 'User', 4 => 'Customer'];

        foreach ($groups as $id => $name) {
            // Never rename, restore or overwrite an existing group.
            if (! DB::table('usergroup')->where('id', $id)->exists()) {
                DB::table('usergroup')->insert([
                    'id' => $id,
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
