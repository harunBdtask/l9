<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssueReturn;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssue;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\BalanceQty;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueReturnDetail;

class FabricManualIssueReturn implements FabricIssueReturnContracts
{
    public function handle(FabricIssueReturnStrategy $strategy)
    {
        $request = $strategy->getRequest();
        $type = $request->get('type');
        $code = $request->get('code');
        $firstDate = $request->get('first_date');
        $lastDate = $request->get('last_date');

        return FabricIssue::query()->has('details')
            ->withSum('details', 'issue_qty')
            ->with([
                'details' => function ($query) use ($type, $code) {
                    $query->when($type === 'batch_no', function ($query) use ($code) {
                        $query->where('batch_no', $code);
                    })->when($type === 'style_name', function ($query) use ($code) {
                        $query->where('style_name', $code);
                    });
                },
                'buyer'
            ])
            ->where('status', FabricIssue::APPROVE)
            ->when($firstDate && $lastDate, function ($query) use ($firstDate, $lastDate) {
                $query->whereBetween('issue_date', [$firstDate, $lastDate]);
            })->when($type === 'issue_no', function ($query) use ($code) {
                $query->where('issue_no', $code);
            })->when($type === 'challan_no', function ($query) use ($code) {
                $query->where('challan_no', $code);
            })->get()->map(function ($issue) {
                $styleName = collect($issue->details)->pluck('style_name')->unique()->join(' ,');
                $batchNo = collect($issue->details)->pluck('batch_no')->unique()->join(' ,');

                if (count($issue->details)) {
                    return [
                        'mrr_no' => $issue->issue_no,
                        'year' => Carbon::create($issue->issue_date)->format('Y'),
                        'batch_no' => $batchNo,
                        'buyer_id' => $issue->buyer_id,
                        'buyer_name' => $issue->buyer->name,
                        'style_name' => $styleName,
                        'unique_id' => $issue->issue_no,
                        'challan_no' => $issue->challan_no,
                        'issue_date' => $issue->issue_date,
                        'issue_purpose' => $issue->issue_purpose,
                        'total_issue_qty' => $issue->details_sum_issue_qty,
                        'details' => $issue->details->map(function ($detail) use ($issue) {
                            $previousIssueReturnQty = FabricIssueReturnDetail::query()
                                                          ->where('unique_id', $detail->unique_id)
                                                          ->first()['return_qty'] ?? 0;

                            $balanceQty = (new BalanceQty)->balance($detail);

                            return [
                                'unique_id' => $detail->unique_id,
                                'fabric_issue_detail_id' => $detail->id,
                                'issue_no' => $issue->issue_no,
                                'buyer_id' => $detail->buyer_id,
                                'style_id' => $detail->style_id,
                                'style_name' => $detail->style_name,
                                'po_no' => $detail->po_no,
                                'batch_no' => $detail->batch_no,
                                'gmts_item_id' => $detail->gmts_item_id,
                                'gmts_item_name' => $detail->gmtsItem->name,
                                'body_part_id' => $detail->body_part_id,
                                'body_part_value' => $detail->bookingDetail->body_part_value,
                                'fabric_description' => $detail->fabric_description,
                                'fabric_composition_id' => $detail->fabric_composition_id,
                                'construction' => $detail->construction,
                                'dia' => $detail->dia,
                                'ac_dia' => $detail->ac_dia,
                                'gsm' => $detail->gsm,
                                'ac_gsm' => $detail->ac_gsm,
                                'dia_type' => $detail->dia_type,
                                'ac_dia_type' => $detail->ac_dia_type,
                                'color_id' => $detail->color_id,
                                'color_name' => $detail->color->name,
                                'color_type_id' => $detail->color_type_id,
                                'contrast_color_id' => $detail->contrast_color_id,
                                'contrast_color_name' => null,
                                'uom_id' => $detail->uom_id,
                                'uom_name' => $detail->uom->unit_of_measurement,
                                'reject_qty' => $detail->reject_qty,
                                'return_qty' => null,
                                'rate' => $detail->rate,
                                'amount' => null,
                                'fabric_shade' => $detail->fabric_shade,
                                'no_of_roll' => $detail->no_of_roll,
                                'store_id' => null,
                                'floor_id' => $detail->floor_id,
                                'floor_name' => $detail->floor->name,
                                'room_id' => $detail->room_id,
                                'room_name' => $detail->room->name,
                                'rack_id' => $detail->rack_id,
                                'rack_name' => $detail->rack->name,
                                'shelf_id' => $detail->shelf_id,
                                'shelf_name' => $detail->shelf->name,
                                'remarks' => null,
                                'issue_qty' => $detail->issue_qty,
                                'previous_issue_return_qty' => $previousIssueReturnQty,
                                'yet_to_return' => $balanceQty - $previousIssueReturnQty,
                                'balance_qty' => $balanceQty,
                                'issue_return_type' => 'manual',
                            ];
                        })
                    ];
                } else {
                    return false;
                }
            })->reject(function ($issue) {
                return $issue === false;
            });
    }

    public function store(FabricIssueReturnStrategy $strategy)
    {
        $request = $strategy->getRequest();
        $fabricIssueReturn = $strategy->getIssueReturnModel();

        $fabricIssueReturnDetail = $fabricIssueReturn->details()->findOrNew($request->id ?? null);
        $fabricIssueReturnDetail->fill($request->all())->save();

        return $fabricIssueReturnDetail;
    }
}
