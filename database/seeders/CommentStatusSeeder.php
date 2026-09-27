<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentStatusSeeder extends Seeder
{
    public function run(): void
    {
        // Preserve the IDs, labels and colors used by the existing controllers.
        $statuses = [
            1 => ['name' => 'Waiting for approvement', 'color' => 'primary'],
            2 => ['name' => 'Approved', 'color' => 'success'],
            3 => ['name' => 'Declined', 'color' => 'danger'],
            4 => ['name' => 'SPAM', 'color' => 'warning'],
        ];

        foreach ($statuses as $id => $status) {
            if (! DB::table('commentstatus')->where('id', $id)->exists()) {
                DB::table('commentstatus')->insert([
                    'id' => $id,
                    ...$status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
