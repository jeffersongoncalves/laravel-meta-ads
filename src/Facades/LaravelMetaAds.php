<?php

namespace Jeffersongoncalves\LaravelMetaAds\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\LaravelMetaAds\LaravelMetaAds
 */
class LaravelMetaAds extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Jeffersongoncalves\LaravelMetaAds\LaravelMetaAds::class;
    }
}
