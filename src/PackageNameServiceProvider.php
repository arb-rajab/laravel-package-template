<?php

declare(strict_types=1);

namespace VendorName\PackageName;

use Illuminate\Support\ServiceProvider;
use VendorName\PackageName\Console\InstallCommand;
use VendorName\PackageName\Contracts\ExampleRepository;

class PackageNameServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/package-name.php', 'package-name');

        $this->app->bind(ExampleRepository::class, EloquentExampleRepository::class);

        // Not a singleton: the manager must resolve whatever ExampleRepository
        // is bound at the time, so tests and host apps can rebind it.
        $this->app->bind(PackageName::class, fn ($app): PackageName => new PackageName(
            $app->make(ExampleRepository::class),
            (bool) $app['config']->get('package-name.enabled', true),
        ));
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/package-name.php' => $this->app->configPath('package-name.php'),
        ], 'package-name-config');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
        ], 'package-name-migrations');

        $this->commands([
            InstallCommand::class,
        ]);
    }
}
