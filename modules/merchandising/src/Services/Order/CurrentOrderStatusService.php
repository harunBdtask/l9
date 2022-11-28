<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Order;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class CurrentOrderStatusService
{
    public static function getReportData($request)
    {
        $orders = Order::query()
            ->where('factory_id', $request->get('company_id'))
            ->when($request->get('buyer_id'), function ($query) use ($request) {
                return $query->where('buyer_id', $request->get('buyer_id'));
            })
            ->when($request->get('style_name'), function ($query) use ($request) {
                return $query->where('style_name', $request->get('style_name'));
            })->when($request->get('unique_id'), function ($query) use ($request) {
                return $query->where('job_no', $request->get('unique_id'));
            })->when($request->get('from_date') && $request->get('to_date'), function ($query) use ($request) {
                return $query->whereDate('created_at', '>=', $request->get('from_date'))
                    ->whereDate('created_at', '<=', $request->get('to_date'));
            })
            ->with('buyer', 'dealingMerchant', 'orderPriceQuotation', 'tna', 'budgetData', 'purchaseOrders',
                'budgetData.trimsBookings', 'budgetData.fabricBookings','budgetData.emblBookings', 'factory')
            ->get();
        return self::formatReportData($orders);
    }

    public static function formatReportData($orderData)
    {
        return collect($orderData)->map(function ($order){
            return [
               // 'factory_name' => $order->factory->factory_name,
                'buyer_name' => $order->buyer->name,
                'style_name' => $order->style_name,
                'unique_id' => $order->job_no,
                'shipment_date' => $order->purchaseOrders->last()['ex_factory_date'] ?? null,
                'dealing_merchant' => $order->dealingMerchant->screen_name,
                'price_quotation' => $order->orderPriceQuotation ? 'Yes' : 'No',
                'price_quotation_created_at' => $order->orderPriceQuotation ? $order->orderPriceQuotation->created_at : '',
                'order_entry' => 'Yes',
                'order_created_at' => $order->created_at,
                'tna' => count($order->tna) > 0 ? 'Yes' : 'No',
                'budget' => ($order->budgetData->id)  ? 'Yes' : 'No',
                'budget_created_at' => $order->budgetData ? $order->budgetData->created_at : '',
//                'budgets' => count($order->budgetData->trimsBookings),
                'trims_booking' => isset($order->budgetData->id) ? ( count($order->budgetData->trimsBookings) > 0  ? 'Yes' : 'No') : 'No',
                'fabric_booking' => isset($order->budgetData->id) ? ( count($order->budgetData->fabricBookingDetails) > 0  ? 'Yes' : 'No') : 'No',
                'embl_booking' => isset($order->budgetData->id) ? ( count($order->budgetData->emblBookings) > 0 ? 'Yes' : 'No') : 'No',
            ];
        });
    }

}
