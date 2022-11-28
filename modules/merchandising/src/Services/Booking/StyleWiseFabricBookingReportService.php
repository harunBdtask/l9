<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;

class StyleWiseFabricBookingReportService
{
    public static function mainFabricData($id)
    {
        $fabricBookings = FabricBooking::with(
            'details',
            'buyer:id,name',
            'factory:id,factory_name,factory_address',
            'supplier:id,name,address_1,address_2',
            'currency:id,currency_name',
            'detailsBreakdown'
        )->find($id);

        return self::formatData($fabricBookings);
    }

    public static function formatData($fabricBookings)
    {
        if (isset($fabricBookings)) {
            $job_no = optional($fabricBookings->detailsBreakdown)->pluck('job_no')->unique()->values();
            $budget = Budget::query()
                ->with('order.purchaseOrders.poDetails',
                    'order.productDepartment',
                    'order.season:id,season_name',
                    'order.dealingMerchant:id,email,screen_name,first_name,last_name',
                    'order.teamLeader')
                ->whereIn('job_no', $job_no)->get();
            $fabricBookings['dealing_merchant'] = $budget->pluck('order.dealingMerchant.full_name')->unique()->implode(',');
            $fabricBookings['team_leader'] = $budget->pluck('order.teamLeader.screen_name')->unique()->implode(',');

            $data = $fabricBookings->detailsBreakdown->whereIn('uom', [1, 2])->map(function ($value) use ($fabricBookings, $budget) {

                $composition = explode(', ', $value->composition);
                $compositionValue = [];
                foreach ($composition as $comp) {
                    $percentage = collect(explode(' ', $comp))->last();
                    $val = collect(explode(' ', $comp, -1))->implode(' ');
                    $composition_value = $percentage . ' ' . $val;
                    array_push($compositionValue, $composition_value);
                }
                return [
                    'style_name' => collect($budget)->where('job_no', $value->job_no)->first()->style_name ?? '',
                    'po' => $value->po_no,
                    'fabric_composition' => $value->body_part_value  .', ' . $value->construction . ' ['. $value->composition  .'] '. ', ' . $value->color_type_value,
                    'gsm' => $value->gsm,
                    'fabric_composition_v4' => $value->construction . ', ' . collect($compositionValue)->join(', ') . ', ' . $value->color_type_value,
                    'cuttable_width' => $value->dia,
                    'team' => $value->gmt_color,
                    'fabric_color' => $value->item_color,
                    'process_loss' => $value->process_loss,
                    'uom_value' => $value->uom_value,
                    'actual_wo_qty' => $value->actual_wo_qty ?? 0,
                    'uom' => $value->uom,
                    'color_type_value' => $value->color_type_value
                ];
            })->values();

            $fabricBookings['details'] = $data;
        }


        return $fabricBookings;
    }
}
