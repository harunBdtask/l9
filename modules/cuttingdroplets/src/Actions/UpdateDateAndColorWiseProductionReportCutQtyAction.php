<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Actions;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;

class UpdateDateAndColorWiseProductionReportCutQtyAction
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
                $productionDate = \operationDate();
                $buyerId = $bundleCardByPo->first()->buyer_id;
                $orderId = $bundleCardByPo->first()->order_id;
                $purchaseOrderId = $bundleCardByPo->first()->purchase_order_id;
                $colorId = $bundleCardByColor->first()->color_id;

                $cuttingReport = DateAndColorWiseProduction::where([
                    'production_date' => $productionDate,
                    'purchase_order_id' => $purchaseOrderId,
                    'color_id' => $colorId
                ])->first();

                if (!$cuttingReport) {
                    $cuttingReport = new DateAndColorWiseProduction();

                    $cuttingReport->production_date = $productionDate;
                    $cuttingReport->buyer_id = $buyerId;
                    $cuttingReport->order_id = $orderId;
                    $cuttingReport->purchase_order_id = $purchaseOrderId;
                    $cuttingReport->color_id = $colorId;
                }
                $bundleQty = $bundleCardByColor->sum('quantity');
                $cuttingReport->cutting_qty += $bundleQty;
                $cuttingReport->save();
            }
        }
    }
}
