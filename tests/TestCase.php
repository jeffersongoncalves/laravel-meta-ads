<?php

namespace Jeffersongoncalves\LaravelMetaAds\Tests;

use Jeffersongoncalves\LaravelMetaAds\LaravelMetaAdsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelMetaAdsServiceProvider::class,
        ];
    }
}
