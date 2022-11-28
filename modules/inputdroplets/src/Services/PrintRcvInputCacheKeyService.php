<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Services;

use Illuminate\Support\Facades\Cache;

class PrintRcvInputCacheKeyService
{
    protected $userId, $itemStatus;

    protected $itemStatusOptions = [
        0 => 'solid_input',
        1 => 'print_embr_input'
    ];

    public function __construct()
    {
        $this->setUserId();
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    private function setUserId(): PrintRcvInputCacheKeyService
    {
        $this->userId = userId();
        return $this;
    }

    public function getItemStatus(): int
    {
        return $this->itemStatus;
    }

    public function setItemStatus($itemStatus): PrintRcvInputCacheKeyService
    {
        $this->itemStatus = $itemStatus;
        return $this;
    }

    public function getChallanNoCacheKey(): string
    {
        return $this->itemStatusOptions[$this->getItemStatus()] . '_challan_no_' . $this->getUserId();
    }

    public function getChallanBundlesCacheKey(): string
    {
        return $this->itemStatusOptions[$this->getItemStatus()] . '_challan_details_' . $this->getUserId();
    }

    public function insertCacheData($details): void
    {
        $cacheData = [];
        $cacheKey = $this->getChallanBundlesCacheKey();
        if (Cache::has($cacheKey)) {
            $cacheData = Cache::get($cacheKey);
        }
        $cacheDataKey = count($cacheData);
        $cacheData[$cacheDataKey] = $details;
        Cache::put($cacheKey, $cacheData, getScanDataCachingTime());
    }

    public function updateCacheData($bundle_card_id, $bundle_card_rejection_column, $printEmbrRejection)
    {
        $cacheKey = $this->getChallanBundlesCacheKey();
        if (Cache::has($cacheKey)) {
            $cacheData = Cache::get($cacheKey);
            $updatedCacheData = [];
            foreach ($cacheData as $key => $cacheData) {
                $print_rejection = $cacheData['print_rejection'];
                $embroidary_rejection = $cacheData['embroidary_rejection'];
                if ($bundle_card_id == $cacheData['id']) {
                    $print_rejection = $bundle_card_rejection_column == 'print_rejection' ? $printEmbrRejection : $cacheData['print_rejection'];
                    $embroidary_rejection = $bundle_card_rejection_column == 'embroidary_rejection' ? $printEmbrRejection : $cacheData['embroidary_rejection'];
                }
                $updatedCacheData[$key] = [
                    'id' => $cacheData['id'],
                    'quantity' => $cacheData['quantity'],
                    'total_rejection' => $cacheData['total_rejection'],
                    'print_rejection' => $print_rejection,
                    'embroidary_rejection' => $embroidary_rejection,
                    'buyer_name' => $cacheData['buyer_name'],
                    'style_name' => $cacheData['style_name'],
                    'po_no' => $cacheData['po_no'],
                    'color_name' => $cacheData['color_name'],
                    'lot_no' => $cacheData['lot_no'],
                    'cutting_no' => $cacheData['cutting_no'],
                    'size_name' => $cacheData['size_name'],
                    'bundle_no' => $cacheData['bundle_no'],
                ];
            }
            Cache::put($cacheKey, collect($updatedCacheData), getScanDataCachingTime());
        }
    }

    public function updateChallanBundlesCacheRejectionQty($bundleCardId, $rejectionQty): void
    {
        $cacheKey = $this->getChallanBundlesCacheKey();
        if (Cache::has($cacheKey)) {
            $cacheData = Cache::get($cacheKey);
            $updatedCacheData = [];
            foreach ($cacheData as $key => $cacheData) {
                $updatedCacheData[$key] = [
                    'id' => $cacheData['id'],
                    'quantity' => $cacheData['quantity'],
                    'total_rejection' => $cacheData['id'] == $bundleCardId ? $rejectionQty : $cacheData['total_rejection'],
                    'print_rejection' => $cacheData['print_rejection'],
                    'embroidary_rejection' => $cacheData['embroidary_rejection'],
                    'buyer_name' => $cacheData['buyer_name'],
                    'style_name' => $cacheData['style_name'],
                    'po_no' => $cacheData['po_no'],
                    'color_name' => $cacheData['color_name'],
                    'lot_no' => $cacheData['lot_no'],
                    'cutting_no' => $cacheData['cutting_no'],
                    'size_name' => $cacheData['size_name'],
                    'bundle_no' => $cacheData['bundle_no'],
                ];
            }
            Cache::put($cacheKey, collect($updatedCacheData), getScanDataCachingTime());
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
