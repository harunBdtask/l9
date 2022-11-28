<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class BudgetService
{
    const UOM = [
        1 => 'Kg',
        2 => 'Yards',
        3 => 'Meter',
        4 => 'Pcs',
        5 => 'Cone(5k)',
    ];

    public static function uoms()
    {
        return collect(self::UOM)->map(function ($uom, $key) {
            return [
                'id' => $key,
                'text' => $uom,
            ];
        })->values();
    }

    public static function jobSearch(Request $request)
    {
        $factoryId = $request->get('factoryId') ?? null;
        $buyerId = $request->get('buyerId') ?? null;
        $jobNo = $request->get('jobNo') ?? null;
        $styleRef = $request->get('styleRef') ?? null;
        $internalRef = $request->get('internalRef') ?? null;
        $fileNo = $request->get('fileNo') ?? null;
        $fromDate = $request->get('fromDate') ?? null;
        $toDate = $request->get('toDate') ?? null;
        $poNo = $request->get('poNo') ?? null;

        return Order::with([
            'purchaseOrders' => function ($query) use ($poNo) {
                $query->when($poNo, function ($query) use ($poNo) {
                    $query->where('po_no', $poNo);
                });
            },
            'factory',
            'buyer',
            'currency',
            'season',
            'productCategory',
            'priceQuotation',
            'priceQuotation.costingDetails',
            'productDepartment',
            'priceQuotation.incoterm',
            'priceQuotation.buyingAgent',
            'buyingAgent',
            'purchaseOrders.poDetails',
        ])
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->when($jobNo, function ($query) use ($jobNo) {
                $query->where('job_no', $jobNo);
            })
            ->when($styleRef, function ($query) use ($styleRef) {
                $query->where('style_name', $styleRef);
            })
            ->when($internalRef, function ($query) use ($internalRef) {
                $query->whereHas('purchaseOrders', function ($query) use ($internalRef) {
                    $query->where('internal_ref_no', $internalRef);
                });
            })
            ->when($fileNo, function ($query) use ($fileNo) {
                $query->whereHas('purchaseOrders', function ($query) use ($fileNo) {
                    $query->where('comm_file_no', $fileNo);
                });
            })
            ->when($poNo, function ($query) use ($poNo) {
                $query->whereHas('purchaseOrders', function ($query) use ($poNo) {
                    $query->where('po_no', $poNo);
                });
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereHas('purchaseOrders', function ($query) use ($fromDate, $toDate) {
                    $query->whereBetween('po_receive_date', [$fromDate, $toDate]);
                });
            })
            ->orderBy('id', 'desc')->get();
    }
}
