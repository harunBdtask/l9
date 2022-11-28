<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssue;

class FinishFabricIssueReportService
{
    public function getReportData(Request $request)
    {
        $formDate = Carbon::make($request->get('form_date', date('Y-m-d')))->format('Y-m-d');
        $toDate = Carbon::make($request->get('to_date', date('Y-m-d')))->format('Y-m-d');
        $buyer = $request->get('buyer');
        $style = $request->get('style');
        $color = $request->get('color');

        return  FabricIssue::query()
            ->with([
                'details'
            ])
            ->with(['details' => function ($query) use ($color) {
                return $query->when($color, function ($query) use ($color) {
                    return $query->where('color_id', $color);
                });
            }])
//            ->when($search, function (Builder $query) use ($search) {
//                $query->where('issue_no', 'LIKE', '%' . $search . '%')
//                    ->orWhere('challan_no', 'LIKE', '%' . $search . '%')
//                    ->orWhereHas('serviceCompany', function ($query) use ($search) {
//                        $query->where('name', 'LIKE', '%' . $search . '%');
//                    })
//                    ->orWhereHas('details', function ($query) use ($search) {
//                        $query->whereHas('buyer', function ($query) use ($search) {
//                            $query->where('name', 'LIKE', '%' . $search . '%');
//                        });
//                        $query->orWhereHas('color', function ($query) use ($search) {
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
            ->whereBetween('issue_date', [$formDate, $toDate])
            ->get()
            ->flatMap(function ($item) {
                return $item->details->map(function ($detail) use ($item) {
                    return [
                        'delivery_unique_id' => $item->issue_no,
                        'challan_no' => $item->challan_no,
                        'issue_date' => $item->issue_date,
                        'supplier_name' => $item->serviceCompany->name,
                        'store_name' => $detail->store->name,
                        'buyer_name' => $detail->buyer->name,
                        'style_no' => $detail->style_name,
                        'po_no' => $detail->po_no,
                        'batch_no' => $detail->batch_no,
                        'feb_type' => $detail->construction,
                        'color' => $detail->color->name,
                        'dia' => $detail->dia,
                        'gsm' => $detail->gsm,
                        'no_of_roll' => $detail->no_of_roll,
                        'dlv_fin_qty' => $detail->issue_qty,
                        'rate' => $detail->rate,
                        'amount' => $detail->amount,
                        'remarks' => $detail->remarks,

                    ];
                });
            })->groupBy('style_no');

    }
}
