<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\Reports;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventoryDetail;

class MonthlyStockUpReportService
{
    public function generateReport(Request $request)
    {
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        return TrimsInventoryDetail::query()
            ->with([
                'itemGroup',
                'trimsInventory.booking.supplier:id,name',
                'trimsInventory.buyer:id,name',
                'receiveDetails.trimsStoreReceive',
                'receiveDetails.mrrDetails.binCardDetails.issueDetails.issue',
            ])->get()->map(function ($inventoryDetail) use ($fromDate, $toDate) {
                $receiveDetails = $inventoryDetail->receiveDetails
                    ->whereNotNull('receive_date')
                    ->sortBy('receive_date')
                    ->values();

                $receiveChallanNos = $inventoryDetail->receiveDetails
                    ->pluck('trimsStoreReceive.challan_no')
                    ->join(', ');

                $issueDetails = $inventoryDetail->receiveDetails
                    ->pluck('mrrDetails')
                    ->flatten(1)
                    ->pluck('binCardDetails')
                    ->flatten(1)
                    ->pluck('issueDetails')
                    ->flatten(1);

                $issueChallanNo = $issueDetails->pluck('issue.challan_no')->join(', ');

                $openingReceiveDetails = $receiveDetails->where('receive_date', '<', $fromDate);

                $openingIssueDetails = $issueDetails->whereNotNull('issue_date')
                    ->where('issue_date', '<', $fromDate);

                $openingQty = $openingReceiveDetails->sum('receive_qty') - $openingIssueDetails->sum('issue_qty');

                $dayReceiveQty = $receiveDetails->whereBetween('receive_date', [$fromDate, $toDate])
                    ->sum('receive_qty');

                $receiveRate = $receiveDetails->whereBetween('receive_date', [$fromDate, $toDate])
                    ->avg('rate');

                $receiveAmount = $receiveDetails->whereBetween('receive_date', [$fromDate, $toDate])
                    ->sum('total_receive_amount');

                $availableQty = $openingQty + $receiveDetails->sum('receive_qty');

                $dayIssueQty = $issueDetails->whereBetween('issue_date', [$fromDate, $toDate])
                    ->sum('issue_qty');

                $closingQty = $availableQty - $issueDetails->sum('issue_qty');

                return [
                    'buyer' => $inventoryDetail->trimsInventory->buyer->name,
                    'style_name' => $inventoryDetail->trimsInventory->style_name,
                    'po_no' => null,
                    'item_name' => $inventoryDetail->itemGroup->item_group,
                    'item_description' => $inventoryDetail->item_description,
                    'supplier_name' => $inventoryDetail->trimsInventory->booking->supplier->name,
                    'order_qty' => null,
                    'booking_qty' => $inventoryDetail->booking_qty,
                    'rate' => $inventoryDetail->rate,
                    'booking_amount' => format($inventoryDetail->booking_qty * $inventoryDetail->rate, 4),
                    'pl_no' => null,
                    'lc_no' => $inventoryDetail->trimsInventory->lc_no,
                    'lc_date' => $inventoryDetail->trimsInventory->lc_receive_date,
                    'receive_challan_no' => $receiveChallanNos,
                    'first_receive_challan_date' => $receiveDetails->first()['receive_date'] ?? null,
                    'last_receive_challan_date' => $receiveDetails->last()['receive_date'] ?? null,
                    'life_end_date' => null,
                    'duration_of_life' => null,
                    'not_earlier' => null,
                    'not_later' => null,
                    'on_time' => null,
                    'delayed' => null,
                    'mrr_no' => null,
                    'mrr_date' => null,
                    'issue_challan_no' => $issueChallanNo,
                    'issue_challan_date' => null,
                    'storage_location' => null,
                    'name_of_unit' => null,
                    'opening_qty' => $openingQty,
                    'opening_rate' => $openingReceiveDetails->avg('rate'),
                    'opening_amount' => $openingReceiveDetails->sum('total_receive_amount'),
                    'day_receive_qty' => $dayReceiveQty,
                    'total_receive_qty' => $receiveDetails->sum('receive_qty'),
                    'receive_rate' => $receiveRate,
                    'receive_amount' => $receiveAmount,
                    'available_qty' => $availableQty,
                    'available_rate' => $receiveRate,
                    'available_amount' => format($availableQty * $receiveRate, 4),
                    'day_issue_qty' => $dayIssueQty,
                    'total_issue_qty' => $issueDetails->sum('issue_qty'),
                    'issue_rate' => $receiveRate,
                    'issue_amount' => format($dayIssueQty * $receiveRate, 4),
                    'closing_qty' => $closingQty,
                    'closing_rate' => $receiveRate,
                    'closing_amount' => format($closingQty * $receiveRate, 4),
                ];
            });
    }
}
