<?php

use Illuminate\Support\Facades\Http;
use Jeffersongoncalves\LaravelMetaAds\Facades\LaravelMetaAds;

it('lists ad accounts', function () {
    Http::fake([
        'graph.facebook.com/*/me/adaccounts*' => Http::response(['data' => [['id' => 'act_1', 'name' => 'Test']]]),
    ]);

    $response = LaravelMetaAds::accounts();

    expect($response->json('data.0.id'))->toBe('act_1');

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), '/me/adaccounts'));
});

it('lists campaigns for the default ad account', function () {
    config(['laravel-meta-ads.ad_account_id' => '123']);

    Http::fake([
        'graph.facebook.com/*/act_123/campaigns*' => Http::response(['data' => []]),
    ]);

    LaravelMetaAds::campaigns();

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), '/act_123/campaigns'));
});

it('throws when no ad account id is available', function () {
    config(['laravel-meta-ads.ad_account_id' => null]);

    LaravelMetaAds::campaigns();
})->throws(InvalidArgumentException::class);

it('creates a campaign', function () {
    config(['laravel-meta-ads.ad_account_id' => '123']);

    Http::fake([
        'graph.facebook.com/*/act_123/campaigns*' => Http::response(['id' => '456']),
    ]);

    $response = LaravelMetaAds::createCampaign('My Campaign', 'OUTCOME_TRAFFIC');

    expect($response->json('id'))->toBe('456');

    Http::assertSent(fn ($request) => $request['name'] === 'My Campaign' && $request['status'] === 'PAUSED');
});
