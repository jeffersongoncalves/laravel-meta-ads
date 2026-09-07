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
            ->hasConfigFile('laravel-meta-ads');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(LaravelMetaAds::class, fn () => new LaravelMetaAds(
            config('laravel-meta-ads.access_token'),
            config('laravel-meta-ads.ad_account_id'),
            config('laravel-meta-ads.api_version'),
        ));

        $this->app->alias(LaravelMetaAds::class, 'laravel-meta-ads');
    }
}
