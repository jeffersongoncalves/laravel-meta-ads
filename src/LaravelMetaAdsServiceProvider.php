<?php

namespace Jeffersongoncalves\LaravelMetaAds;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelMetaAdsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-meta-ads')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
