<?php

declare(strict_types=1);

namespace VendorName\PackageName\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'package-name:install';

    protected $description = 'Publish the package config and migrations';

    public function handle(): int
    {
        $this->call('vendor:publish', ['--tag' => 'package-name-config']);
        $this->call('vendor:publish', ['--tag' => 'package-name-migrations']);

        $this->info('Published. Run `php artisan migrate` to create the tables.');

        return self::SUCCESS;
    }
}
