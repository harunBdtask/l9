<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

class FabricBookingReportService
{
    public static function mainFabricData($id)
    {
        $fabricBookings = FabricBooking::with(
            'details',
            'detailsBreakdown',
            'buyer:id,name',
            'factory:id,factory_name,factory_address',
            'supplier:id,name,address_1,address_2',
            'currency:id,currency_name',
        )->find($id);

        return self::formatData($fabricBookings);
    }

    public static function shortFabricData($id)
    {
        $fabricBookings = ShortFabricBooking::with(
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
//        return $fabricBookings;
        $collar_cuff_val = [];
        $yarnDetails = [];

        if (isset($fabricBookings)) {
            $job_no = $fabricBookings->detailsBreakdown->pluck('job_no')->unique()->values();
            $budget = Budget::query()
                ->with('order.purchaseOrders.poDetails', 'order.productDepartment', 'order.season:id,season_name', 'order.dealingMerchant:id,email,screen_name,first_name,last_name')
                ->whereIn('job_no', $job_no)->get();

            $stripMeasurement = [];
            foreach ($budget as $index => $item) {
                $fabric_details = $item->fabricDetails();
                foreach ($fabric_details as $detailIndex => $detailItem) {
                    if (isset($detailItem['stripMeasurement']) && count($detailItem['stripMeasurement']) > 0) {
                        $stripMeasurement[] = array_add($detailItem['stripMeasurement'], 'type', $detailItem['body_part_value']);
                    }
                }

            }

            $productDept = $budget->pluck('order.productDepartment.product_department')->unique()->implode(',');
            $season = $budget->pluck('order.season.season_name')->unique()->implode(',');
//            $dealing_merchant = $budget->pluck('order.dealingMerchant.full_name')->unique()->implode(',');
            $poDetailBreakdown = [];

            if ($fabricBookings->level == 1) {
                $budgetQty = $budget->pluck('order.purchaseOrders')->flatten(1)->sum('po_quantity');
            } else if ($fabricBookings->level == 2) {
                $poDetailBreakdown = PurchaseOrderDetail::query()
                    ->whereHas('purchaseOrder', function ($query) use ($fabricBookings) {
                        $query->where('po_no', $fabricBookings->po_no);
                    })
                    ->get();
                $budgetQty = $poDetailBreakdown->sum('quantity');
            }

            $fabricBookings['order_uom'] = $budget->map(function ($item) {
                return $item['unit_of_measurement'];
            })->unique()->implode(', ');
            $fabricBookings['budget_qty'] = $budgetQty;
            $fabricBookings['productDept'] = $productDept;
            $fabricBookings['season'] = $season;
//            $fabricBookings['dealing_merchant'] = $dealing_merchant;

            $fabricFormDetails = collect($budget->pluck('costings')->flatten(1)->where('type', 'fabric_costing')
                ->values()->pluck('details'))->pluck('details.fabricForm')->flatten(1);

            if ($fabricBookings) {
                //fabric details section
                $fabricBookings['details'] = self::fabricDetailsDataFormat($fabricBookings, $budget, $poDetailBreakdown);
            }

//                  collar cuff section
            $collar_cuff_val = self::collarCuffDetails($fabricBookings, $fabricFormDetails, $budget);

//                  yarnDetails
            $fabricBookingsDetailsBreakdown = $fabricBookings->detailsBreakdown;
            $yarnDetails = self::yarnDetailsFormat($budget, $fabricBookingsDetailsBreakdown, $yarnDetails);

//                  PO details section
            $poDetails = [];
            $orderDetails = Order::query()->whereIn('job_no', $job_no)->with('purchaseOrders')->get();
            $purchaseOrders = $orderDetails->pluck('purchaseOrders')->flatten();
//        2 Po Level 1 Job Level
            if ($fabricBookings && $fabricBookings->level_name == "Po Label") {
                $poWise_qty = collect($fabricFormDetails)->pluck('greyConsForm.details')->flatten(1);

                $shortFabricBookings = ShortFabricBookingDetailsBreakdown::query()
                    ->whereIn('job_no', $job_no)
                    ->whereHas('shotFabricBooking', function ($query) {
                        return $query->where('level', 1);
                    })
                    ->get();

                foreach (collect($fabricBookings->detailsBreakdown)->groupBy('po_no') as $key => $item) {
                    $poDetails[] = [
                        'po_no' => $key,
                        'shipment_date' => collect($purchaseOrders)->where('po_no', $key)->first()->ex_factory_date ?? '',
                        'booking_qty' => collect($item)->sum('wo_qty') ?? 0,
                        'budget_qty' => $poWise_qty->where('po_no', $key)->sum('total_qty') ?? 0,
                        'short_booking_qty' => $shortFabricBookings ? ($shortFabricBookings->where('po_no', $key)->sum('wo_qty') ?? 0) : 0,
                    ];
                }
            } else {
                $poDetails = collect($fabricBookings->detailsBreakdown->pluck('po_no')->unique())->map(function ($item) use ($purchaseOrders, $poDetails) {
                    $po = explode(",", $item);
                    foreach ($purchaseOrders as $val) {
                        if (in_array($val['po_no'], $po)) {
                            $poDetails[] = [
                                'po_no' => $val['po_no'],
                                'shipment_date' => ($purchaseOrders)->where('po_no', $val['po_no'])->first()->ex_factory_date ?? '',
                                'budget_qty' => 0,
                                'booking_qty' => 0,
                                'short_booking_qty' => 0,
                            ];
                        }
                    }

                    return collect($poDetails)->values();
                })->flatten(1);
            }

        }

        return [
            'fabricBookings' => $fabricBookings,
            'poDetails' => $poDetails ?? [],
            'collarDetails' => collect($collar_cuff_val)->pluck('collarDetails')->filter(function ($val) {
                    return $val != null;
                })->values()->flatten(1) ?? [],
            'cuffDetails' => count($collar_cuff_val) > 0 ? collect($collar_cuff_val)->pluck('cuffDetails')->filter(function ($val) {
                return $val != null;
            })->values()->flatten(1) : [],
            'yarnDetails' => collect($yarnDetails)->flatten(1) ?? [],
            'collarStripDetails' => collect($stripMeasurement)->filter(function ($item) {
                return $item['type'] == 'Collar';
            })->values(),
            'cuffStripDetails' => collect($stripMeasurement)->filter(function ($item) {
                return $item['type'] == 'Cuff';
            })->values(),
        ];
    }


    private static function fabricDetailsDataFormat($fabricBookings, $budget, $poDetailBreakdown)
    {
        return $fabricBookings->detailsBreakdown->where('uom', '!=', 4)->map(function ($value) use ($budget, $fabricBookings, $poDetailBreakdown) {
            $budgetDetails = collect($budget)->firstWhere('job_no', $value['job_no']);
            $budgetDetails = $budgetDetails ? $budgetDetails->knitFabric()->values() : [];

            $item = array();
            foreach ($budgetDetails as $val) {
                if ($val['fabric_composition_value'] == $value->construction . ' ' . '[' . $value->composition . ']'
                    && $val['body_part_id'] == $value->body_part_id
                    && $val['gsm'] == $value->gsm
                    && $val['color_type_id'] == ($value->color_type_id != 0 ? $value->color_type_id : -1 || null)
                    && $val['dia_type'] == $value->dia_type
                ) {
                    $val['grey_cons_avg'] = $val['greyConsForm']['calculation']['grey_cons_avg'] ?? 0;
                    $val['finish_cons_avg'] = $val['greyConsForm']['calculation']['finish_cons_avg'] ?? 0;
                    $item[] = $val;
                }
            }

            $itemsForSizeAndPoQty = collect($item)->values()->pluck('greyConsForm.details')
                ->collapse()->filter(function ($item) {
                    return $item['finish_cons'] > 0;
                })->values()
                ->where('color_id', $value->color_id)
                ->where('finish_cons', '!=', 0)
                ->where('finish_cons', '!=', null);

            if ($fabricBookings->level == 1) {
                $sizeWisePoQty = collect($itemsForSizeAndPoQty)->sum('qty');
            } elseif ($fabricBookings->level == 2) {
                $sizeWisePoQty = $poDetailBreakdown->where('color_id', $value->color_id)->sum('quantity');
            }

            $sizes = collect($itemsForSizeAndPoQty)->pluck('size')->unique()->values()->implode(', ');
            $diaFinType = collect($itemsForSizeAndPoQty)->pluck('dia_fin_type')->unique()->values()->implode(', ');
            $actualFinishCons = collect($itemsForSizeAndPoQty)->count() == 0 ? 0 : (collect($itemsForSizeAndPoQty)->sum('finish_cons') / collect($itemsForSizeAndPoQty)->count());
            $totalGreyCons = collect($itemsForSizeAndPoQty)->count() == 0 ? 0 : (collect($itemsForSizeAndPoQty)->sum('grey_cons') / collect($itemsForSizeAndPoQty)->count());

            $cad_consumption = count($item) > 0 ? collect((collect($item)->pluck('greyConsForm.calculation.finish_cons_avg'))) : [];
            $gmts_item = count($item) > 0 ? collect($item)->pluck('garment_item_name') : [];
            $grey_cons_avg = count($item) > 0 ? collect($item)->pluck('grey_cons_avg') : [];

            $uom = isset($value->uom_value) ? strtolower($value->uom_value) : null;
            $yards = $value->yards == null ? ($uom == 'kg' ? FabricBookingReportService::convertKgToYards($value, $value->actual_wo_qty) : 0) : $value->yards;

            return [
                'style_name' => collect($budget)->where('job_no', $value->job_no)->first()->style_name ?? '',
                'composition' => $value->construction . ', [ ' . $value->composition . ']',
                'body_parts' => $value->body_part_value,
                'fabric_composition' => $value->construction . $value->composition,
                'gsm' => $value->gsm,
                'dia' => $value->dia,
                'dia_type' => $value->dia_type_value,
                'color_type' => $value->color_type_value,
                'dia_fin_type' => $diaFinType, //$value->dia_fin_type,
                'created_at' => $value->created_at,
                'updated_at' => $value->updated_at,
                'gmts_color' => $value->gmt_color,
                'fabric_color' => $value->item_color,
                'process_loss' => $value->process_loss,
                'uom' => $value->uom_value,
                'total_fabric_qty' => $value->actual_wo_qty,
                'sample_fabric_qty' => $value->sample_fabric_qty,
                'inspection_sample_qty' => $value->inspection_sample_qty,
                'amount' => ($value->rate * $value->actual_wo_qty),
                'rate' => $value->rate,
                'cad_consumption' => collect($cad_consumption)->implode(','),
                'gmts_item' => collect($gmts_item)->implode(','),
                'fabric_consumption' => $totalGreyCons,
                'actual_consumption' => $actualFinishCons,
                'remarks' => $value->remarks,
                'remarks2' => $value->remarks2,
                'pantone' => $value->pantone,
                'yards' => $yards,
                'composition_for_mondol' => $value->composition,
                'construction' => $value->construction,
                'cuttable_dia' => $value->cuttable_dia,
                'sizes' => $sizes ?? null,
                'sizeWisePoQty' => $sizeWisePoQty ?? null,
                'code' => $value->code,
            ];
        })->values();

    }

    public static function convertKgToYards($value, $actualWorkOrderQty)
    {
        $kgs = $actualWorkOrderQty;
        $gsm = is_numeric($value['gsm']) ? $value['gsm'] : 0;
        $dia = is_numeric($value['dia']) ? $value['dia'] : 0;
        $const1 = 10000;
        $const2 = 36;
        $inch = 2.54;
        if ($kgs == 0 || $gsm == 0 || $dia == 0) {
            return 0;
        }
        $gsm_points = $gsm / 1000;
        $width_cm = $dia * $inch;

        $yards = ($kgs / $gsm_points * $const1 / $width_cm / $inch / $const2);
        return sprintf("%.4f", $yards);

    }

    private static function yarnDetailsFormat($budget, $fabricBookingsDetailsBreakdown, $yarnDetails)
    {
        return collect($budget)->map(function ($budgetItem) use ($fabricBookingsDetailsBreakdown, $yarnDetails) {
            $budgetId = $budgetItem['job_no'];
            $style_name = $budgetItem['style_name'];
            $yarn_details = $budgetItem->yarnDetails();

            if ($yarn_details) {
                foreach ($yarn_details as $details) {
                    $fabric_description = explode(', ', $details['fabric_description'], 2);
                    $body_part_value = $fabric_description[0];
                    $fabric_composition_value = explode(' [', $fabric_description[1], 2);
                    $construction = $fabric_composition_value[0];
                    $composition = explode(']', $fabric_composition_value[1])[0];

                    $fabricBookingsDetails = $fabricBookingsDetailsBreakdown
                        ->where('job_no', $budgetId)
                        ->where('body_part_value', $body_part_value)
                        ->where('construction', $construction)
                        ->where('composition', $composition)
                        ->map(function ($collection) {
                            $collection['actual_wo_qty'] += $collection['sample_fabric_qty'];
                            $collection['actual_wo_qty'] += ceil(($collection['sample_fabric_qty'] * $collection['process_loss'] / 100));
                            return $collection;
                        });

                    $actual_wo_qty = $fabricBookingsDetails->values()->sum('actual_wo_qty');
                    $yarnCount = YarnCount::query()->find($details['count'] ?? null);
                    $yarnDetails [] = [
                        'budgetId' => $budgetId,
                        'style' => $style_name,
                        'fabric_description' => $details['fabric_description'] ?? '',
                        'yarn_description' => ($yarnCount->yarn_count ?? '') . ', '
                            . ($details['yarn_composition_value'] ?? '') . ', ' . ($details['type'] ?? ''),
                        'yarn_qty' => $details['cons_qty'] ?? 0,
                        'rate' => $details['rate'] ?? 0,
                        'total_yarn_qty' => ($actual_wo_qty * $details['percentage']) / 100,
                    ];
                }

                return $yarnDetails;
            }
        });
    }

    private static function collarCuffDetails($fabricBookings, $fabricFormDetails, $budget)
    {
        $cuff_collar_details = [];
        return collect($fabricBookings->collar_cuff_info)->map(function ($collar_cuff_value) use ($fabricFormDetails, $fabricBookings, $budget, $cuff_collar_details) {
            $body_part = explode(', ', $collar_cuff_value['body_part'], 2);
            $collar_cuff_val = $body_part[0];
            $fabric_composition_value = explode(', ', $body_part[1], 2);
            $composition_value = explode(' [', $fabric_composition_value[1]);
            $construction = $composition_value[0];
            $composition = explode(']', $composition_value[1])[0];
            $style = collect($budget)->where('job_no', $collar_cuff_value['budget_unique_id'])->first()->style_name ?? '';

            $fabricFormDetailsValue = $fabricFormDetails
                ->where('body_part_value', $collar_cuff_val)
                ->where('color_type_value', $fabric_composition_value[0])
                ->where('fabric_composition_value', $fabric_composition_value[1])->first();

            if (isset($fabricFormDetailsValue) && $fabricFormDetailsValue['body_part_type'] == "Flat Knit") {
                return $cuff_collar_details [] = [
                    'collarDetails' => self::formatCollarCuffData($collar_cuff_value, $fabricBookings, $collar_cuff_val, $fabric_composition_value, $construction, $composition, $style, $fabricFormDetailsValue),
                ];
            }
            if (isset($fabricFormDetailsValue) && $fabricFormDetailsValue['body_part_type'] == "Cuff") {
                return $cuff_collar_details [] = [
                    'cuffDetails' => self::formatCollarCuffData($collar_cuff_value, $fabricBookings, $collar_cuff_val, $fabric_composition_value, $construction, $composition, $style, $fabricFormDetailsValue),
                ];
            }
        });
    }

    private static function formatCollarCuffData($collar_cuff_value, $fabricBookings, $collar_cuff_val, $fabric_composition_value, $construction, $composition, $style, $fabricFormDetailsValue)
    {
        $colorWiseCollar = collect($collar_cuff_value['details'])->groupBy('color');
        $cuffDetails = [];
        foreach ($colorWiseCollar as $key => $collarValue) {
            $fabricBreakdown = $fabricBookings->detailsBreakdown
//                ->where('job_no', $collar_cuff_value['budget_unique_id'])
                ->where('body_part_value', $collar_cuff_val)
                ->where('color_type_value', $fabric_composition_value[0])
                ->where('construction', $construction)
                ->where('composition', $composition)
                ->where('gmt_color', $key)
                ->first();

            $cuffDetails[] = [
                'style' => $style,
                'gmts_item' => $fabricFormDetailsValue['garment_item_name'],
                'body_part_value' => $collar_cuff_val,
                'actual_qty' => collect($collarValue)->sum('qty'),
                'fabric_composition' => $fabric_composition_value[1],
                'color_type_value' => $fabric_composition_value[0],
                'gmts_color' => $key,
                'fabric_color' => $fabricBreakdown['item_color'] ?? '',
                'required_qty' => collect($collarValue)->sum('total_qty'),
                'excess' => $collar_cuff_value['excess'] ?? '',
                'rate' => $fabricBreakdown['rate'] ?? 0,
                'details' => $collarValue,
            ];
        }

        return $cuffDetails;
    }
}
