<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService;

use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssue;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;

class DailyFinishFabricIssueMailReportService
{
    public function getReportData($date)
    {
        $fabricIssue = FabricIssue::query()
            ->with('details.order:id,job_no,pcd_date')
            ->whereDate('issue_date', $date)
            ->get();

        return $fabricIssue->flatMap(function ($item) {
            return $item->details->map(function ($detail) use ($item) {
                $totalIssue = FabricIssueDetail::query()
                    ->where('buyer_id', $detail->buyer_id)
                    ->where('unique_id', $detail->unique_id)
                    ->where('style_name', $detail->style_name)
                    ->sum('issue_qty');
                return [
                    'buyer_name' => $detail->buyer->name ?? '',
                    'reference_no' => $detail->unique_id,
                    'style_no' => $detail->style_name,
                    'po_no' => $detail->po_no,
                    'order_qty' => $detail->order->pq_qty_sum ?? 0,
                    'fab_cons' => null,
                    'issue_no' => $item->issue_no,
                    'issue_qty' => $detail->issue_qty,
                    'total_issue_qty' => $totalIssue,
                    'bal_issue_qty' => $totalIssue - $detail->issue_qty,
                    'ex_factory_date' => collect($detail->issue_qty_details)->first()['shipment_date'] ?? '',
                    'pcd_date' => $detail->order->pcd_date ?? '',
                ];
            });
        });
    }
}
