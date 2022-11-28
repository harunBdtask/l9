<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Bookings;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;

class FabricBookingService
{
    public static function shortFabricData($request)
    {
        if ($request->get('goods_rcv_status') == 2) {
//            $fabricBookings = TrimsBookingService::beforeGoodReceive(ShortFabricBooking::query(), $request);
            $fabricBookings = ShortFabricBooking::query();
            return self::formatData($fabricBookings, $request);
        } elseif ($request->get('goods_rcv_status') == 1) {
//            $fabricBookings = TrimsBookingService::afterGoodReceive(ShortFabricBooking::query(), $request);
            $fabricBookings = ShortFabricBooking::query();
            return self::formatData($fabricBookings, $request);
        } else {
            return [];
        }
//        return self::formatData(ShortFabricBooking::query(), $request);
    }

    public static function mainFabricData($request)
    {
        if ($request->get('goods_rcv_status') == 2) {
//            $fabricBookings = TrimsBookingService::beforeGoodReceive(FabricBooking::query(), $request);
            $fabricBookings = FabricBooking::query();
            return self::formatData($fabricBookings, $request);
        } elseif ($request->get('goods_rcv_status') == 1) {
//            $fabricBookings = TrimsBookingService::afterGoodReceive(FabricBooking::query(), $request);
            $fabricBookings = FabricBooking::query();
            return self::formatData($fabricBookings, $request);
        } else {
            return [];
        }
    }

    public static function formatData($booking, $request)
    {
        $factory_id = $request->get('factory_id');
        $buyer_id = $request->get('buyer_id');
        $supplier_id = $request->get('supplier_id');
        $wo_no = $request->get('wo_no');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        return $booking->where('factory_id', $factory_id)
            ->when($buyer_id, function ($query) use ($buyer_id) {
                return $query->where('buyer_id', $buyer_id);
            })
            ->when($supplier_id, function ($query) use ($supplier_id) {
                return $query->where('supplier_id', $supplier_id);
            })
            ->when($wo_no, function ($query) use ($wo_no) {
                return $query->where('unique_id', $wo_no);
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query->whereBetween('booking_date', [$from_date, $to_date]);
            })
            ->has('detailsBreakdown')
            ->doesntHave('detailsBreakdown.piDetails')
            ->with('detailsBreakdown','detailsBreakdown.budget','detailsBreakdown.budget.fabricCosting', 'buyer:id,name', 'supplier:id,name','budget')
            ->get();
    }
}
