<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use VendorName\PackageName\PackageNameServiceProvider;

test('the Testbench harness boots a Laravel application with the package service provider registered', function () {
    expect($this->app)->toBeInstanceOf(Application::class);
    expect($this->app->getProviders(PackageNameServiceProvider::class))->not->toBeEmpty();
});

test('the package config is merged with its defaults', function () {
    expect(config('package-name.enabled'))->toBeTrue()
        ->and(config('package-name.table'))->toBe('package_name_examples');
});
