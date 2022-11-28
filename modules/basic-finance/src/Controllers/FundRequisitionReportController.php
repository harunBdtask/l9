<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\BasicFinance\Models\FundRequisition;

class FundRequisitionReportController extends Controller
{
    public function index(Request $request)
    {
        $requisitions = FundRequisition::query()->orderByDesc('requisition_date')->paginate(20);

        return view('basic-finance::fund_requisition.report.index', compact('requisitions'));
    }

    public function view($id)
    {
        $requisition = FundRequisition::query()
            ->with(['details', 'auditApproved', 'acApproved.detail'])
            ->findOrFail($id);

        $requisition = [
            'id' => $requisition->id,
            'unit_id' => $requisition->unit_id,
            'requisition_no' => $requisition->requisition_no,
            'requisition_date' => $requisition->requisition_date,
            'name' => $requisition->name,
            'designation' => $requisition->designation,
            'expect_receive_date' => $requisition->expect_receive_date,
            'is_approved' => $requisition->is_approved,
            'details' => $this->formatFundRequisitionDetails($requisition->details),
            'auditApproved' => $this->formatAuditApproves($requisition->auditApproved),
            'acApproved' => $this->formatAccountApproves($requisition->acApproved),
        ];

        return view("basic-finance::fund_requisition.report.view", compact('requisition'));
    }

    public function formatFundRequisitionDetails($details): Collection
    {
        return collect($details)->map(function ($detail) {
            return $detail;
        });
    }

    public function formatAuditApproves($details): Collection
    {
        return collect($details)->map(function ($auditApproved) {
            return $auditApproved;
        });
    }

    public function formatAccountApproves($details): Collection
    {
        return collect($details)->groupBy('date')->map(function ($acApproved) {
            return [
                'requisition_id' => $acApproved->first()->requisition_id,
                'detail_id' => $acApproved->first()->detail_id,
                'date' => $acApproved->first()->date,
                'existing_qty' => $acApproved->first()->existing_qty,
                'req_qty' => $acApproved->first()->req_qty,
                'approved_qty' => $acApproved->sum('approved_qty'),
                'rate' => $acApproved->avg('rate'),
                'amount' => $acApproved->sum('amount'),
                'remarks' => $acApproved->first()->remarks,
                'detail' => $acApproved->first()->detail,
            ];
        });
    }
}
