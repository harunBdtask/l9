<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnIssue;

use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;

class DailyYarnIssueStatementService
{
    public function getData($request)
    {
        return YarnIssueDetail::query()
            ->with('issue', 'issue.buyer:id,name', 'yarn_count', 'composition', 'type',
                'requisition.program', 'requisition.program.planInfo', 'requisition.program.planInfo.order.purchaseOrders')
            ->when($request->get('yarn_lot'), Filter::applyFilter('yarn_lot', $request->get('yarn_lot')))
            ->whereHas('issue',function(Builder $builder) use($request){
                $builder->where('factory_id', $request->input('factory_id'));
                $builder->when($request->get('issue_no'), Filter::applyFilter('issue_no', $request->get('issue_no')));
                $builder->when($request->get('challan_no'), Filter::applyFilter('challan_no', $request->get('challan_no')));
                $builder->when($request->get('issue_basis'), Filter::applyFilter('issue_basis', $request->get('issue_basis')));
                $builder->when($request->get('loan_party_id'), Filter::applyFilter('loan_party_id', $request->get('loan_party_id')));
                $builder->when($request->get('from_date') && !$request->get('to_date'),
                    Filter::dateFilter('issue_date', $request->get('from_date'))
                );
                $builder->when($request->get('from_date') && $request->get('to_date'),
                    Filter::betweenFilter('issue_date', [$request->get('from_date'), $request->get('to_date')])
                );
            })
            ->get()
            ->map(function ($item) {
                $pi = $this->item($item);
                $purchaseOrdersCollection = collect($item->requisition->program->planInfo->order->purchaseOrders ?? []);
                return [
                    'buyer_name' => $item->issue->buyer->name ?? '',
                    'style_name' => $item->requisition->program->planInfo->style_name ?? '',
                    'po_no' => $purchaseOrdersCollection->pluck('po_no')->join(', '),
                    'booking_qty' => $item->requisition->program->planInfo->booking_qty ?? '',
                    'job_no' => $item->issue->buyer_job_no ?? '',
                    'pi_no' => $pi->yarnReceive->pi->pi_no ?? '',
                    'issue_date' => date("d-m-Y", strtotime($item->issue->issue_date)),
                    'issue_challan_no' => $item->issue->challan_no ?? '',
                    'knitting_party' => $item->requisition->program->party_name ?? '',
                    'fab_type' => '',
                    'yarn_count' => $item->yarn_count->yarn_count ?? '',
                    'yarn_composition' => $item->composition->yarn_composition ?? '',
                    'yarn_type' => $item->type->name ?? '',
                    'yarn_color' => $item->yarn_color,
                    'yarn_lot' => $item->yarn_lot,
                    'issue_qty' => $item->issue_qty,
                    'rate' => $item->rate,
                    'usd_value' => $item->issue_qty * $item->rate,
                    'pcd_date' => $item->requisition->program->planInfo->order->pcd_date ?? '',
                    'ex_factory_date' => $purchaseOrdersCollection->pluck('ex_factory_date')->unique()->values()->join(', '),
                ];
            });
    }

    private function item($yarn)
    {
        return YarnReceiveDetail::query()
            ->with(['supplier:id,name', 'yarnReceive.pi:id,pi_no,pi_receive_date'])
            ->where(YarnItemAction::itemCriteria($yarn))
            ->first();
    }
}
