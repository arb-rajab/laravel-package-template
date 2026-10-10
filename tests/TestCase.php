<?php

declare(strict_types=1);

namespace VendorName\PackageName\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use VendorName\PackageName\PackageNameServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [PackageNameServiceProvider::class];
    }

    /**
     * SQLite in memory is the default so the suite needs no external services.
     * If your package needs a specific database (Postgres-only features, for
     * example), override the connection here and provision it in CI.
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
