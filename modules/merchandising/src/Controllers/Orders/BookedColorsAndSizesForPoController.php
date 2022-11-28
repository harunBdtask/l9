<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

class BookedColorsAndSizesForPoController
{
    public function __invoke()
    {
        request()->validate([
            'po_no' => 'required',
//           'items_id' => 'required|array'
        ]);


        $poNo = request('po_no');
        $itemsId = request('items_id');
        $data = collect(\DB::select('select booking_id from fabric_booking_details where find_in_set(?, po_no) and deleted_at is NULL', [$poNo]));
        $bookingsId = $data->pluck('booking_id');
        return [
            $bookingsId,
            $itemsId,
        ];
    }
}
