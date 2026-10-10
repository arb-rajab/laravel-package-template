<?php

declare(strict_types=1);

namespace VendorName\PackageName\Contracts;

use VendorName\PackageName\Models\Example;

interface ExampleRepository
{
    public function store(string $name): Example;

    public function findByName(string $name): ?Example;
}
