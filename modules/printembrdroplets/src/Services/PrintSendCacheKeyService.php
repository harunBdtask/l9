<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Services;

use Illuminate\Support\Facades\Cache;

class PrintSendCacheKeyService
{
    protected $userId, $factoryId;

    public function __construct()
    {
        $this->setUserId();
        $this->setFactoryId();
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    private function setUserId(): PrintSendCacheKeyService
    {
        $this->userId = userId();
        return $this;
    }

    public function getFactoryId(): int
    {
        return $this->factoryId;
    }

    private function setFactoryId(): PrintSendCacheKeyService
    {
        $this->factoryId = factoryId();
        return $this;
    }

    public function getChallanNoCacheKey(): string
    {
        return 'print_send_challan_no_' . $this->getUserId() . $this->getFactoryId();
    }

    public function getChallanBundlesCacheKey(): string
    {
        return 'print_send_challan_details_' . $this->getUserId() . $this->getFactoryId();
    }

    public function updateChallanBundlesCache($bundleCardId, $bundleCard): void
    {
        $cacheData = [];
        $cacheKey = $this->getChallanBundlesCacheKey();
        if (Cache::has($cacheKey)) {
            $cacheData = Cache::get($cacheKey);
        }
        $cacheDataKey = count($cacheData);
        $cacheData[$cacheDataKey] = [
            'bundle_card_id' => $bundleCardId,
            'buyer_name' => $bundleCard->buyer->name ?? '',
            'style_name' => $bundleCard->order->style_name ?? '',
            'po_no' => $bundleCard->purchaseOrder->po_no ?? '',
            'color_name' => $bundleCard->color->name ?? '',
            'lot_no' => $bundleCard->lot->lot_no ?? '',
            'size_name' => ($bundleCard->size->name ?? '') . ($bundleCard->suffix ? '(' . $bundleCard->suffix . ')' : ''),
            'cutting_no' => $bundleCard->cutting_no ?? '',
            'bundle_no' => $bundleCard->details->is_manual == 1 ? $bundleCard->size_wise_bundle_no : ($bundleCard->{getbundleCardSerial()} ?? $bundleCard->size_wise_bundle_no ?? $bundleCard->bundle_no ?? ''),
            'quantity' => $bundleCard->quantity,
            'total_rejection' => $bundleCard->total_rejection,
        ];
        Cache::put($cacheKey, $cacheData, 86400);
    }

    public function updateChallanBundlesCacheRejectionQty($bundleCardId, $rejectionQty): void
    {
        $cacheKey = $this->getChallanBundlesCacheKey();
        if (Cache::has($cacheKey)) {
            $cacheData = Cache::get($cacheKey);
            $updatedCacheData = [];
            foreach ($cacheData as $key => $cacheData) {
                $updatedCacheData[$key] = [
                    "bundle_card_id" => $cacheData['bundle_card_id'],
                    "buyer_name" => $cacheData['buyer_name'],
                    "style_name" => $cacheData['style_name'],
                    "po_no" => $cacheData['po_no'],
                    "color_name" => $cacheData['color_name'],
                    "lot_no" => $cacheData['lot_no'],
                    "size_name" => $cacheData['size_name'],
                    "cutting_no" => $cacheData['cutting_no'],
                    "bundle_no" => $cacheData['bundle_no'],
                    "quantity" => $cacheData['quantity'],
                    "total_rejection" => $cacheData['bundle_card_id'] == $bundleCardId ? $rejectionQty : $cacheData['total_rejection'],
                ];
            }
            Cache::put($cacheKey, collect($updatedCacheData), 86400);
        }
    }

    public function removeCache(): void
    {
        $challanNoCacheKey = $this->getChallanNoCacheKey();
        $challanBundlesCacheKey = $this->getChallanBundlesCacheKey();

        if (Cache::has($challanNoCacheKey)) {
            Cache::forget($challanNoCacheKey);
        }

        if (Cache::has($challanBundlesCacheKey)) {
            Cache::forget($challanBundlesCacheKey);
        }
    }
}
