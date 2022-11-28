<?php


namespace SkylarkSoft\GoRMG\Commercial\Services\Bookings;

class EmbellishmentService
{
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
                return $query->where(\DB::raw('substr(unique_id, -5)'), 'LIKE', '%' . $wo_no);
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query->whereBetween('booking_date', [$from_date, $to_date]);
            })
            ->whereHas('bookingDetails', function ($query) {
                return $query->whereNotNull('details');
            })
            ->with('bookingDetails.embellishmentType', 'buyer:id,name', 'supplier:id,name')
            ->get();
    }
}
