<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;

class FinishFabricReceiveReportService
{
    public function getReportData(Request $request)
    {
        $fromDate = Carbon::make($request->get('from_date', date('Y-m-d')))->format('Y-m-d');
        $toDate = Carbon::make($request->get('to_date', date('Y-m-d')))->format('Y-m-d');
        $buyer = $request->get('buyer');
        $style = $request->get('style');
        $color = $request->get('color');

        return FabricReceive::query()
            ->with([
                'details.receive',
            ])
            //            ->when($search, function (Builder $query) use ($search) {
            //                $query->where('receive_no', 'LIKE', '%' . $search . '%')
            //                    ->orWhere('receive_challan', 'LIKE', '%' . $search . '%')
            //                    ->orWhere('lc_sc_no', 'LIKE', '%' . $search . '%')
            //                    ->orWhereHas('details', function ($query) use ($search) {
            //                        $query->whereHas('buyer', function ($query) use ($search) {
            //                            $query->where('name', 'LIKE', '%' . $search . '%');
            //                        });
            //                        $query->orWhereHas('fabricColor', function ($query) use ($search) {
            //                            $query->where('name', 'LIKE', '%' . $search . '%');
            //                        })
            //                            ->orWhere('style_name', 'LIKE', '%' . $search . '%')
            //                            ->orWhere('po_no', 'LIKE', '%' . $search . '%')
            //                            ->orWhere('batch_no', 'LIKE', '%' . $search . '%');
            //                    });
            //            })
            ->when($buyer, function (Builder $query) use ($buyer) {
                return $query->whereHas('details', function ($query) use ($buyer) {
                    return $query->where('buyer_id', $buyer);
                });
            })
            ->when($style, function (Builder $query) use ($style) {
                return $query->whereHas('details', function ($query) use ($style) {
                    return $query->where('style_name', $style);
                });
            })
            ->when($color, function (Builder $query) use ($color) {
                return $query->whereHas('details', function ($query) use ($color) {
                    return $query->where('color_id', $color);
                });
            })
            ->whereBetween('receive_date', [$fromDate, $toDate])
            ->get()
            ->flatmap(function ($item) {
                return $item->details->map(function ($details) use ($item) {

                    $bookingId = $item->receivable_id;
                    $actualReqQty = FabricBookingDetailsBreakdown::query()->where([
                        'booking_id' => $bookingId,
                        'garments_item_id' => $details->gmts_item_id,
                        'body_part_id' => $details->body_part_id,
                        'color_type_id' => $details->color_type_id,
                        'construction' => $details->construction,
                        'uom' => $details->uom_id,
                        'fabric_composition_id' => $details->fabric_composition_id,
                        'dia_type' => $details->dia_type,
                        'dia' => $details->dia,
                        'color_id' => $details->color_id,
                        'style_name' => $details->style_name,
                    ])->sum('actual_wo_qty');

                    return [
                        'receive_unique_id' => $item->receive_no,
                        'challan_no' => $item->receive_challan,
                        'ch_rcv_date' => $item->receive_date,
                        'buyer' => $details->buyer->name,
                        'style_order_no' => $details->style_name,
                        'po_no' => $details->po_no,
                        'batch_no' => $details->batch_no,
                        'fin_dia' => $details->ac_dia,
                        'gsm' => $details->ac_gsm,
                        'feb_type' => $details->construction,
                        'color' => $details->fabricColor->name,
                        'booking_act_req_qty' => $actualReqQty,
                        'no_of_roll' => $details->no_of_roll,
                        'rcv_fin_fab' => $details->receive_qty,
                        'rate' => $details->rate,
                        'value' => $details->amount,
                        'location' => $details->receive->factory_location,
                        'pi_no' => $item->lc_sc_no,
                        'pi_offer_date' => $item->pi_offer_date,
                        'remarks' => $details->remarks,
                    ];
                });
            })->groupBy('style_order_no');
    }
}
