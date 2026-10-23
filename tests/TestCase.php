<?php

declare(strict_types=1);

namespace ArtisanBuild\Turbulence\Tests;

use ArtisanBuild\Turbulence\Providers\TurbulenceServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TurbulenceServiceProvider::class,
        ];
    }
}
