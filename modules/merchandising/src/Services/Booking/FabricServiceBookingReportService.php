<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBooking;

class FabricServiceBookingReportService
{
    public static function mainBooking($id)
    {
        $bookingDetails = FabricServiceBooking::with(
            'supplier:id,name,address_1,address_2', 'buyer:id,name', 'details', 'details.yarnCount', 'details.uom',
                    'details.yarnComposition', 'details.brand', 'details.garmentsColor', 'processInfo'
        )->find($id);

        return self::formatBookingData($bookingDetails);
    }

    public static function formatBookingData($bookingDetails)
    {
        $bookingDetails['FabricServiceDetails'] = collect(collect($bookingDetails)['details'])->map(function ($item) {
            return [
                'style_name' => $item['style_name'] ?? '',
                'po_no' => $item['po_no'] ?? '',
                'gmts_color' => collect($item)['garments_color']['name'] ?? '',
                'labdip_no' => $item['labdip_no'] ?? '',
                'fabric_description' => $item['fabric_description'] ?? '',
                'yarn_count' => collect($item['yarn_count'])['yarn_count'] ?? '',
                'lot' => $item['lot'] ?? '',
                'yarn_composition' => collect($item['yarn_composition'])['yarn_composition'] ?? '',
                'brand' => collect($item['brand'])['brand_name'] ?? 0,
                'uom' => $item['uom']['unit_of_measurement'],
                'mc_dia' => $item['mc_dia'] ?? '',
                'finish_dia' => $item['finish_dia'] ?? '',
                'finish_gsm' => $item['finish_gsm'] ?? 0,
                'stich_length' => $item['stich_length'] ?? 0,
                'mc_gauge' => $item['mc_gauge'] ?? 0,
                'wo_qty' => $item['wo_qty'] ?? 0,
                'rate' => $item['rate'] ?? 0,
                'amount' => $item['amount'] ?? 0,
            ];
        });

        return $bookingDetails;
    }
}
