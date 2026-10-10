<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

// Publishing writes into the Testbench skeleton inside vendor/, which would
// leak into later runs (a published config shadows the package default), so
// every file the command creates is removed again.
afterEach(function () {
    File::delete(config_path('package-name.php'));
    File::delete(File::glob(database_path('migrations/*_create_package_name_examples_table.php')));
});

test('the install command publishes the config and migration', function () {
    $this->artisan('package-name:install')->assertSuccessful();

    expect(file_exists(config_path('package-name.php')))->toBeTrue()
        ->and(File::glob(database_path('migrations/*_create_package_name_examples_table.php')))->not->toBeEmpty();
});
