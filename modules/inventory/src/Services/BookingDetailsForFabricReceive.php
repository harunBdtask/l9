<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetails;

class BookingDetailsForFabricReceive
{
    public function getData(Request $request)
    {
        if ($request->get('receivable_type') === 'fabric-booking') {
            $bookingDetails = new FabricBookingDetailsBreakdown();
        } else {
            $bookingDetails = new ShortFabricBookingDetails();
        }

        return $bookingDetails->where('booking_id', $request->get('booking_id'))->get()
            ->groupBy('body_part_id')
            ->flatMap(function ($detailBreakDownColor) use ($request) {
                return $detailBreakDownColor->groupBy('color_id')->map(function ($detailBreakDownColor) use ($request) {
                    return $detailBreakDownColor->groupBy('style_name')->flatmap(function ($detailBreakDown) use ($request) {
                        $styleName = $detailBreakDown->first()->budget->style_name;
                        $styleId = Order::query()->where('style_name', $styleName)->first()['id'] ?? null;
                        $detail = $detailBreakDown->first();
                        $fabricDescription = "{$detail->construction} [{$detail->composition}]";
                        $contrastColorId = collect($detailBreakDown)->pluck('item_color')->unique()
                            ? collect($detailBreakDown)->pluck('color_id')->unique()
                            : null;

                        $previousReceive = FabricReceiveDetail::query()->with('receiveReturnDetails')
                            ->where([
                                'style_id' => $styleId,
                                'gmts_item_id' => $detail->garments_item_id,
                                'body_part_id' => $detail->body_part_id,
                                'color_type_id' => $detail->color_type_id,
                                'color_id' => $detail->color_id,
                                'construction' => $detail->construction,
                                'uom_id' => $detail->uom,
                                'fabric_composition_id' => $detail->fabric_composition_id,
                                'dia' => $detail->dia,
                                'gsm' => $detail->gsm,
                            ])->get();

                        $totalPreviousQty = $previousReceive->sum('receive_qty');
                        $totalReturnQty = $previousReceive->pluck('receiveReturnDetails')->flatten()->sum('return_qty');

                        $bookingQty = collect($detailBreakDown)->sum('actual_wo_qty');
                        $balanceQty = $bookingQty - $totalPreviousQty + $totalReturnQty;

                        return [
                            'unique_id' => $detail->job_no,
                            'receivable_type' => $request->get('receivable_type'),
                            'receivable_id' => $request->get('booking_id'),
                            'buyer_id' => $detail->budget->buyer_id,
                            'buyer' => $detail->budget->buyer->name,
                            'style_id' => $styleId,
                            'style_name' => $detail->budget->style_name,
                            'po_no' => $detail->po_no ?? null,
                            'budget_id' => $detail->budget->id,
                            'batch_no' => null,
                            'gmts_item_id' => $detail->garments_item_id,
                            'gmts_item_name' => $detail->garments_item_name,
                            'body_part_id' => $detail->body_part_id,
                            'body_part_value' => $detail->body_part_value,
                            'color_type_id' => $detail->color_type_id,
                            'color_type_value' => $detail->color_type_value,
                            'fabric_composition_id' => $detail->fabric_composition_id,
                            'fabric_composition_value' => $detail->fabric_composition_value,
                            'construction' => $detail->construction,
                            'fabric_description' => $fabricDescription,
                            'dia' => $detail->dia,
                            'ac_dia' => $detail->dia,
                            'gsm' => $detail->gsm,
                            'ac_gsm' => $detail->gsm,
                            'dia_type' => $detail->dia_type,
                            'ac_dia_type' => $detail->dia_type,
                            'dia_type_value' => $detail->dia_type_value,
                            'ac_dia_type_value' => $detail->dia_type_value,
                            'color_id' => $detail->color_id,
                            'color_name' => $detail->color,
                            'contrast_color_id' => $contrastColorId,
                            'contrast_color_name' => $detail->gmt_color,
                            'uom_id' => $detail->uom,
                            'uom_name' => $detail->uom_value,
                            'receive_qty' => round($balanceQty),
                            'balance_qty' => round($balanceQty),
                            'booking_qty' => round($bookingQty),
                            'previous_receive_qty' => round($totalPreviousQty),
                            'previous_return_qty' => round($totalReturnQty),
                            'rate' => round($detail->rate, 2),
                            'amount' => (float)number_format(($balanceQty) * $detail->rate, 4),
                            'reject_qty' => null,
                            'fabric_shade' => null,
                            'no_of_roll' => null,
                            'grey_used' => null,
                            'store_id' => null,
                            'floor_id' => null,
                            'room_id' => null,
                            'rack_id' => null,
                            'shelf_id' => null,
                            'remarks' => null,
                            'booking_no' => $detail->booking->unique_id,
                            'booking_id' => $detail->booking->id,
                            'is_used' => count($previousReceive->where('receive_id', $request->query('fabric_receive_id'))) > 0,
                        ];
                    });
                });
            })
//            ->filter(function ($detail) {
//                return $detail['balance_qty'] > 0;
//            })
            ->values();
    }
}
