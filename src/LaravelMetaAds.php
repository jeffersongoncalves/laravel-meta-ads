<?php

namespace Jeffersongoncalves\LaravelMetaAds;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class LaravelMetaAds
{
    public function __construct(
        protected ?string $accessToken,
        protected ?string $adAccountId,
        protected string $apiVersion,
    ) {}

    public function accounts(): Response
    {
        return $this->get('/me/adaccounts', ['fields' => 'id,name,account_status']);
    }

    public function campaigns(?string $adAccountId = null): Response
    {
        return $this->get('/act_'.$this->resolveAccountId($adAccountId).'/campaigns', [
            'fields' => 'id,name,status,objective,daily_budget',
        ]);
    }

    public function campaignInsights(string $campaignId, string $datePreset = 'last_30d'): Response
    {
        return $this->get("/{$campaignId}/insights", [
            'fields' => 'impressions,clicks,spend,actions,cost_per_action_type',
            'date_preset' => $datePreset,
        ]);
    }

    public function createCampaign(string $name, string $objective, string $status = 'PAUSED', ?string $adAccountId = null): Response
    {
        return $this->post('/act_'.$this->resolveAccountId($adAccountId).'/campaigns', [
            'name' => $name,
            'objective' => $objective,
            'status' => $status,
            'special_ad_categories' => [],
        ]);
    }

    public function updateCampaignStatus(string $campaignId, string $status): Response
    {
        return $this->post("/{$campaignId}", ['status' => $status]);
    }

    public function adSets(?string $adAccountId = null): Response
    {
        return $this->get('/act_'.$this->resolveAccountId($adAccountId).'/adsets', [
            'fields' => 'id,name,status,targeting,daily_budget,bid_amount',
        ]);
    }

    public function ads(string $adSetId): Response
    {
        return $this->get("/{$adSetId}/ads", ['fields' => 'id,name,status,creative']);
    }

    public function audiences(?string $adAccountId = null): Response
    {
        return $this->get('/act_'.$this->resolveAccountId($adAccountId).'/customaudiences', [
            'fields' => 'id,name,approximate_count',
        ]);
    }

    public function createLookalikeAudience(string $sourceId, string $country, string $name = 'Lookalike Audience', ?string $adAccountId = null): Response
    {
        return $this->post('/act_'.$this->resolveAccountId($adAccountId).'/customaudiences', [
            'name' => $name,
            'subtype' => 'LOOKALIKE',
            'origin_audience_id' => $sourceId,
            'lookalike_spec' => json_encode(['type' => 'similarity', 'country' => $country]),
        ]);
    }

    protected function resolveAccountId(?string $adAccountId): string
    {
        $adAccountId ??= $this->adAccountId;

        throw_if(blank($adAccountId), new InvalidArgumentException('ad_account_id required (pass explicitly or set META_AD_ACCOUNT_ID)'));

        return $adAccountId;
    }

    protected function get(string $path, array $query = []): Response
    {
        return $this->client()->get($path, $query);
    }

    protected function post(string $path, array $data = []): Response
    {
        return $this->client()->post($path, $data);
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl("https://graph.facebook.com/{$this->apiVersion}")
            ->withToken($this->accessToken);
    }
}
