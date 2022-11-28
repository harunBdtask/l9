<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;

class FinishFabricStockReportService
{
    public function getReportData(Request $request)
    {
        $fromDate = $request->form_date ? Carbon::make($request->form_date)->format('Y-m-d') : date('Y-m-d');
        $toDate = $request->to_date ? Carbon::make($request->to_date)->format('Y-m-d') : date('Y-m-d');
        $buyer = $request->get('buyer');
        $style = $request->get('style');
        $color = $request->get('color');

        $reportData = FabricReceiveDetail::query()
            ->with([
                'receive.booking',
                'issueDetails.issue.serviceCompany',
                'floor',
                'room',
                'rack',
                'orderStyle.createdBy',
            ])
            //            ->whereHas('receive', function ($receive) use ($search, $fromDate, $toDate) {
            //                $receive->when($search, function (Builder $query) use ($search) {
            //                    $query->where('receive_no', 'LIKE', '%' . $search . '%')
            //                        ->orWhere('receive_challan', 'LIKE', '%' . $search . '%')
            //                        ->orWhere('lc_sc_no', 'LIKE', '%' . $search . '%')
            //                        ->orWhereHas('details', function ($query) use ($search) {
            //                            $query->whereHas('buyer', function ($query) use ($search) {
            //                                $query->where('name', 'LIKE', '%' . $search . '%');
            //                            });
            //                            $query->orWhereHas('fabricColor', function ($query) use ($search) {
            //                                $query->where('name', 'LIKE', '%' . $search . '%');
            //                            })
            //                                ->orWhere('style_name', 'LIKE', '%' . $search . '%')
            //                                ->orWhere('po_no', 'LIKE', '%' . $search . '%')
            //                                ->orWhere('batch_no', 'LIKE', '%' . $search . '%');
            //                        });
            //                })->whereBetween('receive_date', [$fromDate, $toDate]);
            //            })
            ->when($buyer, function (Builder $query) use ($buyer) {
                return $query->where('buyer_id', $buyer);
            })
            ->when($style, function (Builder $query) use ($style) {
                return $query->where('style_name', $style);
            })
            ->when($color, function (Builder $query) use ($color) {
                return $query->where('color_id', $color);
            })
            ->whereBetween('receive_date', [$fromDate, $toDate])
            ->get();

        $bookingIds = $reportData->pluck('receivable_id')->unique();
        $garmentsItems = $reportData->pluck('gmts_item_id')->unique();
        $bodyParts = $reportData->pluck('body_part_id')->unique();
        $colorTypes = $reportData->pluck('color_type_id')->unique();
        $constructions = $reportData->pluck('construction')->unique();
        $uoms = $reportData->pluck('uom_id')->unique();
        $fabricCompositions = $reportData->pluck('fabric_composition_id')->unique();
        $diaTypes = $reportData->pluck('dia_type')->unique();
        $dias = $reportData->pluck('dia')->unique();
        $colors = $reportData->pluck('color_id')->unique();
        $styles = $reportData->pluck('style_name')->unique();

        $actualWOQtys = FabricBookingDetailsBreakdown::query()
            ->whereIn('booking_id', $bookingIds)
            ->whereIn('garments_item_id', $garmentsItems)
            ->whereIn('body_part_id', $bodyParts)
            ->whereIn('color_type_id', $colorTypes)
            ->whereIn('construction', $constructions)
            ->whereIn('uom', $uoms)
            ->whereIn('fabric_composition_id', $fabricCompositions)
            ->whereIn('dia_type', $diaTypes)
            ->whereIn('dia', $dias)
            ->whereIn('color_id', $colors)
            ->whereIn('style_name', $styles)
            ->get();

        $reportData = $reportData->map(function ($details) use ($actualWOQtys) {
            //            dd($details);
            $bookingId = $details->receivable_id;

            $delivery_statement = [];
            foreach ($details->issueDetails as $fabricIssueDetails) {
                $delivery_statement[] = [
                    'supplier_name' => $fabricIssueDetails->issue->serviceCompany->name ?? '',
                    'sys_unique_id' => $fabricIssueDetails->unique_id,
                    'dlv_ch_no' => $fabricIssueDetails->issue->challan_no,
                    'dlv_challan_date' => $fabricIssueDetails->issue->issue_date,
                    'finish_delivery_qty' => $fabricIssueDetails->issue_qty,
                ];
            }

            $location = [
                $details->room->name,
                $details->rack->name,
            ];

            $actualWorkOrderQty = $actualWOQtys
                ->where('booking_id', $bookingId)
                ->where('garments_item_id', $details->gmts_item_id)
                ->where('body_part_id', $details->body_part_id)
                ->where('color_type_id', $details->color_type_id)
                ->where('construction', $details->construction)
                ->where('uom', $details->uom_id)
                ->where('fabric_composition_id', $details->fabric_composition_id)
                ->where('dia_type', $details->dia_type)
                ->where('dia', $details->dia)
                ->where('color_id', $details->color_id)
                ->where('style_name', $details->style_name)
                ->sum('actual_wo_qty');

            $rateSum = $actualWOQtys
                ->where('booking_id', $bookingId)
                ->where('garments_item_id', $details->gmts_item_id)
                ->where('body_part_id', $details->body_part_id)
                ->where('color_type_id', $details->color_type_id)
                ->where('construction', $details->construction)
                ->where('uom', $details->uom_id)
                ->where('fabric_composition_id', $details->fabric_composition_id)
                ->where('dia_type', $details->dia_type)
                ->where('dia', $details->dia)
                ->where('color_id', $details->color_id)
                ->where('style_name', $details->style_name)
                ->sum('rate');

            return [
                'merchandiser' => $details->orderStyle->createdBy->screen_name,
                'receive_unique_id' => $details->receive->receive_no,
                'challan_no' => $details->receive->receive_challan,
                'ch_rcv_date' => $details->receive->receive_date,
                'buyer' => $details->buyer->name,
                'style_order_no' => $details->style_name,
                'po_no' => $details->po_no,
                'batch_no' => $details->batch_no,
                'fin_dia' => $details->dia,
                'gsm' => $details->gsm,
                'feb_type' => $details->construction,
                'color' => $details->fabricColor->name,
                'booking_act_req_qty' => $actualWorkOrderQty,
                'no_of_roll' => $details->no_of_roll,
                'rcv_fin_fab' => $details->receive_qty,
                'rate' => $rateSum,
                'pi_no' => $details->receive->lc_sc_no,
                'pi_offer_date' => $details->receive->pi_offer_date,
                'delivery_statement' => $delivery_statement,
                'fab_val_qty' => '',
                'stock_qty' => '',
                'value' => '',
                'remarks' => $details->remarks,
                'in_house_ageing' => Carbon::make($details->receive->receive_date)->diffInDays(Carbon::now()),
                'location' => $location,
                'bom_status' => $details->receive->booking->booking_date,
                'bom_age' => '',
                'floor' => $details->floor->name,
            ];
        })->groupBy('style_order_no');

        return $reportData;
    }
}
