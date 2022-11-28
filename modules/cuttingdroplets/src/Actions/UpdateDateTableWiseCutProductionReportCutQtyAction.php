<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Actions;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;

class UpdateDateTableWiseCutProductionReportCutQtyAction
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

        foreach ($bundleCardsData->groupBy('cutting_table_id') as $bundleCardByTable) {
            foreach ($bundleCardByTable->groupBy('order_id') as $bundleCardByOrder) {
                foreach ($bundleCardByOrder->groupBy('garments_item_id') as $bundleCardByItem) {
                    foreach ($bundleCardByItem->groupBy('purchase_order_id') as $bundleCardByPo) {
                        foreach ($bundleCardByPo->groupBy('color_id') as $bundleCardByColor) {
                            foreach ($bundleCardByColor->groupBy('size_id') as $bundleCardBySize) {

                                $productionDate = \operationDate();
                                $cuttingFloorId = $bundleCardByTable->first()->cutting_floor_id;
                                $cuttingTableId = $bundleCardByTable->first()->cutting_table_id;
                                $buyerId = $bundleCardByOrder->first()->buyer_id;
                                $orderId = $bundleCardByOrder->first()->order_id;
                                $garmentsItemId = $bundleCardByItem->first()->garments_item_id;
                                $purchaseOrderId = $bundleCardByPo->first()->purchase_order_id;
                                $colorId = $bundleCardByColor->first()->color_id;
                                $sizeId = $bundleCardBySize->first()->size_id;

                                $cuttingReport = DateTableWiseCutProductionReport::where([
                                    'production_date' => $productionDate,
                                    'cutting_table_id' => $cuttingTableId,
                                    'order_id' => $orderId,
                                    'garments_item_id' => $garmentsItemId,
                                    'purchase_order_id' => $purchaseOrderId,
                                    'color_id' => $colorId,
                                    'size_id' => $sizeId,
                                ])->first();

                                if (!$cuttingReport) {
                                    $cuttingReport = new DateTableWiseCutProductionReport();

                                    $cuttingReport->production_date = $productionDate;
                                    $cuttingReport->cutting_floor_id = $cuttingFloorId;
                                    $cuttingReport->cutting_table_id = $cuttingTableId;
                                    $cuttingReport->buyer_id = $buyerId;
                                    $cuttingReport->order_id = $orderId;
                                    $cuttingReport->garments_item_id = $garmentsItemId;
                                    $cuttingReport->purchase_order_id = $purchaseOrderId;
                                    $cuttingReport->color_id = $colorId;
                                    $cuttingReport->size_id = $sizeId;
                                }
                                $bundleQty = $bundleCardBySize->sum('quantity');
                                $cuttingReport->cutting_qty += $bundleQty;
                                $cuttingReport->save();
                            }
                        }
                    }
                }
            }
        }
    }
}
