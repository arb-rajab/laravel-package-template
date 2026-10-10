<?php

declare(strict_types=1);

namespace VendorName\PackageName\Facades;

use Illuminate\Support\Facades\Facade;
use VendorName\PackageName\Models\Example;

/**
 * @method static bool isEnabled()
 * @method static Example remember(string $name)
 * @method static Example|null find(string $name)
 *
 * @see \VendorName\PackageName\PackageName
 */
class PackageName extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VendorName\PackageName\PackageName::class;
    }
}
