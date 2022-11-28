<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricStockSummeryReport;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;

class FabricStockSummeryService
{
    public function reportData(Request $request)
    {
        $fromDate = $request->get('from_date') ?? Carbon::now()->firstOfMonth()->format('Y-m-d');
        $toDate = $request->get('to_date') ?? Carbon::today()->format('Y-m-d');
        $buyerId = $request->get('buyer');
        $styleName = $request->get('style');

        $fabricReceiveDetails = FabricReceiveDetail::query()
            ->with([
                'receive',
                'buyer',
                'color',
                'receiveReturnDetails',
            ])
            ->when($buyerId, function (Builder $builder) use ($buyerId) {
                $builder->where('buyer_id', $buyerId);
            })
            ->when($styleName, function (Builder $builder) use ($styleName) {
                $builder->where('style_name', $styleName);
            })
            ->whereBetween('receive_date', [$fromDate, $toDate])
            ->select(
                DB::raw('*, GROUP_CONCAT(id SEPARATOR ",") as all_ids,
                SUM(receive_qty) as total_receive_qty,
                SUM(amount) as total_receive_amount,
                AVG(rate) as avg_rate')
            )
            ->groupBy(['receive_date', 'buyer_id', 'style_name', 'po_no', 'color_id', 'batch_no'])
            ->get();

        $fabricIssueDetails = FabricIssueDetail::query()
            ->with('issueReturnDetails')
            ->when($buyerId, function (Builder $builder) use ($buyerId) {
                $builder->where('buyer_id', $buyerId);
            })
            ->when($styleName, function (Builder $builder) use ($styleName) {
                $builder->where('style_name', $styleName);
            })
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->select(
                DB::raw('*, SUM(issue_qty) as total_issue_qty,
                SUM(amount) as total_issue_amount')
            )
            ->groupBy(['buyer_id', 'style_name', 'po_no', 'color_id', 'batch_no'])
            ->get();

        return $fabricReceiveDetails->map(function ($detail) use ($fabricIssueDetails) {
            $receiveIds = explode(',', $detail->all_ids);
            $totalReceiveReturnQty = count($detail->receiveReturnDetails) > 0
                ? $detail->receiveReturnDetails->sum('return_qty')
                : 0;
            $issueDetails = $fabricIssueDetails->whereIn('fabric_receive_details_id', $receiveIds);
            $totalIssueQty = $issueDetails->sum('issue_qty');

            $totalIssueReturnQty = collect($issueDetails)->pluck('issueReturnDetails')->map(function ($collection) {
                return $collection->pluck('return_qty');
            })->flatten()->sum();

            $totalBalanceQty = ($detail->total_receive_qty - $totalReceiveReturnQty) - ($totalIssueQty - $totalIssueReturnQty);

            return [
                'id' => $detail->id,
                'receive_date' => $detail->receive->receive_date,
                'unique_id' => $detail->unique_id,
                'receivable_type' => $detail->receivable_type,
                'buyer_id' => $detail->buyer_id,
                'buyer_name' => $detail->buyer->name,
                'style_id' => $detail->style_id,
                'style_name' => $detail->style_name,
                'po_no' => $detail->po_no,
                'color_id' => $detail->color_id,
                'color_name' => $detail->color->name,
                'order_qty' => 0,
                'total_receive_qty' => $detail->total_receive_qty,
                'total_receive_return_qty' => $totalReceiveReturnQty,
                'avg_rate' => $detail->avg_rate,
                'remarks' => $detail->remarks,
                'total_receive_amount' => $detail->total_receive_amount,
                'total_issue_qty' => $totalIssueQty,
                'total_issue_return_qty' => $totalIssueReturnQty,
                'total_issue_amount' => $issueDetails->sum('amount'),
                'total_balance_qty' => $totalBalanceQty,
                'total_balance_amount' => $totalBalanceQty * $detail->avg_rate,
            ];
        })->groupBy('style_name');

    }
}
