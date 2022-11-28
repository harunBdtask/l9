<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Actions;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\ColorSizeSummaryReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;

class UpdateColorSizeSummaryReportCutQtyAction
{
    protected $bundleCards;

    public function setBundleCards($bundleCards)
    {
        $this->bundleCards = $bundleCards;
        return $this;
    }

    private function getBundleCards()
    {
        return $this->bundleCards;
    }

    public function handle()
    {
        $bundleCardsData = $this->getBundleCards();

        foreach ($bundleCardsData->groupBy('purchase_order_id') as $bundleCardByPo) {
            foreach ($bundleCardByPo->groupBy('color_id') as $bundleCardByColor) {
                foreach ($bundleCardByColor->groupBy('size_id') as $bundleCardBySize) {

                    $buyerId = $bundleCardByPo->first()->buyer_id;
                    $orderId = $bundleCardByPo->first()->order_id;
                    $purchaseOrderId = $bundleCardByPo->first()->purchase_order_id;
                    $colorId = $bundleCardByColor->first()->color_id;
                    $sizeId = $bundleCardBySize->first()->size_id;

                    $cuttingReport = ColorSizeSummaryReport::where([
                        'purchase_order_id' => $purchaseOrderId,
                        'color_id' => $colorId,
                        'size_id' => $sizeId
                    ])->first();

                    if (!$cuttingReport) {
                        $cuttingReport = new ColorSizeSummaryReport();

                        $cuttingReport->buyer_id = $buyerId;
                        $cuttingReport->order_id = $orderId;
                        $cuttingReport->purchase_order_id = $purchaseOrderId;
                        $cuttingReport->color_id = $colorId;
                        $cuttingReport->size_id = $sizeId;
                    }
                    $bundleQty = $bundleCardBySize->sum('quantity');
                    $cuttingReport->total_cutting += $bundleQty;
                    $cuttingReport->save();
                }
            }
        }
    }
}
