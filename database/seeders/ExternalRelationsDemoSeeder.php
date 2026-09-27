<?php

namespace Database\Seeders;

use App\Models\ExternalRecord;
use App\Models\ExternalRow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/** Optional local demo; no user accounts, external calls or production data. */
class ExternalRelationsDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'development', 'testing'])) {
            throw new RuntimeException('Run demo seeders only in local/development/testing.');
        }
        DB::transaction(function (): void {
            foreach ([1, 2] as $source) {
                $record = ExternalRecord::withTrashed()->firstOrCreate(
                    ['source_id' => $source, 'external_id' => 100],
                    ['name' => 'Demo: Source '.$source.' / Record 100']
                );
                // Do not restore or modify an existing (possibly soft-deleted) record.
                if (!$record->wasRecentlyCreated && ($record->trashed() || $record->name !== 'Demo: Source '.$source.' / Record 100')) { continue; }
                foreach ([1, 2] as $number) {
                    ExternalRow::withTrashed()->firstOrCreate([
                        'source_id' => $source, 'record_external_id' => 100,
                        'name' => 'Demo: Source '.$source.' / Row '.$number,
                    ]);
                }
            }
        });
    }
}
