<?php

declare(strict_types=1);

namespace VendorName\PackageName;

use VendorName\PackageName\Contracts\ExampleRepository;
use VendorName\PackageName\Models\Example;

/**
 * The package's public entry point. Replace the example behaviour with your own.
 */
class PackageName
{
    public function __construct(
        private readonly ExampleRepository $examples,
        private readonly bool $enabled = true,
    ) {}

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function remember(string $name): Example
    {
        return $this->examples->store($name);
    }

    public function find(string $name): ?Example
    {
        return $this->examples->findByName($name);
    }
}
