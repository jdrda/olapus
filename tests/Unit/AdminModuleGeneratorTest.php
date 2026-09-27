<?php

namespace Tests\Unit;

use App\Support\AdminModuleGenerator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class AdminModuleGeneratorTest extends TestCase
{
    private string $root;

    protected function setUp(): void
    {
        parent::setUp();
        $this->root = sys_get_temp_dir().'/olapus-generator-'.bin2hex(random_bytes(6));
        foreach (['stubs/olapus', 'lang/en', 'lang/cs', 'routes', 'config/admin'] as $directory) {
            mkdir($this->root.'/'.$directory, 0755, true);
        }
        $source = dirname(__DIR__, 2);
        foreach (glob($source.'/stubs/olapus/*.stub') as $stub) {
            copy($stub, $this->root.'/stubs/olapus/'.basename($stub));
        }
        foreach (['en', 'cs'] as $locale) {
            copy($source.'/lang/'.$locale.'/admin_scaffold.php', $this->root.'/lang/'.$locale.'/admin_scaffold.php');
        }
        file_put_contents($this->root.'/config/admin/menu.php', "<?php return require __DIR__.'/generated_menu.php';\n");
        file_put_contents($this->root.'/config/admin/generated_menu.php', "<?php return [];\n");
        file_put_contents($this->root.'/routes/admin_generated.php', "<?php\nuse Illuminate\\Support\\Facades\\Route;\n");
    }

    protected function tearDown(): void
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $path) {
            $path->isDir() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        rmdir($this->root);
        parent::tearDown();
    }

    public function test_multiword_module_is_consistent_and_dry_plan_writes_nothing(): void
    {
        $generator = new AdminModuleGenerator($this->root);
        $plan = $generator->plan('DocumentType', 'document_types', 'bi-file-text', 'Document types', '2026_09_22_130000');
        $this->assertFileDoesNotExist($this->root.'/app/Models/DocumentType.php');
        $generator->write($plan);
        $this->assertFileExists($this->root.'/database/migrations/2026_09_22_130000_create_document_types_table.php');
        $this->assertStringContainsString("'documentType'", file_get_contents($this->root.'/routes/admin_generated.php'));
        $menu = require $this->root.'/config/admin/generated_menu.php';
        $this->assertSame('admin.documentType.index', $menu['documenttype']['route']);
        $this->assertSame('bi bi-file-text', $menu['documenttype']['icon']);
    }

    public function test_existing_files_are_never_overwritten(): void
    {
        $generator = new AdminModuleGenerator($this->root);
        $generator->write($generator->plan('Note', 'notes', 'bi-box', 'Notes'));
        $before = file_get_contents($this->root.'/app/Models/Note.php');
        try {
            $generator->plan('Note', 'notes', 'bi-box', 'Notes');
            $this->fail('Expected an existing-file failure.');
        } catch (RuntimeException) {
            $this->assertSame($before, file_get_contents($this->root.'/app/Models/Note.php'));
        }
    }

    public function test_paths_cannot_escape_the_application(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new AdminModuleGenerator($this->root))->plan('../User', 'users', 'bi-box', 'Unsafe');
    }
}
