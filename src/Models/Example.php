<?php

declare(strict_types=1);

namespace VendorName\PackageName\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Example extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return (string) config('package-name.table', 'package_name_examples');
    }
}
