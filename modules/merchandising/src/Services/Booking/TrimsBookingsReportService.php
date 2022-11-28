<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\CareInstruction;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class TrimsBookingsReportService
{
    public static function bookingData($id)
    {
        $trimsBookings = TrimsBooking::query()->with(
            'buyer:id,name',
            'factory:id,factory_name,group_name',
            'supplier:id,name,address_1,address_2',
            'bookingDetails.itemGroup.itemSubGroup',
            'bookingDetails.itemGroup.group',
        )->where('id', $id)->first();

        return self::processData($trimsBookings);
    }

    public static function shortBookingData($id)
    {
        $trimsBookings = ShortTrimsBooking::query()
            ->with('buyer:id,name', 'factory:id,factory_name,group_name', 'supplier:id,name', 'bookingDetails')
            ->where('id', $id)
            ->first();

        return self::processData($trimsBookings);
    }

    public static function processData($trimsBookings)
    {
        //seasons and dealing merchant need to fetch from order table
        if ($trimsBookings) {
            $budgetUniqueId = optional($trimsBookings->bookingDetails)->pluck('budget_unique_id')->unique();
            $orders = Order::with('season', 'dealingMerchant', 'priceQuotation:id,revised_no', 'purchaseOrders.poDetails')
                ->whereIn('job_no', $budgetUniqueId)
                ->get();

            $trimsBookingsOrders = $orders->map(function ($query) {
                return [
                    'season' => $query->season->season_name,
                    'dealing_merchant' => $query->dealingMerchant->first_name . ' ' . $query->dealingMerchant->last_name,
                    'revise_no' => $query->priceQuotation->revised_no ?? '',
                    'repeat_no' => $query->repeat_no,
                ];
            });

            $trimsBookings['address'] = optional($trimsBookings->supplier)->address_1;
            $trimsBookings['season'] = $trimsBookingsOrders->pluck('season')->unique()->implode(',');
            $trimsBookings['dealing_merchant'] = $trimsBookingsOrders->pluck('dealing_merchant')->unique()->implode(',');
            $trimsBookings['revised_no'] = $trimsBookingsOrders->pluck('revised_no')->implode(',');
            $trimsBookings['repeat_no'] = $trimsBookingsOrders->pluck('repeat_no')->implode(',');

            $budget = Budget::query()->whereIn('job_no', $budgetUniqueId)->get();

            // no sensitivity selected
            $trimsBookingsDetailsWithoutSensitivity = self::formatTrimsBookingSensitivityWise(
                $trimsBookings->bookingDetails->whereNull('sensitivity'),
                $budget, $sizeWise = false,
                $orders
            );

            //as per gmts color
            $trimsBookingsDetailsAsPerGmtsColor = self::formatTrimsBookingSensitivityWise(
                $trimsBookings->bookingDetails->where('sensitivity', '1'),
                $budget, $sizeWise = false,
                $orders
            );

            // no sensitivity
            $trimsBookingsDetailsNoSensitivity = self::formatTrimsBookingSensitivityWise(
                $trimsBookings->bookingDetails->whereIn('sensitivity', [null, '1']),
                $budget, $sizeWise = false,
                $orders
            );

            // contrast color sensitivity
            $trimsBookingsDetailsContrastColorSensitivity = self::formatTrimsBookingSensitivityWise(
                $trimsBookings->bookingDetails->where('sensitivity', '2'),
                $budget, $sizeWise = false,
                $orders
            );

            //size sensitivity
            $trimsBookingsDetailsSizeSensitivity = self::formatTrimsBookingSensitivityWise(
                $trimsBookings->bookingDetails->where('sensitivity', '3'),
                $budget, $sizeWise = true,
                $orders
            );

            //Color & Size Sensitive
            $trimsBookingsDetailsColorAndSizeSensitivity = self::formatTrimsBookingSensitivityWise(
                $trimsBookings->bookingDetails->where('sensitivity', '4'),
                $budget, $sizeWise = true,
                $orders
            );
        }

        return [
            'trimsBookings' => $trimsBookings,
            'trimsBookingsDetailsSizeSensitivity' => $trimsBookingsDetailsSizeSensitivity ?? [],
            'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookingsDetailsColorAndSizeSensitivity ?? [],
            'trimsBookingsDetailsNoSensitivity' => $trimsBookingsDetailsNoSensitivity ?? [],
            'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookingsDetailsContrastColorSensitivity ?? [],
            'trimsBookingsDetailsWithoutSensitivity' => $trimsBookingsDetailsWithoutSensitivity ?? [],
            'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookingsDetailsAsPerGmtsColor ?? [],
            'total' => $trimsBookings ? optional($trimsBookings->bookingDetails)->pluck('work_order_amount')->sum() : 0,
            'totalQty' => $trimsBookings ? optional($trimsBookings->bookingDetails)->pluck('work_order_qty')->sum() : 0,
            'payMode' => TrimsBooking::PAY_MODE,
            'source' => TrimsBooking::SOURCE,
        ];
    }

    public static function formatTrimsBookingSensitivityWise($sensitivityWiseDetails, $budget, $sizeWise = null, $orders)
    {
        return collect($sensitivityWiseDetails)->map(function ($trimsDetails) use ($budget, $sizeWise, $orders) {
            $trimsOrders = collect($orders)->firstWhere('job_no', $trimsDetails['budget_unique_id']) ?
                collect($orders)->firstWhere('job_no', $trimsDetails['budget_unique_id'])['purchaseOrders']->pluck('poDetails')->flatten(1)->pluck('quantity_matrix')->flatten(1)->where('particular', 'Qty.')->values() : null;
            $trimsBudget = collect($budget)->firstWhere('job_no', $trimsDetails['budget_unique_id']);
            $trimsBudgetDetails = !empty($trimsBudget) ? $trimsBudget->trimDetails()->where('group_name', $trimsDetails['item_name'])
                ->where('nominated_supplier_id', $trimsDetails['nominated_supplier_id'])
                ->where('cons_uom_value', $trimsDetails['cons_uom_value'])->values()->pluck('breakdown.details')->flatten(1) : null;

            $costingMultiplier = $trimsBudget->costing_multiplier ?? 0;

            return collect($trimsDetails->details)->map(function ($item) use ($trimsDetails, $trimsBudgetDetails, $sizeWise, $trimsBudget, $costingMultiplier) {
                $sizeWise ? $colorWiseDetails = ($trimsBudgetDetails ? collect($trimsBudgetDetails)->firstWhere('size', $item['size']) : '')
                    : $colorWiseDetails = ($trimsBudgetDetails ? collect($trimsBudgetDetails)->firstWhere('color', $item['color']) : '');

                $actulCons = $colorWiseDetails['total_cons'] ?? 0;
                $totalCons = $item['wo_total_qty'] ?? 0;
                $actualConsratio = $costingMultiplier != 0 ? ($actulCons / $costingMultiplier) : 0;
                $wo_qty = $actualConsratio != 0 ? $totalCons / $actualConsratio : 0;
                $careInstruction = isset($item['care_instruction'])
                    ? CareInstruction::query()->find($item['care_instruction'])['instruction'] ?? ''
                    : '';
                $bodyPart = isset($item['body_part']) ? BodyPart::query()->find($item['body_part'])['name'] ?? '' : '';
                $gmtsItem = isset($item['gmts_item_id'])
                    ? GarmentsItem::query()->find($item['gmts_item_id'])['name'] ?? null
                    : null;

                $budgetItemBreakdown = null;

                if (isset($trimsBudget) && isset($trimsBudget->trimCosting)) {
                    $budgetItemBreakdown = collect($trimsBudget->trimCosting->details['details'])->where('group_name', $trimsDetails['item_name'])->first()['breakdown'];
                }

                return [
                    'budget_unique_id' => $trimsDetails['budget_unique_id'] ?? '',
                    'style_name' => $trimsDetails['style_name'] ?? '',
                    'reference_no' => $trimsDetails->budget->order->reference_no ?? '',
                    'total_qty' => $trimsDetails['total_qty'] ?? 0,
                    'po_no' => $trimsDetails['po_no'] ?? '',
                    'item_name' => $trimsDetails['item_name'] ?? '',
                    'item_subgroup_name' => $trimsDetails->itemGroup->itemSubGroup->name ?? '',
                    'extra_field_keys' => $trimsDetails->itemGroup->group->fields ?? '',
                    'gmts_item_name' => $gmtsItem ?? '',
                    'mtr_per_cone' => $budgetItemBreakdown['mtr_per_cone'] ?? null,
                    'mtr_per_gmts' => $budgetItemBreakdown['mtr_per_gmts'] ?? null,
                    'details' => [
                        'style' => $trimsDetails['style_name'] ?? '',
                        'gmts_item_name' => $gmtsItem ?? null,
                        'item_description' => $item['item_description'] ?? '',
                        'pcs' => $item['pcs'] ?? '',
                        'brand' => $item['brand'] ?? '',
                        'length_inch' => $item['length_inch'] ?? '',
                        'width_inch' => $item['width_inch'] ?? '',
                        'length_cm' => $item['length_cm'] ?? '',
                        'width_cm' => $item['width_cm'] ?? '',
                        'plaster_fastener_adjustable_straps_quality' => $item['plaster_fastener_adjustable_straps_quality'] ?? '',
                        'quality' => $item['quality'] ?? '',
                        'nominated_supplier' => $item['nominated_supplier'] ?? '',
                        'color' => $item['color'] ?? '',
                        'wo_qty' => round($wo_qty),
                        'po_no' => $item['po_no'] ?? '',
                        'item_name' => $trimsDetails['item_name'] ?? '',
                        'remarks' => $item['remarks'] ?? '',
                        'ref' => $item['ref'] ?? '',
                        'care_symbol' => $item['care_symbol'] ?? '',
                        'attachment' => $item['attachment'] ?? '',
                        'care_instruction' => $careInstruction,
                        'production_batch' => $item['production_batch'] ?? '',
                        'fiber_composition' => $item['fiber_composition'] ?? '',
                        'moq_qty' => $item['moq_qty'] ?? 0,
                        // $sizeWise ?
                        //                            ($trimsOrders ? $trimsOrders->where('color', $item['color'])->where('size', $item['size'])->pluck('value')->sum() : 0) :
                        //                            ($trimsOrders ? $trimsOrders->where('color', $item['color'])->pluck('value')->sum() : 0),
                        'item_color' => $item['item_color'] ?? '',
                        'pantone_code' => $item['pantone_code'] ?? '',
                        'item_size' => $item['item_size'] ?? '',
                        'item_sizes' => $item['item_sizes'] ?? '',
                        'size_range' => $item['size_range'] ?? '',
                        'care_label_type' => $item['care_label_type'] ?? '',
                        'size' => $item['size'] ?? '',
                        'wo_total_qty' => $item['wo_total_qty'] ?? '',
                        'rate' => format($item['rate'], 2) ?? '',
                        'bd_taka' => $item['bd_taka'] ?? '',
                        'amount' => format($item['wo_total_qty'] * $item['rate'], 4), //number_format($item['amount'] / 12, 2) ?? '',
                        'uom' => $trimsDetails['cons_uom_value'] ?? '',
                        'actual_cons' => $colorWiseDetails['cons_gmts'] ?? 0,
                        'process_loss' => $colorWiseDetails['ext_cons_percent'] ?? 0,
                        'total_cons' => $colorWiseDetails['total_cons'] ?? 0,
                        'wo_order_qty' => $item['wo_qty'] ?? '',
                        'team_id' => $item['team_id'] ?? '',
                        'division' => $item['division'] ?? '',
                        'style_ref' => $item['style_ref'] ?? '',
                        'body_part' => $bodyPart,
                        'factory_ref_no' => $item['factory_ref_no'] ?? '',
                        'po_ref' => $item['po_ref'] ?? '',
                        'qty_per_carton' => $item['qty_per_carton'] ?? '',
                        'measurement' => $item['measurement'] ?? '',
                        'fabric_ref' => $item['fabric_ref'] ?? '',
                        'thread_count' => $item['thread_count'] ?? '',
                        'cons_per_mtr' => $item['cons_per_mtr'] ?? '',
                        'league' => $item['league'] ?? '',
                        'age_or_size' => $item['age_or_size'] ?? '',
                        'poly_bag_art_work' => $item['poly_bag_art_work'] ?? '',
                        'fold_over' => $item['fold_over'] ?? '',
                        'poly_thickness' => $item['poly_thickness'] ?? '',
                        'swatch' => $item['swatch'] ?? '',
                        'sizer' => $item['sizer'] ?? '',
                        'combo_color' => $item['combo_color'] ?? '',
                        'item_code' => $item['item_code'] ?? '',
                        'binding_color' => $item['binding_color'] ?? '',
                        'zip_puller_ref' => $item['zip_puller_ref'] ?? '',
                        'zipper_puller_teeth_color' => $item['zipper_puller_teeth_color'] ?? '',
                        'zipper_tape_color' => $item['zipper_tape_color'] ?? '',
                        'contrast_cord_color' => $item['contrast_cord_color'] ?? '',
                        'zipper_size' => $item['zipper_size'] ?? '',
                        'fusing_status' => $item['fusing_status'] ?? '',

                    ],
                ];
            })->values();
        });
    }
}
