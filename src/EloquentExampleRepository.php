<?php

declare(strict_types=1);

namespace VendorName\PackageName;

use VendorName\PackageName\Contracts\ExampleRepository;
use VendorName\PackageName\Models\Example;

class EloquentExampleRepository implements ExampleRepository
{
    public function store(string $name): Example
    {
        return Example::query()->firstOrCreate(['name' => $name]);
    }

    public function findByName(string $name): ?Example
    {
        return Example::query()->where('name', $name)->first();
    }
}
