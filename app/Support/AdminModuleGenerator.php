<?php

namespace App\Support;

use InvalidArgumentException;
use RuntimeException;

/**
 * File-only generator. It does not run migrations or install packages.
 * Writes only new module files and the two clearly separated generated registries.
 */
class AdminModuleGenerator
{
    public function __construct(private string $root) {}

    public function plan(string $module, string $table, string $icon, string $label, ?string $timestamp = null): array
    {
        if (!preg_match('/^[A-Z][A-Za-z0-9]*$/D', $module)
            || !preg_match('/^[a-z][a-z0-9_]*$/D', $table)
            || !preg_match('/^bi-[a-z0-9-]+$/D', $icon)) {
            throw new InvalidArgumentException('Use a PascalCase model, a lowercase SQL table and a Bootstrap icon (e.g. bi-box).');
        }
        $slug = strtolower($module);
        $route = lcfirst($module);
        $timestamp ??= date('Y_m_d_His');
        if (!preg_match('/^\d{4}_\d{2}_\d{2}_\d{6}$/D', $timestamp)) {
            throw new InvalidArgumentException('Invalid migration timestamp.');
        }
        $replace = ['{{module}}' => $module, '{{table}}' => $table, '{{slug}}' => $slug, '{{icon}}' => $icon];
        $files = [
            "app/Models/{$module}.php" => $this->stub('admin_model', $replace),
            "app/Http/Controllers/Admin/{$module}Controller.php" => $this->stub('admin_controller', $replace),
            "resources/views/admin/modules/{$slug}/index.blade.php" => $this->stub('admin_index_view', $replace),
            "resources/views/admin/modules/{$slug}/create_edit.blade.php" => $this->stub('admin_create_edit_view', $replace),
            "database/migrations/{$timestamp}_create_{$table}_table.php" => $this->stub('admin_migration', $replace),
        ];
        if (glob($this->path("database/migrations/*_create_{$table}_table.php"))) {
            throw new RuntimeException("A create migration for {$table} already exists.");
        }

        foreach (glob($this->path('lang/*'), GLOB_ONLYDIR) as $directory) {
            $locale = basename($directory);
            if (!preg_match('/^[a-z]{2}(?:[_-][A-Za-z]{2})?$/D', $locale)) {
                continue;
            }
            $translation = $directory.'/admin_scaffold.php';
            if (!is_file($translation)) {
                $translation = $this->path('lang/en/admin_scaffold.php');
            }
            $strings = require $translation;
            $files["lang/{$locale}/admin_module_{$slug}.php"] = $this->stub('admin_lang', [
                '{{label}}' => var_export($label, true),
                '{{description}}' => var_export(str_replace(':module', $label, $strings['description']), true),
                '{{deleteConfirmation}}' => var_export($strings['delete_row_confirmation'], true),
                '{{fields}}' => var_export($strings['fields'], true),
            ]);
        }

        foreach ($files as $path => $content) {
            if (is_file($this->path($path))) {
                throw new RuntimeException("File already exists; nothing was written: {$path}");
            }
            if (!str_ends_with($path, '.blade.php')) {
                token_get_all($content, TOKEN_PARSE);
            }
        }

        $menuPath = $this->path('config/admin/generated_menu.php');
        $routePath = $this->path('routes/admin_generated.php');
        if (!is_file($menuPath) || !is_file($routePath)) {
            throw new RuntimeException('Install the generated menu and route registries first.');
        }
        $allMenu = require $this->path('config/admin/menu.php');
        if ($this->menuContains($allMenu, "admin.{$route}.index")) {
            throw new RuntimeException("The menu already contains admin.{$route}.index.");
        }
        foreach (glob($this->path('routes/*.php')) as $path) {
            $content = file_get_contents($path);
            if (preg_match('/Route::resource\(\s*[\'"]'.preg_quote($route, '/').'[\'"]/', $content)) {
                throw new RuntimeException("A resource route for {$route} already exists.");
            }
        }
        $menu = require $menuPath;
        if (isset($menu[$slug])) {
            throw new RuntimeException("The generated menu key {$slug} already exists.");
        }
        $menu[$slug] = [
            'name' => "admin_module_{$slug}.name", 'class' => "menu-class-{$slug}",
            'route' => "admin.{$route}.index", 'icon' => "bi {$icon}", 'items' => [],
        ];
        $files['config/admin/generated_menu.php'] = "<?php\n\n// Managed by make:admin-module. Custom menu groups stay in menu.php.\nreturn ".var_export($menu, true).";\n";
        $files['routes/admin_generated.php'] = rtrim(file_get_contents($routePath))."\n\n"
            ."Route::resource('{$route}', \\App\\Http\\Controllers\\Admin\\{$module}Controller::class)->except('show');\n";

        return $files;
    }

    public function write(array $files): void
    {
        $originals = [];
        try {
            foreach ($files as $relative => $content) {
                $path = $this->path($relative);
                $originals[$path] = is_file($path) ? file_get_contents($path) : null;
                if (!is_dir(dirname($path)) && !mkdir(dirname($path), 0755, true) && !is_dir(dirname($path))) {
                    throw new RuntimeException("Cannot create directory: {$relative}");
                }
                if (file_put_contents($path, $content, LOCK_EX) === false) {
                    throw new RuntimeException("Cannot write file: {$relative}");
                }
            }
        } catch (\Throwable $exception) {
            foreach ($originals as $path => $content) {
                if ($content === null) {
                    if (is_file($path)) { unlink($path); }
                } else {
                    file_put_contents($path, $content, LOCK_EX);
                }
            }
            throw $exception;
        }
    }

    private function menuContains(array $menu, string $route): bool
    {
        foreach ($menu as $item) {
            if (($item['route'] ?? null) === $route || $this->menuContains($item['items'] ?? [], $route)) {
                return true;
            }
        }
        return false;
    }

    private function stub(string $name, array $replace): string
    {
        $path = $this->path("stubs/olapus/{$name}.stub");
        if (!is_file($path)) {
            throw new RuntimeException("Missing stub: {$path}");
        }
        return strtr(file_get_contents($path), $replace);
    }

    private function path(string $path): string
    {
        return rtrim($this->root, '/\\').'/'.$path;
    }
}
