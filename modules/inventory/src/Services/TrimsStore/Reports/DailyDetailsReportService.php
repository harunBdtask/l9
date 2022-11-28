<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\Reports;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetail;

class DailyDetailsReportService
{
    private $fromDate, $toDate;

    public function __construct($request)
    {
        $this->fromDate = $request->get('from_date') ?? Carbon::now()->firstOfMonth();
        $this->toDate = $request->get('to_date') ?? Carbon::today();
    }

    public function generate()
    {
        return TrimsStoreReceiveDetail::query()
            ->whereDate('receive_date', '>=', $this->fromDate)
            ->whereDate('receive_date', '<=', $this->toDate)
            ->with([
                'trimsStoreReceive.booking.supplier:id,name',
                'trimsStoreReceive.buyer:id,name', 'color:id,name',
                'trimsInventoryDetail.trimsInventory'
            ])
            ->get()
            ->map(function ($item) {
                return [
                    'buyer' => $item->trimsStoreReceive->buyer->name ?? '',
                    'style' => $item->trimsStoreReceive->booking->style ?? '',
                    'po_no' => $item->trimsStoreReceive->booking->po_no ?? '',
                    'country' => null,
                    'color' => $item->color->name ?? '',
                    'size' => $item->size,
                    'item' => $item->item_description,
                    'booking_qty' => $item->booking_qty,
                    'unit_price' => $item->trimsStoreReceive->booking->rate ?? '',
                    'total_value' => $item->trimsStoreReceive->booking->amount ?? '',
                    'supplier_name' => $item->trimsStoreReceive->booking->supplier->name ?? '',
                    'pi_no' => $item->trimsStoreReceive->pi_no,
                    'pi_date' => $item->trimsStoreReceive->pi_receive_date,
                    'challan_no' => $item->trimsStoreReceive->challan_no,
                    'challan_date' => null,
                    'challan_order_qty' => null,
                    'challan_receive_qty' => $item->receive_qty,
                    'balance' => null,
                    'expected_in_house_date' => null,
                    'material_receive_no' => $item->trimsStoreReceive->unique_id,
                    'material_receive_date' => $item->receive_date,
                    'expected_inventory_date' => null,
                    'actual_inventory_date' => null,
                    'inventory_status' => null,
                    'qc_status' => null,
                    'qc_date' => null,
                    'lc_no' => $item->trimsInventoryDetail->trimsInventory->lc_no ?? '',
                    'lc_date' => $item->trimsInventoryDetail->trimsInventory->lc_receive_date ?? '',
                    'life_time' => Carbon::parse($item->receive_date)->diffInDays(now()),
                    'remarks' => $item->remarks,
                ];
            });
    }
}
