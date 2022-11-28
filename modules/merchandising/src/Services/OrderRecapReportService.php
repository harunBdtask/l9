<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services;


use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class OrderRecapReportService
{

    public static function reportData($factoryId, $buyerId, $seasonId, $fromDate, $toDate, $styleName)
    {
        return Order::query()
            ->with(['buyer:id,name',
                'factory:id,factory_name',
                'season:id,season_name',
                'purchaseOrders'
            ])
            ->when($factoryId, function ($query) use ($factoryId) {
                $query->where('factory_id', $factoryId);
            })
            ->when($buyerId, function ($query) use ($buyerId) {
                $query->where('buyer_id', $buyerId);
            })
            ->when($seasonId, function ($query) use ($seasonId) {
                $query->where('season_id', $seasonId);
            })
            ->when($styleName, function ($query) use ($styleName) {
                $query->where('style_name', $styleName);
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [date_format(date_create($fromDate), 'Y-m-d'), date_format(date_create($toDate), 'Y-m-d')]);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'season_name' => optional($item->season)->season_name ?? '',
                    'factory_name' => optional($item->factory)->factory_name ?? '',
                    'buyer_name' => optional($item->buyer)->name ?? '',
                    'brand' => null,
                    'style_name' => $item->style_name ?? '',
                    'description' => $item->item_details ? collect($item->item_details['details'])->pluck('item_name')->unique()->join(', ') : '',
                    'purchase_orders' => [
                        $item->purchaseOrders->map(function ($po) {
                            return [
                                'customer' => $po->customer ?? '',
                                'po_no' => $po->po_no ?? '',
                                'order_qty' => $po->po_quantity ?? 0,
                                'fty_fob' => null,
                                'po' => $po->avg_rate_pc_set ?? 0,
                                'fty_delivery_date' => $po->country_ship_date ?? '',
                                'po_delivery_date' => $po->ex_factory_date ?? '',
                                'fty_fob_value' => null,
                                'po_fob_value' => (double)$po->po_quantity * (double)$po->avg_rate_pc_set,
                                'remarks' => $po->remarks ?? ''
                            ];
                        })
                    ]
                ];
            });
    }

}
