<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\WipReport;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class WipReportService
{
    public static function formatWipData($order)
    {
        $data = [];
        $order = $order->load('priceQuotation.costingDetails', 'purchaseOrders.poDetails', 'assignFactory:id,name');

        $data['order_id'] = $order->id ?? null;
        $data['assign_factory'] = $order->assignFactory->name ?? null;
        $data['assign_factory_id'] = $order->assigning_factory_id ?? null;
        $data['style'] = $order->style_name ?? null;
        $data['po_issued_to_fty'] = $order->purchaseOrders->pluck('country_ship_date')->first() ?? null;
        $data['order_qty'] = $order->purchaseOrders->sum('po_quantity') ?? 0;
        $data['image'] = $order->images ?? null;
        $data['garments_item'] = ($order->item_details && $order->item_details['details']) ? collect($order->item_details['details'])->pluck('item_name')->implode(', ') : null;
        $data['color_breakdown_as_per_po'] = $order->purchaseOrders ? self::formatColorBreakdownAsPerPo($order->purchaseOrders) : [];
        $data['customer_wise_po'] = $order->purchaseOrders ? self::formatCustomerWisePoDetails($order->purchaseOrders) : [];
        $data['fabric_booking_data'] = $order->priceQuotation ? self::formatFabricDetails($order->priceQuotation) : [];
        $data['trims_booking_data'] = $order->priceQuotation ? self::formatTrimsDetails($order->priceQuotation) : [];

        return $data;

    }

    private static function formatColorBreakdownAsPerPo($purchaseOrder)
    {
        $poDetails = $purchaseOrder->pluck('poDetails')->flatten();

        $poQtyWiseData = collect($poDetails)->pluck('quantity_matrix')->flatten(1)->where('particular', PurchaseOrder::QTY)->values();
        return $poQtyWiseData ? collect($poQtyWiseData)->groupBy('color')->map(function ($colorWiseDetails, $key) {
            return [
                'color' => $key,
                'color_id' => collect($colorWiseDetails)->pluck('color_id')->unique()->first() ?? null,
                'qty' => collect($colorWiseDetails)->sum('value'),
                'asi_master_lc_due_on' => null,
            ];
        })->values() : [];
    }

    private static function formatCustomerWisePoDetails($purchaseOrder)
    {
        return $purchaseOrder->map(function ($item) {
            return [
                'customer_name' => $item['customer'] ?? null,
                'po_no' => $item['po_no'] ?? null,
                'order_qty' => $item['po_quantity'] ?? 0,
                'group' => null,
                'brand' => null,
                'fty_del' => null,
            ];
        });
    }

    private static function formatFabricDetails($costingDetails)
    {
        $fabricCosting = collect($costingDetails->costingDetails)->where('type', 'fabric_costing')->values();
        $fabricDetails = $fabricCosting ? $fabricCosting->pluck('details.details.fabricForm')->flatten(1) : null;

        return $fabricDetails ? $fabricDetails->map(function ($item) {
            return [
                'body_part_id' => $item['body_part_id'] ?? null,
                'body_part_value' => $item['body_part_value'] ?? null,
                'fabric_description' => $item['fabric_composition_value'] ?? null,
                'fabric_composition_id' => $item['fabric_composition_id'] ?? null,
                'gsm' => $item['gsm'] ?? null,
                'dia_type_value' => $item['dia_type_value'] ?? null,
                'dia_type' => $item['dia_type'] ?? null,
                'supplier_id' => $item['supplier_id'] ?? null,
                'supplier_value' => $item['supplier_value'] ?? null,
                'details' => []
            ];
        }) : [];
    }

    private static function formatTrimsDetails($costingDetails)
    {
        $trimsCosting = collect($costingDetails->costingDetails)->where('type', 'trims_costing')->values();
        $trimsDetails = $trimsCosting ? $trimsCosting->pluck('details.details')->flatten(1) : null;

        return $trimsDetails ? collect($trimsDetails)->map(function ($item) {
            return [
                'item_id' => $item['group_id'] ?? null,
                'item_name' => $item['group_name'] ?? null,
                'uom_id' => $item['cons_uom_id'] ?? null,
                'uom_name' => $item['cons_uom_value'] ?? null,
                'description' => $item['item_description'] ?? null,
                'supplier_id' => $item['nominated_supplier_id'] ?? null,
                'supplier_value' => $item['nominated_supplier_value'] ?? null,
                'details' => [],
                'thread_color' => null,
                'body_color' => 'Body Color',

            ];
        }) : [];

    }


    public static function showWipData($wipReport)
    {
        $wipReport->load('factory:id,name','order.priceQuotation');
        $wipReport['assign_factory'] = $wipReport->factory->name ?? ' ';
        $wipReport['fabric_booking_data'] = $wipReport->order->priceQuotation ? self::formatFabricDetails($wipReport->order->priceQuotation) : [];
        $wipReport['trims_booking_data'] = $wipReport->order->priceQuotation ? self::formatTrimsDetails($wipReport->order->priceQuotation) : [];
        return collect($wipReport)->except('factory','order');
    }

}
