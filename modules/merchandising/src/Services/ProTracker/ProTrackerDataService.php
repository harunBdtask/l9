<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\ProTracker;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class ProTrackerDataService
{
    public function getDesireData($poId)
    {
        return PurchaseOrder::query()
            ->with('poDetails')
            ->findOrFail($poId);
    }

    public function designDesireData($data): array
    {
        return collect($data)->map(function ($poDetails) {
            return collect($poDetails['colors'])->map(function ($color) use ($poDetails) {
                return collect($poDetails['sizes'])->map(function ($size) use ($poDetails, $color) {
                    return [
                        'factory_id' => $poDetails['factory_id'],
                        'buyer_id' => $poDetails['buyer_id'],
                        'order_id' => $poDetails['order_id'],
                        'purchase_order_id' => $poDetails['purchase_order_id'],
                        'garments_item_id' => $poDetails['garments_item_id'],
                        'color_id' => (int) $color,
                        'size_id' => (int) $size,
                        'rate' => $this->breakdownData($poDetails['quantity_matrix'], $color, $size, PurchaseOrder::RATE),
                        'excess_cut_percent' => $this->breakdownData($poDetails['quantity_matrix'], $color, $size, PurchaseOrder::EX_CUT),
                        'quantity' => $this->breakdownData($poDetails['quantity_matrix'], $color, $size, PurchaseOrder::QTY),
                        'article_no' => $this->breakdownData($poDetails['quantity_matrix'], $color, $size, PurchaseOrder::ARTICLE_NO),
                        'created_by' => $poDetails['created_by'],
                        'updated_by' => $poDetails['updated_by'],
                    ];
                });
            });
        })->flatten(2)->toArray();
    }

    private function breakdownData($data, $colorId, $sizeId, $particular)
    {
        $target = collect($data)->where('color_id', $colorId)
            ->where('size_id', $sizeId)
            ->where('particular', $particular)
            ->first();

        return $target ? $target['value'] : null;
    }

    public function deleteDesireData($poId)
    {
        PurchaseOrderDetail::query()->where('purchase_order_id', $poId)->delete();
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function processAllPurchaseOrders(): string
    {
        try {
            DB::beginTransaction();
            PurchaseOrderDetail::query()->truncate();
            PurchaseOrder::query()
                ->with('poDetails')
                ->chunk(50, function ($purchaseOrders) {
                    foreach ($purchaseOrders as $purchaseOrder) {
                        $purchaseOrderDetails = collect($this->designDesireData($purchaseOrder['poDetails']))->whereNotNull('quantity')->toArray();
                        PurchaseOrderDetail::query()->insert($purchaseOrderDetails);
                    }
                });
            DB::commit();

            return 'Successfully Inserted';
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
