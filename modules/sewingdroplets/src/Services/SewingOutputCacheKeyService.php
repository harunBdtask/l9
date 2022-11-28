<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Services;

use Illuminate\Support\Facades\Cache;

class SewingOutputCacheKeyService
{
    protected $userId, $factoryId;

    public function __construct()
    {
        $this->setUserId();
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    private function setUserId(): SewingOutputCacheKeyService
    {
        $this->userId = userId();
        return $this;
    }

    public function getChallanNoCacheKey(): string
    {
        return 'output_challan_no' . $this->getUserId();
    }

    public function getChallanBundlesCacheKey(): string
    {
        return 'output_challan_details_' . $this->getUserId();
    }

    public function updateCacheData($details): void
    {
        $cacheData = [];
        $cacheKey = $this->getChallanBundlesCacheKey();
        if (Cache::has($cacheKey)) {
            $cacheData = Cache::get($cacheKey);
        }
        $cacheDataKey = count($cacheData);
        $cacheData[$cacheDataKey] = $details;
        Cache::put($cacheKey, $cacheData, 86400);
    }

    public function updateCacheRejectionQty($bundleCardId, $details): void
    {
        $cacheKey = $this->getChallanBundlesCacheKey();
        if (Cache::has($cacheKey)) {
            $cacheData = Cache::get($cacheKey);
            $updatedCacheData = [];
            foreach ($cacheData as $key => $cacheData) {
                $updatedCacheData[$key] = $bundleCardId == $cacheData['bundle_card_id'] ? $details : $cacheData;
            }
            Cache::put($cacheKey, collect($updatedCacheData), 86400);
        }
    }

    public function removeCache(): void
    {
        $challanNocacheKey = $this->getChallanNoCacheKey();
        $challanBundlescacheKey = $this->getChallanBundlesCacheKey();
        if (Cache::has($challanNocacheKey)) {
            Cache::forget($challanNocacheKey);
        }
        if (Cache::has($challanBundlescacheKey)) {
            Cache::forget($challanBundlescacheKey);
        }
    }
}
