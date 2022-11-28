<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Actions;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateWiseCuttingProductionReport;

class UpdateDateWiseCuttingProductionReportCutQtyAction
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

    private function formatData($bundleCardByTable)
    {
        $cutting_details_data = [];
        foreach ($bundleCardByTable->groupBy('purchase_order_id') as $bundleCardByPo) {
            foreach ($bundleCardByPo->groupBy('color_id') as $bundleCardByColor) {
                foreach ($bundleCardByColor->groupBy('size_id') as $bundleCardBySize) {
                    $cutting_details_data[] = [
                        'purchase_order_id' => $bundleCardByPo->first()->purchase_order_id,
                        'color_id' => $bundleCardByColor->first()->color_id,
                        'size_id' => $bundleCardBySize->first()->size_id,
                        'cutting_qty' => $bundleCardBySize->sum('quantity'),
                        'cutting_rejection' => $bundleCardBySize->sum('total_rejection')
                    ];
                }
            }
        }
        return $cutting_details_data;
    }

    private function updateCuttingDetailsData(&$cutting_details_existing_data, &$cutting_details_data)
    {
        foreach ($cutting_details_data as $key => $data) {
            $purchaseOrderId = $data['purchase_order_id'];
            $colorId = $data['color_id'];
            $sizeId = $data['size_id'];
            $cuttingQty = $data['cutting_qty'];
            $cuttingRejectionQty = $data['cutting_rejection'];

            $existingDataQuery = collect($cutting_details_existing_data)
                ->where('purchase_order_id', $purchaseOrderId)
                ->where('color_id', $colorId)
                ->where('size_id', $sizeId);

            if (!$existingDataQuery->count()) {
                $cutting_details_existing_data[] = $data;
            } else {
                $key = $existingDataQuery->keys()->first();
                $cutting_details_existing_data[$key] = [
                    'purchase_order_id' => $purchaseOrderId,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                    'cutting_qty' => ($cuttingQty + $cutting_details_existing_data[$key]['cutting_qty']) ?? 0,
                    'cutting_rejection' => ($cuttingRejectionQty + $cutting_details_existing_data[$key]['cutting_rejection']) ?? 0,
                ];
            }
        }
    }

    public function handle()
    {
        $bundleCardsData = $this->getBundleCards();

        foreach ($bundleCardsData->groupBy('cutting_floor_id') as $bundleCardByFloor) {
            foreach ($bundleCardByFloor->groupBy('cutting_table_id') as $bundleCardByTable) {
                $cutting_date = \operationDate();
                $cutting_floor_id = $bundleCardByFloor->first()->cutting_floor_id;
                $cutting_table_id = $bundleCardByTable->first()->cutting_table_id;
                $total_cutting = $bundleCardByTable->sum('quantity');
                $total_rejection = $bundleCardByTable->sum('total_rejection');

                $date_wise_cutting_production_report = DateWiseCuttingProductionReport::where([
                    'cutting_date' => $cutting_date,
                    'cutting_floor_id' => $cutting_floor_id,
                    'cutting_table_id' => $cutting_table_id
                ])->first();
                $cutting_details_data = $this->formatData($bundleCardByTable);

                if (!$date_wise_cutting_production_report) {
                    $date_wise_cutting_production_report = new DateWiseCuttingProductionReport();
                    $date_wise_cutting_production_report->cutting_date = $cutting_date;
                    $date_wise_cutting_production_report->cutting_floor_id = $cutting_floor_id;
                    $date_wise_cutting_production_report->cutting_table_id = $cutting_table_id;
                    $date_wise_cutting_production_report->cutting_details = $cutting_details_data;
                    $date_wise_cutting_production_report->total_cutting = $total_cutting;
                    $date_wise_cutting_production_report->total_rejection = $total_rejection;
                    $date_wise_cutting_production_report->save();
                } else {
                    $cutting_details_existing_data = $date_wise_cutting_production_report->cutting_details;

                    $this->updateCuttingDetailsData($cutting_details_existing_data, $cutting_details_data);

                    $date_wise_cutting_production_report->total_cutting += $total_cutting ?? 0;
                    $date_wise_cutting_production_report->total_rejection += $total_rejection ?? 0;
                    $date_wise_cutting_production_report->cutting_details = $cutting_details_existing_data;
                    $date_wise_cutting_production_report->save();
                }
            }
        }
    }
}
