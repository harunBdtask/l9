<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Observers;

use SkylarkSoft\GoRMG\ManualProduction\Models\ManualEmblReceiveProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateChallanWiseEmbrReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateChallanWisePrintReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWisePrintEmbrReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualTotalProductionReport;

class ManualEmblReceiveProductionObserver
{
    /**
     * Handle the manualEmblReceiveProduction "created" event.
     *
     * @param  ManualEmblReceiveProduction $manualEmblReceiveProduction
     * @return void
     */
    public function created(ManualEmblReceiveProduction $manualEmblReceiveProduction)
    {
        //
    }

    /**
     * Handle the manualEmblReceiveProduction "saved" event.
     *
     * @param  ManualEmblReceiveProduction $manualEmblReceiveProduction
     * @return void
     */
    public function saved(ManualEmblReceiveProduction $manualEmblReceiveProduction)
    {
        $embl_name = $manualEmblReceiveProduction->embl_name;

        $color_size_wise_production_query = ManualEmblReceiveProduction::query()->where([
            'embl_name' => $embl_name,
            'factory_id' => $manualEmblReceiveProduction->factory_id,
            'subcontract_factory_id' => $manualEmblReceiveProduction->subcontract_factory_id,
            'buyer_id' => $manualEmblReceiveProduction->buyer_id,
            'order_id' => $manualEmblReceiveProduction->order_id,
            'garments_item_id' => $manualEmblReceiveProduction->garments_item_id,
            'purchase_order_id' => $manualEmblReceiveProduction->purchase_order_id,
            'color_id' => $manualEmblReceiveProduction->color_id,
            'size_id' => $manualEmblReceiveProduction->size_id,
        ]);
        
        $color_size_wise_production_qty = $color_size_wise_production_query->sum('production_qty');
        $color_size_wise_rejection_qty = $color_size_wise_production_query->sum('rejection_qty');

        $date_color_size_wise_production_query = ManualEmblReceiveProduction::query()->where([
            'embl_name' => $embl_name,
            'production_date' => $manualEmblReceiveProduction->production_date,
            'factory_id' => $manualEmblReceiveProduction->factory_id,
            'subcontract_factory_id' => $manualEmblReceiveProduction->subcontract_factory_id,
            'buyer_id' => $manualEmblReceiveProduction->buyer_id,
            'order_id' => $manualEmblReceiveProduction->order_id,
            'garments_item_id' => $manualEmblReceiveProduction->garments_item_id,
            'purchase_order_id' => $manualEmblReceiveProduction->purchase_order_id,
            'color_id' => $manualEmblReceiveProduction->color_id,
            'size_id' => $manualEmblReceiveProduction->size_id,
        ]);
        
        $date_color_size_wise_production_qty = $date_color_size_wise_production_query->sum('production_qty');
        $date_color_size_wise_rejection_qty = $date_color_size_wise_production_query->sum('rejection_qty');

        $color_wise_production_query = ManualEmblReceiveProduction::query()->where([
            'embl_name' => $embl_name,
            'factory_id' => $manualEmblReceiveProduction->factory_id,
            'subcontract_factory_id' => $manualEmblReceiveProduction->subcontract_factory_id,
            'buyer_id' => $manualEmblReceiveProduction->buyer_id,
            'order_id' => $manualEmblReceiveProduction->order_id,
            'garments_item_id' => $manualEmblReceiveProduction->garments_item_id,
            'purchase_order_id' => $manualEmblReceiveProduction->purchase_order_id,
            'color_id' => $manualEmblReceiveProduction->color_id,
        ]);
        
        $color_wise_production_qty = $color_wise_production_query->sum('production_qty');
        $color_wise_rejection_qty = $color_wise_production_query->sum('rejection_qty');
        
        $color_challan_wise_production_query = ManualEmblReceiveProduction::query()->where([
            'embl_name' => $embl_name,
            'factory_id' => $manualEmblReceiveProduction->factory_id,
            'subcontract_factory_id' => $manualEmblReceiveProduction->subcontract_factory_id,
            'buyer_id' => $manualEmblReceiveProduction->buyer_id,
            'order_id' => $manualEmblReceiveProduction->order_id,
            'garments_item_id' => $manualEmblReceiveProduction->garments_item_id,
            'purchase_order_id' => $manualEmblReceiveProduction->purchase_order_id,
            'color_id' => $manualEmblReceiveProduction->color_id,
            'challan_no' => $manualEmblReceiveProduction->challan_no,
        ]);

        $color_challan_wise_production_qty = $color_challan_wise_production_query->sum('production_qty');
        $color_challan_wise_rejection_qty = $color_challan_wise_production_query->sum('rejection_qty');

        $this->updateManualTotalProductionReport($manualEmblReceiveProduction, $embl_name, $color_size_wise_production_qty, $color_size_wise_rejection_qty);
        $this->updateManualDailyProductionReport($manualEmblReceiveProduction, $embl_name, $date_color_size_wise_production_qty, $date_color_size_wise_rejection_qty);
        $this->updateManualDateWisePrintEmbrReport($manualEmblReceiveProduction, $embl_name, $color_wise_production_qty, $color_wise_rejection_qty);
        $this->updateManualDateChallanWiseEmbrReport($manualEmblReceiveProduction, $embl_name, $color_challan_wise_production_qty, $color_challan_wise_rejection_qty);
        $this->updateManualDateChallanWisePrintReport($manualEmblReceiveProduction, $embl_name, $color_challan_wise_production_qty, $color_challan_wise_rejection_qty);
    }

    private function updateManualTotalProductionReport($manualEmblIssueProduction, $embl_name, $production_qty, $rejection_qty)
    {
        $report = ManualTotalProductionReport::query()
            ->where([
                'factory_id' => $manualEmblIssueProduction->factory_id,
                'subcontract_factory_id' => $manualEmblIssueProduction->subcontract_factory_id,
                'buyer_id' => $manualEmblIssueProduction->buyer_id,
                'order_id' => $manualEmblIssueProduction->order_id,
                'garments_item_id' => $manualEmblIssueProduction->garments_item_id,
                'purchase_order_id' => $manualEmblIssueProduction->purchase_order_id,
                'color_id' => $manualEmblIssueProduction->color_id,
                'size_id' => $manualEmblIssueProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualTotalProductionReport();
            $report->factory_id = $manualEmblIssueProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualEmblIssueProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualEmblIssueProduction->buyer_id ?? null;
            $report->order_id = $manualEmblIssueProduction->order_id ?? null;
            $report->garments_item_id = $manualEmblIssueProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualEmblIssueProduction->purchase_order_id ?? null;
            $report->color_id = $manualEmblIssueProduction->color_id ?? null;
            $report->size_id = $manualEmblIssueProduction->size_id ?? null;
        }
        switch ($embl_name) {
            case 1:
                // print receive qty
                $report->print_receive_qty = $production_qty;
                $report->print_rejection_qty = $rejection_qty;
                $report->save();
                break;
            case 2:
                // embroidery receive qty
                $report->embroidery_receive_qty = $production_qty;
                $report->embroidery_rejection_qty = $rejection_qty;
                $report->save();
                break;
            case 3:
                // wash receive qty
                $report->wash_receive_qty = $production_qty;
                $report->wash_rejection_qty = $rejection_qty;
                $report->save();
                break;
            case 4:
                // special works receive qty
                $report->special_works_receive_qty = $production_qty;
                $report->special_works_rejection_qty = $rejection_qty;
                $report->save();
                break;
            case 5:
                // others receive qty
                $report->others_receive_qty = $production_qty;
                $report->others_rejection_qty = $rejection_qty;
                $report->save();
                break;
            default:
                break;
        }
    }


    private function updateManualDailyProductionReport($manualEmblIssueProduction, $embl_name, $production_qty, $rejection_qty)
    {
        $report = ManualDailyProductionReport::query()
            ->where([
                'production_date' => $manualEmblIssueProduction->production_date,
                'factory_id' => $manualEmblIssueProduction->factory_id,
                'subcontract_factory_id' => $manualEmblIssueProduction->subcontract_factory_id,
                'buyer_id' => $manualEmblIssueProduction->buyer_id,
                'order_id' => $manualEmblIssueProduction->order_id,
                'garments_item_id' => $manualEmblIssueProduction->garments_item_id,
                'purchase_order_id' => $manualEmblIssueProduction->purchase_order_id,
                'color_id' => $manualEmblIssueProduction->color_id,
                'size_id' => $manualEmblIssueProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualDailyProductionReport();
            $report->production_date = $manualEmblIssueProduction->production_date ?? null;
            $report->factory_id = $manualEmblIssueProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualEmblIssueProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualEmblIssueProduction->buyer_id ?? null;
            $report->order_id = $manualEmblIssueProduction->order_id ?? null;
            $report->garments_item_id = $manualEmblIssueProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualEmblIssueProduction->purchase_order_id ?? null;
            $report->color_id = $manualEmblIssueProduction->color_id ?? null;
            $report->size_id = $manualEmblIssueProduction->size_id ?? null;
        }
        switch ($embl_name) {
            case 1:
                // print receive qty
                $report->print_receive_qty = $production_qty;
                $report->print_rejection_qty = $rejection_qty;
                $report->save();
                break;
            case 2:
                // embroidery receive qty
                $report->embroidery_receive_qty = $production_qty;
                $report->embroidery_rejection_qty = $rejection_qty;
                $report->save();
                break;
            case 3:
                // wash receive qty
                $report->wash_receive_qty = $production_qty;
                $report->wash_rejection_qty = $rejection_qty;
                $report->save();
                break;
            case 4:
                // special works receive qty
                $report->special_works_receive_qty = $production_qty;
                $report->special_works_rejection_qty = $rejection_qty;
                $report->save();
                break;
            case 5:
                // others receive qty
                $report->others_receive_qty = $production_qty;
                $report->others_rejection_qty = $rejection_qty;
                $report->save();
                break;
            default:
                break;
        }
    }

    private function updateManualDateWisePrintEmbrReport($manualEmblIssueProduction, $embl_name, $production_qty, $rejection_qty)
    {
        $report = ManualDateWisePrintEmbrReport::query()
            ->where([
                'production_date' => $manualEmblIssueProduction->production_date,
                'factory_id' => $manualEmblIssueProduction->factory_id,
                'subcontract_factory_id' => $manualEmblIssueProduction->subcontract_factory_id,
                'buyer_id' => $manualEmblIssueProduction->buyer_id,
                'order_id' => $manualEmblIssueProduction->order_id,
                'garments_item_id' => $manualEmblIssueProduction->garments_item_id,
                'purchase_order_id' => $manualEmblIssueProduction->purchase_order_id,
                'color_id' => $manualEmblIssueProduction->color_id,
            ])->first();
        if (!$report) {
            $report = new ManualDateWisePrintEmbrReport();
            $report->production_date = $manualEmblIssueProduction->production_date ?? null;
            $report->factory_id = $manualEmblIssueProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualEmblIssueProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualEmblIssueProduction->buyer_id ?? null;
            $report->order_id = $manualEmblIssueProduction->order_id ?? null;
            $report->garments_item_id = $manualEmblIssueProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualEmblIssueProduction->purchase_order_id ?? null;
            $report->color_id = $manualEmblIssueProduction->color_id ?? null;
        }
        switch ($embl_name) {
            case 1:
                // print receive qty
                $report->print_receive_qty = $production_qty;
                $report->print_rejection_qty = $rejection_qty;
                $report->save();
                break;
            case 2:
                // embroidery receive qty
                $report->embroidery_receive_qty = $production_qty;
                $report->embroidery_rejection_qty = $rejection_qty;
                $report->save();
                break;
            default:
                break;
        }
    }

    private function updateManualDateChallanWiseEmbrReport($manualEmblIssueProduction, $embl_name, $production_qty, $rejection_qty)
    {
        if ($embl_name != 2) {
            return false;
        }
        $report = ManualDateChallanWiseEmbrReport::query()
            ->where([
                'production_date' => $manualEmblIssueProduction->production_date,
                'factory_id' => $manualEmblIssueProduction->factory_id,
                'subcontract_factory_id' => $manualEmblIssueProduction->subcontract_factory_id,
                'buyer_id' => $manualEmblIssueProduction->buyer_id,
                'order_id' => $manualEmblIssueProduction->order_id,
                'garments_item_id' => $manualEmblIssueProduction->garments_item_id,
                'purchase_order_id' => $manualEmblIssueProduction->purchase_order_id,
                'color_id' => $manualEmblIssueProduction->color_id,
                'challan_no' => $manualEmblIssueProduction->challan_no,
            ])->first();
        if (!$report) {
            $report = new ManualDateChallanWiseEmbrReport();
            $report->production_date = $manualEmblIssueProduction->production_date ?? null;
            $report->factory_id = $manualEmblIssueProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualEmblIssueProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualEmblIssueProduction->buyer_id ?? null;
            $report->order_id = $manualEmblIssueProduction->order_id ?? null;
            $report->garments_item_id = $manualEmblIssueProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualEmblIssueProduction->purchase_order_id ?? null;
            $report->color_id = $manualEmblIssueProduction->color_id ?? null;
            $report->challan_no = $manualEmblIssueProduction->challan_no ?? null;
        }
        $report->embroidery_receive_qty = $production_qty;
        $report->embroidery_rejection_qty = $rejection_qty;
        $report->save();
    }

    private function updateManualDateChallanWisePrintReport($manualEmblIssueProduction, $embl_name, $production_qty, $rejection_qty)
    {
        if ($embl_name != 1) {
            return false;
        }
        $report = ManualDateChallanWisePrintReport::query()
            ->where([
                'production_date' => $manualEmblIssueProduction->production_date,
                'factory_id' => $manualEmblIssueProduction->factory_id,
                'subcontract_factory_id' => $manualEmblIssueProduction->subcontract_factory_id,
                'buyer_id' => $manualEmblIssueProduction->buyer_id,
                'order_id' => $manualEmblIssueProduction->order_id,
                'garments_item_id' => $manualEmblIssueProduction->garments_item_id,
                'purchase_order_id' => $manualEmblIssueProduction->purchase_order_id,
                'color_id' => $manualEmblIssueProduction->color_id,
                'challan_no' => $manualEmblIssueProduction->challan_no,
            ])->first();
        if (!$report) {
            $report = new ManualDateChallanWisePrintReport();
            $report->production_date = $manualEmblIssueProduction->production_date ?? null;
            $report->factory_id = $manualEmblIssueProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualEmblIssueProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualEmblIssueProduction->buyer_id ?? null;
            $report->order_id = $manualEmblIssueProduction->order_id ?? null;
            $report->garments_item_id = $manualEmblIssueProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualEmblIssueProduction->purchase_order_id ?? null;
            $report->color_id = $manualEmblIssueProduction->color_id ?? null;
            $report->challan_no = $manualEmblIssueProduction->challan_no ?? null;
        }
        $report->print_receive_qty = $production_qty;
        $report->print_rejection_qty = $rejection_qty;
        $report->save();
    }

    /**
     * Handle the manualEmblReceiveProduction "updated" event.
     *
     * @param  ManualEmblReceiveProduction $manualEmblReceiveProduction
     * @return void
     */
    public function updated(ManualEmblReceiveProduction $manualEmblReceiveProduction)
    {
        //
    }

    /**
     * Handle the manualEmblReceiveProduction "deleted" event.
     *
     * @param  ManualEmblReceiveProduction $manualEmblReceiveProduction
     * @return void
     */
    public function deleted(ManualEmblReceiveProduction $manualEmblReceiveProduction)
    {
        //
    }

    /**
     * Handle the manualEmblReceiveProduction "restored" event.
     *
     * @param  ManualEmblReceiveProduction $manualEmblReceiveProduction
     * @return void
     */
    public function restored(ManualEmblReceiveProduction $manualEmblReceiveProduction)
    {
        //
    }

    /**
     * Handle the manualEmblReceiveProduction "force deleted" event.
     *
     * @param  ManualEmblReceiveProduction $manualEmblReceiveProduction
     * @return void
     */
    public function forceDeleted(ManualEmblReceiveProduction $manualEmblReceiveProduction)
    {
        //
    }
}
