<?php

namespace Tests;

use Illuminate\Foundation\Application;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    public function createApplication(): Application
    {
        $app = parent::createApplication();
        // Never allow RefreshDatabase to touch a developer's persistent database,
        // even if their config cache still contains production/local DB settings.
        $app['config']->set([
            'app.env' => 'testing',
            'app.key' => 'base64:'.base64_encode(str_repeat('t', 32)),
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.url' => null,
            'cache.default' => 'array',
            'session.driver' => 'array',
            'queue.default' => 'sync',
            'mail.default' => 'array',
        ]);
        $app['db']->purge();
        return $app;
    }
}
