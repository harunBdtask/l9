<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\FundRequisitionDetail;
use SkylarkSoft\GoRMG\BasicFinance\Models\FundRequisitionAuditApproval;
use SkylarkSoft\GoRMG\BasicFinance\Models\FundRequisitionAccountApproval;

class AccountApprovalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('type') === 'unapproved') {
            $approvals = FundRequisitionAuditApproval::query()
                ->orderBy('audit_date', 'desc')
                ->whereRelation("detail", "approval_status", '>=', 1)
                ->get()->groupBy(['audit_date', 'requisition_id']);
        } else {
            $approvals = FundRequisitionAccountApproval::query()->orderBy('date', 'desc')
                ->paginate($request->get('row'));
        }

        return view('basic-finance::account_approval.index', compact('approvals'));
    }

    public function create(Request $request)
    {
        $requisitionDetails = [];
        $requisitionNo = $request->get('requisition_no');
        if ($requisitionNo) {
            $requisitionDetails = FundRequisitionDetail::query()
                ->where("approval_status", '>=', 1)
                ->whereRelation('requisition', 'requisition_no', $requisitionNo)->get();
        }

        return view('basic-finance::account_approval.create', compact('requisitionDetails'));
    }

    public function store(Request $request): RedirectResponse
    {
        $approved = [];
        if (!empty($request->input('check'))) {
            foreach ($request->input('check') as $key => $value) {
                $detail = FundRequisitionDetail::query()->findOrFail($key);
                $detail['approval_status'] = 2;
                $detail->save();
                $approved[] = [
                    "requisition_id" => $detail['requisition_id'],
                    "detail_id" => $key,
                    "date" => date('Y-m-d'),
                    "approved_qty" => $request->input('appr_qty')[$key],
                    "rate" => $request->input('appr_rate')[$key],
                    "amount" => $request->input('appr_qty')[$key] * $request->input('appr_rate')[$key],
                    "remarks" => $request->input('appr_remarks')[$key],
                    "created_at" => date('Y-m-d h:i:s'),
                ];
            }
            FundRequisitionAccountApproval::query()->insert($approved);
        }

        return redirect()->back();
    }

    public function view($id, Request $request)
    {
        $approval = FundRequisitionAccountApproval::query()->where([
            'requisition_id' => $id, 'date' => $request->get('date')
        ])->get();

        return view('basic-finance::account_approval.view', compact('approval'));
    }

    public function print($id, Request $request): Response
    {
        $approval = FundRequisitionAccountApproval::query()->where([
            'requisition_id' => $id, 'date' => $request->get('date')
        ])->get();
        $pdf = PDF::loadView('basic-finance::account_approval.print', compact('approval'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('account_approval.pdf');
    }
}
