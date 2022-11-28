<?php


namespace SkylarkSoft\GoRMG\Commercial\Services\Bookings;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class YarnPurchaseOrderService
{


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
                return $query->where('wo_no', $wo_no);
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query->whereBetween('wo_date', [$from_date, $to_date]);
            })
            ->with('details', 'buyer:id,name', 'supplier:id,name')
            ->get();
    }

    public static function formatData2($bookings, $request)
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
                return $query->where('wo_no', $wo_no);
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query->whereBetween('wo_date', [$from_date, $to_date]);
            })
            ->has('details')
            ->with('details', 'buyer:id,name', 'supplier:id,name','details.yarnComposition:id,yarn_composition', 'details.yarnCount:id,yarn_count', 'details.unitOfMeasurement:id,unit_of_measurement')
            ->get();
    }

}
