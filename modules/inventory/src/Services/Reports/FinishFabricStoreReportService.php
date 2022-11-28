<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\Reports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;

class FinishFabricStoreReportService
{
    protected $fromDate, $toDate, $buyer, $style;

    public function __construct(Request $request)
    {
        $this->fromDate = Carbon::make($request->get('from_date'))->format('Y-m-d');
        $this->toDate = Carbon::make($request->get('to_date'))->format('Y-m-d');
        $this->buyer = $request->get('buyer');
        $this->style = $request->get('style');
    }

    public function report()
    {
        $receives = FabricReceive::query()
            ->with([
                'details.buyer',
                'details.color',
                'details.body',
                'details.uom',
                'details.receiveReturnDetails',
            ])
            ->when($this->buyer, function ($query) {
                $query->whereHas('details', function ($details) {
                    $details->where('buyer_id', $this->buyer);
                });
            })
            ->when($this->style, function ($query) {

                $query->whereHas('details', function ($details) {
                    $details->where('style_name', $this->style);
                });
            })
            ->whereBetween('receive_date', [$this->fromDate, $this->toDate])
            ->get()
            ->map(function ($receive) {

                return $receive->details->map(function ($detail) use ($receive) {

                    $booking = collect($receive->bookingDetailsBreakdown)
                        ->where('garments_item_id', $detail->gmts_item_id)
                        ->where('body_part_id', $detail->body_part_id)
                        ->where('fabric_composition_id', $detail->fabric_composition_id)
                        ->where('gsm', $detail->gsm)
                        ->where('color_id', $detail->color_id)
                        ->first();

                    return [
                        "batch_no" => $detail->batch_no,
                        "buyer" => $detail->buyer->name,
                        "uniq_id" => $detail->unique_id,
                        "style" => $detail->style_name,
                        "fabric_type" => $detail->fabric_description,
                        "color" => $detail->color->name,
                        "part" => $detail->body->name,
                        "unique_id" => $detail->unique_id,
                        "gmts_item_id" => $detail->gmts_item_id,
                        "body_part_id" => $detail->body_part_id,
                        "fabric_composition_id" => $detail->fabric_composition_id,
                        "gsm" => $detail->gsm,
                        "color_id" => $detail->color_id,
                        "receive_date" => $receive->receive_date ?? null,
                        "booking_qty" => $booking['actual_wo_qty'] ?? 0,
                        "receive_qty" => $detail->receive_qty ?? 0,
                        "uom" => 'Kg',
                        "receiveReturnDetails" => $detail->receiveReturnDetails ?? [],
                    ];
                });
            })->collapse();

        $issues = FabricIssueDetail::query()
            ->with('issueReturnDetails')
            ->whereHas('issue', function (Builder $query) {
                return $query->whereBetween('issue_date', [$this->fromDate, $this->toDate]);
            })
            ->get()
            ->map(function ($issueDetail) {
                $issueDetail['issue_date'] = $issueDetail->issue->issue_date ?? null;
                return $issueDetail;
            });

        return $receives->groupBy(['buyer', 'uniq_id', 'style', 'fabric_type', 'color', 'part', 'batch_no'])
            ->map(function ($buyerWise) use ($issues) {
                return $buyerWise->map(function ($uniqueWise) use ($issues) {
                    return $uniqueWise->map(function ($styleWise) use ($issues) {
                        return $styleWise->map(function ($fabricTypeWise) use ($issues) {
                            return $fabricTypeWise->map(function ($colorWise) use ($issues) {
                                return $colorWise->map(function ($partWise) use ($issues) {
                                return $partWise->map(function ($batchWise) use ($issues) {

                                    $prevReceiveValues = collect($batchWise)
                                        ->where('receive_date', '>', $this->fromDate)
                                        ->where('receive_date', '<', $this->toDate)
                                        ->values();

                                    $prevReceiveReturnValues = $prevReceiveValues->first()['receiveReturnDetails'] ?? [];

                                    $todayReceiveValues = collect($batchWise)
                                        ->where('receive_date', '=', $this->toDate)
                                        ->values();

                                    $issue = collect($issues)
                                            ->where('unique_id', collect($batchWise)->first()['uniq_id'])
                                            ->where('gmts_item_id', collect($batchWise)->first()['gmts_item_id'])
                                            ->where('body_part_id', collect($batchWise)->first()['body_part_id'])
                                            ->where('fabric_composition_id', collect($batchWise)->first()['fabric_composition_id'])
                                            ->where('gsm', collect($batchWise)->first()['gsm'])
                                            ->where('color_id', collect($batchWise)->first()['color_id']) ?? null;

                                    $prevIssueValues = $issue->where('issue_date', '>', $this->fromDate)
                                            ->where('issue_date', '<', $this->toDate)
                                            ->values() ?? null;

                                    $prevIssueReturnValues = $prevIssueValues->first()['issueReturnDetails'] ?? [];

                                    $todayIssueValues = $issue->where('issue_date', '=', $this->toDate)
                                            ->values() ?? null;

                                    $batchWise['booking_qty'] = collect($prevReceiveValues)->first()['booking_qty'] ?? 0;
                                    $batchWise['prev_receive_qty'] = collect($prevReceiveValues)->sum('receive_qty') ?? 0;
                                    $batchWise['prev_receive_return_qty'] = collect($prevReceiveReturnValues)->sum('return_qty') ?? 0;
                                    $batchWise['prev_issue_qty'] = collect($prevIssueValues)->sum('issue_qty') ?? 0;
                                    $batchWise['prev_issue_return_qty'] = collect($prevIssueReturnValues)->sum('return_qty') ?? 0;
                                    $batchWise['today_receive_qty'] = collect($todayReceiveValues)->sum('receive_qty') ?? 0;
                                    $batchWise['today_issue_qty'] = collect($todayIssueValues)->sum('issue_qty') ?? 0;
                                    $batchWise['total_receive_qty'] = $batchWise['prev_receive_qty'] - $batchWise['prev_receive_return_qty'] + $batchWise['today_receive_qty'];
                                    $batchWise['total_issue_qty'] = $batchWise['prev_issue_qty'] - $batchWise['prev_issue_return_qty'] + $batchWise['today_issue_qty'];
                                    $batchWise['balance_receive_qty'] = $batchWise['booking_qty'] - $batchWise['total_receive_qty'];
                                    $batchWise['balance_issue_qty'] = $batchWise['total_receive_qty'] - $batchWise['total_issue_qty'];

                                    return $batchWise;
                                });
                                });
                            });
                        });
                    });
                });
            })->flatten(6);
    }
}
