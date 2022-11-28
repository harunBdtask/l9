<?php


namespace SkylarkSoft\GoRMG\Commercial\Services\Bookings;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;

class TrimsBookingService
{
    public static function mainTrimsData($request)
    {
        //        return self::formatData(TrimsBooking::query(), $request);
//        After Goods Rcv Status 1
//        Before Goods Receive 2
        if ($request->get('goods_rcv_status') == 2) {
//            $trimsBookings = self::beforeGoodReceive(TrimsBooking::query(), $request);
            $trimsBookings = TrimsBooking::query();

            return self::formatData($trimsBookings, $request);
        } elseif ($request->get('goods_rcv_status') == 1) {
//            $trimsBookings = self::afterGoodReceive(TrimsBooking::query(), $request);
            $trimsBookings = TrimsBooking::query();
            return self::formatData($trimsBookings, $request);
        } else {
            return [];
        }
    }

    public static function shortTrimsData($request)
    {
        if ($request->get('goods_rcv_status') == 2) {
//            $trimsBookings = self::beforeGoodReceive(ShortTrimsBooking::query(), $request);
            $trimsBookings = ShortTrimsBooking::query();
            return self::formatData($trimsBookings, $request);
        } elseif ($request->get('goods_rcv_status') == 1) {
//            $trimsBookings = self::afterGoodReceive(ShortTrimsBooking::query(), $request);
            $trimsBookings = ShortTrimsBooking::query();

            return self::formatData($trimsBookings, $request);
        } else {
            return [];
        }
//        return self::formatData(ShortTrimsBooking::query(), $request);
    }

    public static function formatData($bookings, $request)
    {
        $factory_id = $request->get('factory_id');
        $buyer_id = $request->get('buyer_id');
        $supplier_id = $request->get('supplier_id');
        $wo_no = $request->get('wo_no');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        return $bookings->where('factory_id', $factory_id)
            ->when($buyer_id, function ($query) use ($buyer_id) {
                return $query->where('buyer_id', $buyer_id);
            })
            ->when($supplier_id, function ($query) use ($supplier_id) {
                return $query->where('supplier_id', $supplier_id);
            })
            ->when($wo_no, function ($query) use ($wo_no) {
                return $query->where('unique_id', $wo_no);

                // return $query->whereHas('bookingDetails', function ($q) use ($wo_no) {
                //     $q->where('unique_id', $wo_no);
                // });
                // return $query->where(\DB::raw('substr(unique_id, -5)'), 'LIKE', '%' . $wo_no);
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query->whereBetween('booking_date', [$from_date, $to_date]);
            })
            ->whereHas('bookingDetails', function ($query) {
                return $query->whereNotNull('details');
            })
            ->with('bookingDetails', 'buyer:id,name', 'supplier:id,name')
            ->get();
    }

    public static function beforeGoodReceive($bookings, $request)
    {
        return $bookings->whereIn('source', ['1', '2'])
            ->where('pay_mode', 2)
            ->where('source', $request->get('source'));
    }

    public static function afterGoodReceive($bookings, $request)
    {
        return $bookings->whereIn('source', ['2', '3'])
            ->whereIn('pay_mode', ['1', '3', '4'])
            ->where('source', $request->get('source'));
    }
}
