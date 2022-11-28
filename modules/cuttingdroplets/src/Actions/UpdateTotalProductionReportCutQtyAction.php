<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Actions;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;

class UpdateTotalProductionReportCutQtyAction
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

        foreach ($bundleCardsData->groupBy('order_id') as $bundleCardByOrder) {
            foreach ($bundleCardByOrder->groupBy('garments_item_id') as $bundleCardByItem) {
                foreach ($bundleCardByItem->groupBy('purchase_order_id') as $bundleCardByPo) {
                    foreach ($bundleCardByPo->groupBy('color_id') as $bundleCardByColor) {

                        $buyerId = $bundleCardByOrder->first()->buyer_id;
                        $orderId = $bundleCardByOrder->first()->order_id;
                        $garmentsItemId = $bundleCardByItem->first()->garments_item_id;
                        $purchaseOrderId = $bundleCardByPo->first()->purchase_order_id;
                        $colorId = $bundleCardByColor->first()->color_id;
                        
                        $cuttingReport = TotalProductionReport::where([
                            'order_id' => $orderId,
                            'garments_item_id' => $garmentsItemId,
                            'purchase_order_id' => $purchaseOrderId,
                            'color_id' => $colorId
                        ])->first();
            
                        if (!$cuttingReport) {
                            $cuttingReport = new TotalProductionReport();
            
                            $cuttingReport->buyer_id = $buyerId;
                            $cuttingReport->order_id = $orderId;
                            $cuttingReport->garments_item_id = $garmentsItemId;
                            $cuttingReport->purchase_order_id = $purchaseOrderId;
                            $cuttingReport->color_id = $colorId;
                        }
                        $bundleQty = $bundleCardByColor->sum('quantity');
                        $cuttingReport->total_cutting += $bundleQty;
                        $cuttingReport->todays_cutting += $bundleQty;
                        $cuttingReport->save();
                    }
                }
            }
        }
    }
}

