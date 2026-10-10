<?php

declare(strict_types=1);

use VendorName\PackageName\Contracts\ExampleRepository;
use VendorName\PackageName\Facades\PackageName;
use VendorName\PackageName\Models\Example;
use VendorName\PackageName\PackageName as Manager;

test('remember stores an example and find returns it', function () {
    $stored = PackageName::remember('first');

    expect($stored)->toBeInstanceOf(Example::class)
        ->and(PackageName::find('first')?->is($stored))->toBeTrue()
        ->and(PackageName::find('missing'))->toBeNull();
});

test('remember is idempotent for the same name', function () {
    PackageName::remember('same');
    PackageName::remember('same');

    expect(Example::query()->count())->toBe(1);
});

test('the enabled flag is read from config', function () {
    config(['package-name.enabled' => false]);

    expect(app(Manager::class)->isEnabled())->toBeFalse();
});

test('the repository binding can be swapped by the host application', function () {
    $fake = new class implements ExampleRepository
    {
        public function store(string $name): Example
        {
            return new Example(['name' => 'from-fake']);
        }

        public function findByName(string $name): ?Example
        {
            return null;
        }
    };
    app()->instance(ExampleRepository::class, $fake);

    expect(app(Manager::class)->remember('ignored')->name)->toBe('from-fake');
});

test('the table name can be overridden before the migration runs', function () {
    config(['package-name.table' => 'custom_examples']);

    expect((new Example)->getTable())->toBe('custom_examples');
});
