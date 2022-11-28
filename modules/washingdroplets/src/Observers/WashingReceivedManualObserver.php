<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Observers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceivedManual;

class WashingReceivedManualObserver
{
    /**
     * Handle the washing received manual "created" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceivedManual  $washingReceivedManual
     * @return void
     */
    public function created(WashingReceivedManual $washingReceivedManual)
    {
        $receivedQty = $washingReceivedManual->received_qty;
        $rejectionQty = $washingReceivedManual->rejection_qty;

        $this->updateTotalProductionReportForWashingReceived($washingReceivedManual, $receivedQty, $rejectionQty);
        $this->updateDateAndColorWiseProductionForWashingReceived($washingReceivedManual, $receivedQty, $rejectionQty);
    }

    private function updateTotalProductionReportForWashingReceived($washingReceivedManual, $receivedQty, $rejectionQty) 
    {
        $totalReport = TotalProductionReport::where([
            'buyer_id' => $washingReceivedManual->buyer_id,
            'order_id' => $washingReceivedManual->order_id, 
            'purchase_order_id' => $washingReceivedManual->purchase_order_id,
            'color_id' => $washingReceivedManual->color_id
        ])->first();

        if (!$totalReport) {
            $totalReport = new TotalProductionReport();
            $totalReport->buyer_id = $washingReceivedManual->buyer_id;
            $totalReport->purchase_order_id = $washingReceivedManual->purchase_order_id;
            $totalReport->order_id = $washingReceivedManual->order_id;
            $totalReport->color_id = $washingReceivedManual->color_id;
        }        

        $totalReport->todays_washing_received += $receivedQty;
        $totalReport->total_washing_received += $receivedQty;
        $totalReport->todays_washing_rejection += $rejectionQty;
        $totalReport->total_washing_rejection += $rejectionQty;
        $totalReport->save();
    }

    private function updateDateAndColorWiseProductionForWashingReceived($washingReceivedManual, $receivedQty, $rejectionQty)
    {
        $washing_date = date('Y-m-d');
        $orderId = $washingReceivedManual->order_id;
        $colorId = $washingReceivedManual->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $washing_date,
            'order_id' => $orderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();

            $dateAndColorWiseProduction->buyer_id = $washingReceivedManual->buyer_id;
            $dateAndColorWiseProduction->purchase_order_id = $washingReceivedManual->purchase_order_id;
            $dateAndColorWiseProduction->order_id = $orderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $washing_date;
        }
        $dateAndColorWiseProduction->washing_received_qty += $receivedQty;
        $dateAndColorWiseProduction->washing_rejection_qty += $rejectionQty;
        $save = $dateAndColorWiseProduction->save();

        return $save;
    }

    /**
     * Handle the washing received manual "updated" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceivedManual  $washingReceivedManual
     * @return void
     */
    public function updated(WashingReceivedManual $washingReceivedManual)
    {
        $this->updateTotalProductionReport($washingReceivedManual);
        $this->updateDateAndColorWashingProduction($washingReceivedManual);
    }

    // date wise production update
    private function updateTotalProductionReport($washingReceivedManual)
    {
        $totalReport = TotalProductionReport::where([
            'buyer_id' => $washingReceivedManual->buyer_id,
            'order_id' => $washingReceivedManual->order_id,
            'purchase_order_id' => $washingReceivedManual->purchase_order_id,
            'color_id' => $washingReceivedManual->color_id
        ])->first();

        if (!$totalReport) {
            $totalReport = new TotalProductionReport();
            $totalReport->buyer_id = $washingReceivedManual->buyer_id;            
            $totalReport->order_id = $washingReceivedManual->order_id;
            $totalReport->purchase_order_id = $washingReceivedManual->purchase_order_id;
            $totalReport->color_id = $washingReceivedManual->color_id;
        }

        $original = $washingReceivedManual->getOriginal();
        $receivedQty = $washingReceivedManual->received_qty ?? 0;
        $rejectionQty = $washingReceivedManual->rejection_qty ?? 0;
        $washingDate = $washingReceivedManual->created_at->toDateString();

        if ($washingReceivedManual->isDirty('received_qty')) {            
            if ($washingDate == date('Y-m-d')) {
                $totalReport->todays_washing_received -= $original['received_qty'];
                $totalReport->todays_washing_received += $receivedQty;
            }
            $totalReport->total_washing_received -= $original['rejection_qty'];
            $totalReport->total_washing_received += $receivedQty;           
        }

        if ($washingReceivedManual->isDirty('rejection_qty')) {          
            if ($washingReceivedManual->updated_at->toDateString() == date('Y-m-d')) {
                $totalReport->todays_washing_rejection -= $original['rejection_qty'];
                $totalReport->todays_washing_rejection += $rejectionQty;
            }
            $totalReport->total_washing_rejection -= $original['rejection_qty'];
            $totalReport->total_washing_rejection += $rejectionQty;
        }        
        $totalReport->save();
    }

    // date and color wise production update
    private function updateDateAndColorWashingProduction($washingReceivedManual)
    {
        $purchaseOrderId = $washingReceivedManual->purchase_order_id;
        $colorId = $washingReceivedManual->color_id;
        $receivedQty = $washingReceivedManual->received_qty ?? 0;
        $rejectionQty = $washingReceivedManual->rejection_qty ?? 0;
        $washingDate = $washingReceivedManual->created_at->toDateString();
        $original = $washingReceivedManual->getOriginal(); 

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $washingDate,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();
        
        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();
            $dateAndColorWiseProduction->buyer_id = $washingReceivedManual->buyer_id;            
            $dateAndColorWiseProduction->order_id = $washingReceivedManual->order_id;
            $dateAndColorWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $washingDate;
        }
        if ($washingReceivedManual->isDirty('received_qty')) {            
            $dateAndColorWiseProduction->washing_received_qty -= $original['received_qty'];
            $dateAndColorWiseProduction->washing_received_qty += $receivedQty;                   
        }

        if ($washingReceivedManual->isDirty('rejection_qty')) {
            $dateAndColorWiseProduction->washing_rejection_qty -= $original['rejection_qty'];
            $dateAndColorWiseProduction->washing_rejection_qty += $rejectionQty;
        }
        $dateAndColorWiseProduction->save();

        return true;
    }

    /**
     * Handle the washing received manual "deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceivedManual  $washingReceivedManual
     * @return void
     */
    public function deleted(WashingReceivedManual $washingReceivedManual)
    {
        $this->deleteTotalProductionReport($washingReceivedManual);
        $this->deleteDateAndColorWashingProduction($washingReceivedManual);
    }

    // date wise production update
    private function deleteTotalProductionReport($washingReceivedManual)
    {
        $totalReport = TotalProductionReport::where([
            'buyer_id' => $washingReceivedManual->buyer_id,
            'order_id' => $washingReceivedManual->order_id, 
            'purchase_order_id' => $washingReceivedManual->purchase_order_id,
            'color_id' => $washingReceivedManual->color_id
        ])->first();        

        $receivedQty = $washingReceivedManual->received_qty;
        $rejectionQty = $washingReceivedManual->rejection_qty;
        $washingDate = $washingReceivedManual->created_at->toDateString();  
            
        if ($washingDate == date('Y-m-d')) {
            $totalReport->todays_washing_received -= $receivedQty;
        }

        $totalReport->total_washing_received -= $receivedQty;          
        if ($washingDate == date('Y-m-d')) {             
            $totalReport->todays_washing_rejection -= $rejectionQty;
        }

        $totalReport->total_washing_rejection -= $rejectionQty;        
        $totalReport->save();

        return true;
    }

    // date and color wise production update
    private function deleteDateAndColorWashingProduction($washingReceivedManual)
    {
        $purchaseOrderId = $washingReceivedManual->purchase_order_id;
        $colorId = $washingReceivedManual->color_id;
        $receivedQty = $washingReceivedManual->received_qty;
        $rejectionQty = $washingReceivedManual->rejection_qty;        

        $washingDate = $washingReceivedManual->created_at->toDateString();
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $washingDate,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();
        
        $dateAndColorWiseProduction->washing_received_qty -= $receivedQty; 
        $dateAndColorWiseProduction->washing_rejection_qty -= $rejectionQty;
        $dateAndColorWiseProduction->save();

        return true;
    }

    /**
     * Handle the washing received manual "restored" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceivedManual  $washingReceivedManual
     * @return void
     */
    public function restored(WashingReceivedManual $washingReceivedManual)
    {
        //
    }

    /**
     * Handle the washing received manual "force deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceivedManual  $washingReceivedManual
     * @return void
     */
    public function forceDeleted(WashingReceivedManual $washingReceivedManual)
    {
        //
    }
}
