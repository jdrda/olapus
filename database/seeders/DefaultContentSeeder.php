<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultContentSeeder extends Seeder
{
    public function run(): void
    {
        // slide.slider_id defaults to 1 in the existing schema.
        if (! DB::table('slider')->where('id', 1)->exists()) {
            DB::table('slider')->insert([
                'id' => 1,
                'name' => 'Default',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! DB::table('articlecategory')->where('id', 1)->exists()) {
            DB::table('articlecategory')->insert([
                'id' => 1,
                'name' => 'General',
                'color' => 'secondary',
                'meta_title' => 'General category',
                'meta_description' => 'General category',
                'meta_keywords' => 'General category',
                'url' => 'default',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // No image categories: the old custom image module has been retired.
        // No homepage / comments: optional examples belong to DemoContentSeeder.
    }
}
