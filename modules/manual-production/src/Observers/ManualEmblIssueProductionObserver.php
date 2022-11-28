<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Observers;

use SkylarkSoft\GoRMG\ManualProduction\Models\ManualEmblIssueProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateChallanWiseEmbrReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateChallanWisePrintReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWisePrintEmbrReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualTotalProductionReport;

class ManualEmblIssueProductionObserver
{
    /**
     * Handle the manualEmblIssueProduction "created" event.
     *
     * @param  ManualEmblIssueProduction $manualEmblIssueProduction
     * @return void
     */
    public function created(ManualEmblIssueProduction $manualEmblIssueProduction)
    {
        //
    }

    /**
     * Handle the manualEmblIssueProduction "saved" event.
     *
     * @param  ManualEmblIssueProduction $manualEmblIssueProduction
     * @return void
     */
    public function saved(ManualEmblIssueProduction $manualEmblIssueProduction)
    {
        $embl_name = $manualEmblIssueProduction->embl_name;

        $color_size_wise_production_qty = ManualEmblIssueProduction::query()->where([
            'embl_name' => $embl_name,
            'factory_id' => $manualEmblIssueProduction->factory_id,
            'subcontract_factory_id' => $manualEmblIssueProduction->subcontract_factory_id,
            'buyer_id' => $manualEmblIssueProduction->buyer_id,
            'order_id' => $manualEmblIssueProduction->order_id,
            'garments_item_id' => $manualEmblIssueProduction->garments_item_id,
            'purchase_order_id' => $manualEmblIssueProduction->purchase_order_id,
            'color_id' => $manualEmblIssueProduction->color_id,
            'size_id' => $manualEmblIssueProduction->size_id,
        ])->sum('production_qty');
        
        $date_color_size_wise_production_qty = ManualEmblIssueProduction::query()->where([
            'production_date' => $manualEmblIssueProduction->production_date,
            'embl_name' => $embl_name,
            'factory_id' => $manualEmblIssueProduction->factory_id,
            'subcontract_factory_id' => $manualEmblIssueProduction->subcontract_factory_id,
            'buyer_id' => $manualEmblIssueProduction->buyer_id,
            'order_id' => $manualEmblIssueProduction->order_id,
            'garments_item_id' => $manualEmblIssueProduction->garments_item_id,
            'purchase_order_id' => $manualEmblIssueProduction->purchase_order_id,
            'color_id' => $manualEmblIssueProduction->color_id,
            'size_id' => $manualEmblIssueProduction->size_id,
        ])->sum('production_qty');

        $color_wise_production_qty = ManualEmblIssueProduction::query()->where([
            'embl_name' => $embl_name,
            'factory_id' => $manualEmblIssueProduction->factory_id,
            'subcontract_factory_id' => $manualEmblIssueProduction->subcontract_factory_id,
            'buyer_id' => $manualEmblIssueProduction->buyer_id,
            'order_id' => $manualEmblIssueProduction->order_id,
            'garments_item_id' => $manualEmblIssueProduction->garments_item_id,
            'purchase_order_id' => $manualEmblIssueProduction->purchase_order_id,
            'color_id' => $manualEmblIssueProduction->color_id,
        ])->sum('production_qty');
        
        $color_challan_wise_production_qty = ManualEmblIssueProduction::query()->where([
            'embl_name' => $embl_name,
            'factory_id' => $manualEmblIssueProduction->factory_id,
            'subcontract_factory_id' => $manualEmblIssueProduction->subcontract_factory_id,
            'buyer_id' => $manualEmblIssueProduction->buyer_id,
            'order_id' => $manualEmblIssueProduction->order_id,
            'garments_item_id' => $manualEmblIssueProduction->garments_item_id,
            'purchase_order_id' => $manualEmblIssueProduction->purchase_order_id,
            'color_id' => $manualEmblIssueProduction->color_id,
            'challan_no' => $manualEmblIssueProduction->challan_no,
        ])->sum('production_qty');

        $this->updateManualTotalProductionReport($manualEmblIssueProduction, $embl_name, $color_size_wise_production_qty);
        $this->updateManualDailyProductionReport($manualEmblIssueProduction, $embl_name, $date_color_size_wise_production_qty);
        $this->updateManualDateWisePrintEmbrReport($manualEmblIssueProduction, $embl_name, $color_wise_production_qty);
        $this->updateManualDateChallanWiseEmbrReport($manualEmblIssueProduction, $embl_name, $color_challan_wise_production_qty);
        $this->updateManualDateChallanWisePrintReport($manualEmblIssueProduction, $embl_name, $color_challan_wise_production_qty);
    }

    private function updateManualTotalProductionReport($manualEmblIssueProduction, $embl_name, $production_qty)
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
                // print sent qty
                $report->print_sent_qty = $production_qty;
                $report->save();
                break;
            case 2:
                // embroidery sent qty
                $report->embroidery_sent_qty = $production_qty;
                $report->save();
                break;
            case 3:
                // wash sent qty
                $report->wash_sent_qty = $production_qty;
                $report->save();
                break;
            case 4:
                // special works sent qty
                $report->special_works_sent_qty = $production_qty;
                $report->save();
                break;
            case 5:
                // others sent qty
                $report->others_sent_qty = $production_qty;
                $report->save();
                break;
            default:
                break;
        }
    }
    
    private function updateManualDailyProductionReport($manualEmblIssueProduction, $embl_name, $production_qty)
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
                // print sent qty
                $report->print_sent_qty = $production_qty;
                $report->save();
                break;
            case 2:
                // embroidery sent qty
                $report->embroidery_sent_qty = $production_qty;
                $report->save();
                break;
            case 3:
                // wash sent qty
                $report->wash_sent_qty = $production_qty;
                $report->save();
                break;
            case 4:
                // special works sent qty
                $report->special_works_sent_qty = $production_qty;
                $report->save();
                break;
            case 5:
                // others sent qty
                $report->others_sent_qty = $production_qty;
                $report->save();
                break;
            default:
                break;
        }
    }

    private function updateManualDateWisePrintEmbrReport($manualEmblIssueProduction, $embl_name, $production_qty)
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
                // print sent qty
                $report->print_sent_qty = $production_qty;
                $report->save();
                break;
            case 2:
                // embroidery sent qty
                $report->embroidery_sent_qty = $production_qty;
                $report->save();
                break;
            default:
                break;
        }
    }

    private function updateManualDateChallanWiseEmbrReport($manualEmblIssueProduction, $embl_name, $production_qty)
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
        $report->embroidery_sent_qty = $production_qty;
        $report->save();
    }

    private function updateManualDateChallanWisePrintReport($manualEmblIssueProduction, $embl_name, $production_qty)
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
        $report->print_sent_qty = $production_qty;
        $report->save();
    }
    /**
     * Handle the manualEmblIssueProduction "updated" event.
     *
     * @param  ManualEmblIssueProduction $manualEmblIssueProduction
     * @return void
     */
    public function updated(ManualEmblIssueProduction $manualEmblIssueProduction)
    {
        //
    }

    /**
     * Handle the manualEmblIssueProduction "deleted" event.
     *
     * @param  ManualEmblIssueProduction $manualEmblIssueProduction
     * @return void
     */
    public function deleted(ManualEmblIssueProduction $manualEmblIssueProduction)
    {
        //
    }

    /**
     * Handle the manualEmblIssueProduction "restored" event.
     *
     * @param  ManualEmblIssueProduction $manualEmblIssueProduction
     * @return void
     */
    public function restored(ManualEmblIssueProduction $manualEmblIssueProduction)
    {
        //
    }

    /**
     * Handle the manualEmblIssueProduction "force deleted" event.
     *
     * @param  ManualEmblIssueProduction $manualEmblIssueProduction
     * @return void
     */
    public function forceDeleted(ManualEmblIssueProduction $manualEmblIssueProduction)
    {
        //
    }
}
