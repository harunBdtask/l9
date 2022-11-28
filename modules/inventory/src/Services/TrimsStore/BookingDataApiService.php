<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\TrimsStore;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceive;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;

class BookingDataApiService
{
    public static function get($bookingNo): array
    {
        $booking = TrimsBooking::query()
            ->with(['supplier:id,name', 'details.budget.order.dealingMerchant', 'details.budget.order.season:id,season_name', 'deliveryTo:id,factory_name,factory_address'
            , 'details.order.purchaseOrders'
            ])
            ->where('unique_id', $bookingNo)
            ->first();

        $detailsCollection = collect($booking->getRelation('details'));

        $trimsStoreReceive = TrimsStoreReceive::query()
            ->withSum('details','receive_qty')
            ->where('booking_no',$bookingNo)
            ->get();

        $trimsStoreReceiveQtySum = $trimsStoreReceive->sum('details_sum_receive_qty');

        return [
            'supplier_name' => $booking->supplier->name ?? '',
            'address' => $booking->supplier->address_1 ?? '',
            'attention' => $booking->attention,
            'factory_id' => $booking->factory_id,
            'buyer_id' => $booking->buyer_id,
            'booking_id' => $booking->id,
            'booking_no' => $booking->unique_id,
            'booking_date' => $booking->booking_date,
            'delivery_date' => $booking->delivery_date,
            'dealing_merchant' => $detailsCollection->pluck('budget.order.dealingMerchant.screen_name')->unique()->values()->join(', '),
            'season_name' => $detailsCollection->pluck('budget.order.season.season_name')->unique()->values()->join(', '),
            'style_name' => $detailsCollection->first()->style_name ?? '',
            'po_no' => $detailsCollection->pluck('po_no')->unique()->values()->join(', '),
            'booking_qty' => $detailsCollection->sum('work_order_qty'),
            'amount' => $booking->amount,
            'delivery_to' => $booking->deliveryTo->factory_name ?? '',
            'delivery_address' => $booking->deliveryTo->factory_address ?? '',
            'short_access_qty' => format($detailsCollection->sum('work_order_qty') - $trimsStoreReceiveQtySum),
            'garments_shipment_date' => $detailsCollection->first()->order->purchaseOrders->sortBy('ex_factory_date')->first()['ex_factory_date'] ?? '',
        ];
    }
}
