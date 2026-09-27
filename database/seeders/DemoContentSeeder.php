<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        // Samples are opt-in and cannot be installed into production by --force.
        if (! app()->environment('local', 'development', 'testing')) {
            throw new RuntimeException('DemoContentSeeder is only allowed in local, development or testing.');
        }

        $this->call(DatabaseSeeder::class);

        // Reuse an existing homepage but never overwrite its content.
        $page = DB::table('page')->where('url', '/')->first();

        if ($page !== null && $page->deleted_at !== null) {
            $this->command?->warn('The existing homepage is soft-deleted; demo content was not added.');
            return;
        }

        $pageId = $page?->id ?? DB::table('page')->insertGetId([
            'name' => 'Homepage',
            'url' => '/',
            'meta_title' => 'Homepage',
            'meta_keywords' => 'Homepage',
            'meta_description' => 'Homepage',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'published_at' => now(),
        ]);

        if (! DB::table('comment')->where('page_id', $pageId)
            ->where('headline', 'Test comment')->where('text', 'Hello, this is test comment')->exists()) {
            DB::table('comment')->insert([
                'name' => 'John Doe',
                'headline' => 'Test comment',
                'url' => 'https://example.com',
                'email' => 'johndoe@example.com',
                'text' => 'Hello, this is test comment',
                'commentstatus_id' => 1,
                'page_id' => $pageId,
                'article_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
