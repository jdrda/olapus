<?php

// Fast, dependency-free checks for the URL parser and file generator.
// Run: php tests/smoke-admin-tools.php
require __DIR__.'/../app/Support/RelationFilters.php';
require __DIR__.'/../app/Support/AdminModuleGenerator.php';

use App\Support\AdminModuleGenerator;
use App\Support\RelationFilters;

$checks = 0;
$check = function (bool $condition, string $message) use (&$checks): void {
    if (!$condition) { throw new RuntimeException($message); }
    $checks++;
};
$rejects = function (callable $callable, string $message) use ($check): void {
    try { $callable(); } catch (InvalidArgumentException|RuntimeException|ParseError) { $check(true, $message); return; }
    $check(false, $message);
};

$filters = RelationFilters::parse('Label:2,owner:7,label:1,label:2');
$check($filters === ['label' => ['1', '2'], 'owner' => ['7']], 'Repeated filter keys must preserve distinct values.');
$check(RelationFilters::encode($filters) === 'label:1,label:2,owner:7', 'Canonical URL failed.');
$check(RelationFilters::encode(RelationFilters::without($filters, 'label', '1')) === 'label:2,owner:7', 'Single value removal failed.');
$check(RelationFilters::encode(RelationFilters::without($filters, 'label')) === 'owner:7', 'Group removal failed.');
$check(RelationFilters::ids(['0', '001', '1', '']) === ['0', '1'], 'Zero/duplicate handling failed.');
$check(RelationFilters::parse(null) === [] && RelationFilters::parse('') === [], 'Empty filters failed.');
$check(RelationFilters::ids(['abc-def-123']) === ['abc-def-123'], 'String/UUID key handling failed.');
$rejects(fn () => RelationFilters::parse(['x']), 'Array relation must fail.');
$rejects(fn () => RelationFilters::parse('label:1 OR 1=1'), 'SQL-like input must fail.');
$rejects(fn () => RelationFilters::parse('bad.key:1'), 'Unknown syntax must fail.');
$rejects(fn () => RelationFilters::ids([['1']]), 'Nested values must fail.');
$rejects(fn () => RelationFilters::ids(range(1, 101)), 'Overlong filter list must fail.');
$rejects(fn () => RelationFilters::parse(str_repeat('a', 8001)), 'Overlong query must fail.');

$root = sys_get_temp_dir().'/olapus-tools-'.bin2hex(random_bytes(6));
try {
    foreach (['stubs/olapus', 'config/admin', 'routes', 'database/migrations'] as $directory) {
        mkdir($root.'/'.$directory, 0755, true);
    }
    foreach (glob(__DIR__.'/../stubs/olapus/*.stub') as $stub) {
        copy($stub, $root.'/stubs/olapus/'.basename($stub));
    }
    foreach (glob(__DIR__.'/../lang/*/admin_scaffold.php') as $file) {
        $locale = basename(dirname($file));
        mkdir($root.'/lang/'.$locale, 0755, true);
        copy($file, $root.'/lang/'.$locale.'/admin_scaffold.php');
    }
    file_put_contents($root.'/config/admin/menu.php', "<?php return require __DIR__.'/generated_menu.php';\n");
    file_put_contents($root.'/config/admin/generated_menu.php', "<?php return [];\n");
    file_put_contents($root.'/routes/admin_generated.php', "<?php\nuse Illuminate\\Support\\Facades\\Route;\n");
    $generator = new AdminModuleGenerator($root);
    $plan = $generator->plan('DocumentType', 'document_types', 'bi-file-text', "Document types", '2026_09_22_130000');
    $check(!file_exists($root.'/app/Models/DocumentType.php'), 'Dry plan wrote a file.');
    $check(count($plan) === 17, 'Expected five base files, ten translations and two registries.');
    $generator->write($plan);
    foreach ($plan as $relative => $content) {
        $check(file_get_contents($root.'/'.$relative) === $content, 'Generated file mismatch: '.$relative);
        if (str_ends_with($relative, '.php') && !str_ends_with($relative, '.blade.php')) {
            token_get_all($content, TOKEN_PARSE);
        }
    }
    $menu = require $root.'/config/admin/generated_menu.php';
    $check($menu['documenttype']['route'] === 'admin.documentType.index', 'CamelCase route mismatch.');
    $check($menu['documenttype']['icon'] === 'bi bi-file-text', 'Icon mismatch.');
    $check(str_contains(file_get_contents($root.'/app/Models/DocumentType.php'), "namespace App\\Models;"), 'Model namespace mismatch.');
    $check(str_contains(file_get_contents($root.'/routes/admin_generated.php'), "Route::resource('documentType'"), 'Resource name mismatch.');
    $before = hash_file('sha256', $root.'/config/admin/generated_menu.php');
    $rejects(fn () => $generator->plan('DocumentType', 'document_types', 'bi-file-text', 'Other'), 'Existing module must be rejected.');
    $check($before === hash_file('sha256', $root.'/config/admin/generated_menu.php'), 'Rejected module changed existing files.');
    $rejects(fn () => $generator->plan('../Escape', 'escape', 'bi-box', 'Escape'), 'Path traversal must fail.');
    $rejects(fn () => $generator->plan('Safe', 'bad/table', 'bi-box', 'Safe'), 'Invalid table must fail.');
    $rejects(fn () => $generator->plan('Safe', 'safe', 'fa fa-box', 'Safe'), 'Invalid icon must fail.');
    $rejects(fn () => $generator->plan('Class', 'classes', 'bi-box', 'Reserved name'), 'Reserved PHP word must fail.');
    $safePlan = $generator->plan('Note', 'notes', 'bi-sticky', "A label with ' and :module");
    $generator->write($safePlan);
    $label = require $root.'/lang/en/admin_module_note.php';
    $check($label['name'] === "A label with ' and :module", 'PHP label escaping failed.');
} finally {
    if (is_dir($root)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $path) {
            $path->isDir() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        rmdir($root);
    }
}

echo "OK: {$checks} parser/generator checks.\n";
