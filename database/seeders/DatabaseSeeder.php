<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Only required reference / default data. No demo content or login account.
        $this->call([
            CommentStatusSeeder::class,
            SystemUserSeeder::class,
            DefaultContentSeeder::class,
        ]);
    }
}
