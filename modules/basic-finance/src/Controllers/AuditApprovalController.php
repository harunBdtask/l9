<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\FundRequisitionDetail;
use SkylarkSoft\GoRMG\BasicFinance\Models\FundRequisitionAuditApproval;

class AuditApprovalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('type') === 'unapproved') {
            $approvals = FundRequisitionDetail::query()->orderBy('date', 'desc')
                ->where("approval_status", 0)->get();
        } else {
            $approvals = FundRequisitionAuditApproval::query()->orderBy('audit_date', 'desc')
                ->paginate($request->get('row'));
        }

        return view('basic-finance::audit_approval.index', compact('approvals'));
    }

    public function create(Request $request)
    {
        $requisitionDetails = [];
        $requisitionNo = $request->get('requisition_no');
        if ($requisitionNo) {
            $requisitionDetails = FundRequisitionDetail::query()->where("approval_status", 0)
                ->whereRelation('requisition', 'requisition_no', $requisitionNo)->get();
        }

        return view('basic-finance::audit_approval.create', compact('requisitionDetails'));
    }

    public function store(Request $request): RedirectResponse
    {
        $approved = [];
        if (!empty($request->input('check'))) {
            foreach ($request->input('check') as $key => $value) {
                $detail = FundRequisitionDetail::query()->findOrFail($key);
                $detail['approval_status'] = 1;
                $detail->save();
                $approved[] = [
                    "audit_date" => date('Y-m-d'),
                    "requisition_id" => $detail['requisition_id'],
                    "detail_id" => $key,
                    "comment" => $request->input('comment')[$key]
                ];
            }
            FundRequisitionAuditApproval::query()->insert($approved);
        }

        return redirect()->back();
    }

    public function view($id, Request $request)
    {
        $approval = FundRequisitionAuditApproval::query()->where([
            'requisition_id' => $id, 'audit_date' => $request->get('date')
        ])->get();

        return view('basic-finance::audit_approval.view', compact('approval'));
    }

    public function print($id, Request $request): Response
    {
        $approval = FundRequisitionAuditApproval::query()->where([
            'requisition_id' => $id, 'audit_date' => $request->get('date')
        ])->get();
        $pdf = PDF::loadView('basic-finance::audit_approval.print', compact('approval'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('audit_approval.pdf');
    }
}
