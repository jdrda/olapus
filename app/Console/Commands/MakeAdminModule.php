<?php

namespace App\Console\Commands;

use App\Support\AdminModuleGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeAdminModule extends Command
{
    protected $signature = 'make:admin-module {module : PascalCase model name, e.g. DocumentType}
        {--icon=bi-box : Bootstrap icon without the leading bi class}
        {--table= : Override the default plural snake_case table}
        {--label= : Initial label; customize translations afterwards}
        {--dry-run : List planned files without writing them}';

    protected $description = 'Generate an Olapus CRUD module, migration, views, translations, route and menu from stubs';

    public function handle(): int
    {
        $module = (string) $this->argument('module');
        $table = $this->option('table') ?: Str::snake(Str::pluralStudly($module));
        $label = $this->option('label') ?: Str::headline($module);
        $generator = new AdminModuleGenerator(base_path());
        try {
            $files = $generator->plan($module, $table, (string) $this->option('icon'), $label);
            foreach (array_keys($files) as $path) {
                $this->line($path);
            }
            if ($this->option('dry-run')) {
                $this->info('Dry run: no files were changed.');
                return self::SUCCESS;
            }
            $generator->write($files);
            $this->info('Module created. No existing module was overwritten; no migration was executed.');
            $this->line('Next: php artisan optimize:clear');
            $this->line('Then review the migration and run: php artisan migrate');
            return self::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
            return self::FAILURE;
        }
    }
}
