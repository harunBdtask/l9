<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class TrimsBookingWoWiseReportService
{
    public static function mainBookingData($id)
    {
        $trimsBookings = TrimsBooking::with('buyer:id,name', 'factory:id,factory_name,group_name', 'supplier:id,name', 'bookingDetails')->find($id);

        return self::formatData($trimsBookings);
    }

    public static function formatData($trimsBookings)
    {
        if ($trimsBookings) {
            $budgetUniqueId = optional($trimsBookings->bookingDetails)->pluck('budget_unique_id')->unique();
            $trimsBookingsOrders = Order::with('season', 'dealingMerchant', 'priceQuotation:id,revised_no')->whereIn('job_no', $budgetUniqueId)->get();
            $trimsBookings['issued_by'] = collect($trimsBookingsOrders)->pluck('dealingMerchant')->map(function ($val) {
                return $val->first_name . ' ' . $val->last_name;
            })->unique()->implode(',');
            $details = [];

            $data = collect($trimsBookings->bookingDetails)->map(function ($booking) use ($trimsBookingsOrders) {
                $unit = $trimsBookingsOrders->firstWhere('job_no', $booking->budget_unique_id)['order_uom_id'] ?? '';

                return collect($booking->details)->map(function ($item) use ($booking, $unit) {
                    return [
                        'style_name' => $booking->style_name,
                        'po' => $booking->po_no,
                        'gmt_color' => array_key_exists('color', $item) ? $item['color'] : '',
                        'gmt_size' => array_key_exists('size', $item) ? $item['size'] : '',
                        'gmt_qty' => array_key_exists('pcs', $item) ? $item['pcs'] : '',
                        'uom' => $booking->cons_uom_value,
                        'unit' => $unit == 1 ? 'Pcs' : ($unit == 2 ? 'Set' : ''),
                        'item_description' => array_key_exists('item_description', $item) ? $item['item_description'] : '',
                        'item_color' => array_key_exists('item_color', $item) ? $item['item_color'] : '',
                        'item_size' => array_key_exists('item_size', $item) ? $item['item_size'] : '',
                        'booking_qty' => array_key_exists('wo_total_qty', $item) ? $item['wo_total_qty'] : '',
                        'remarks' => '',
                    ];
                });
            })->flatten(1);

            $trimsBookings['trimsDetails'] = $data;
        }

        return $trimsBookings;
    }
}
