<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\User;
use Illuminate\Database\Seeder;

/** Optional sample data only. Does not create users or modify the login system. */
class ProjectRelationsDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'development', 'testing'])) {
            throw new \RuntimeException('Run the demo seeder only in a local or testing environment.');
        }

        \Illuminate\Support\Facades\DB::transaction(function (): void {
            $web = ProjectLabel::firstOrCreate(['name' => 'Demo: Web']);
            $design = ProjectLabel::firstOrCreate(['name' => 'Demo: Design']);
            $backend = ProjectLabel::firstOrCreate(['name' => 'Demo: Backend']);
            $owner = User::query()->where('active', true)->orderBy('id')->value('id');
            $first = Project::firstOrCreate(['name' => 'Demo: Website'], ['owner_id' => $owner]);
            $second = Project::firstOrCreate(['name' => 'Demo: Internal application'], ['owner_id' => $owner]);
            $first->labels()->syncWithoutDetaching([$web->id, $design->id]);
            $second->labels()->syncWithoutDetaching([$web->id, $backend->id]);
            $first->tasks()->firstOrCreate(['name' => 'Demo: Prepare content'], ['completed' => false]);
            $first->tasks()->firstOrCreate(['name' => 'Demo: Design homepage'], ['completed' => true]);
            $second->tasks()->firstOrCreate(['name' => 'Demo: Test permissions'], ['completed' => false]);
        });
    }
}
