<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnGatePass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use SkylarkSoft\GoRMG\Inventory\Models\YarnGatePassChallanScan;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssue;

class YarnGatePassChallanScanController extends Controller
{
    public function index(Request $request)
    {
        return view('inventory::yarns.yarn-gate-pass-challan-scan.index');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        $yarnGatePass = YarnGatePassChallanScan::query()->where('issue_no', $search)->first();
        $yarnIssueCheck = YarnIssue::query()
            ->with([
                'loanParty'
            ])
            ->where('issue_no', $search)->first();

        if (!$yarnIssueCheck) {
            throw new Exception("Data Not Found");
        }

        if ($yarnGatePass) {
            throw new Exception("Already Gate Pass Challan Scan");
        } else {
            $yarnIssue = YarnIssue::query()
                ->with([
                    'loanParty'
                ])
                ->where('issue_no', $search)->first();
        }

        return view('inventory::yarns.yarn-gate-pass-challan-scan.table', [
            'search' => $search,
            'yarnIssue' => $yarnIssue ?? ''
        ]);

    }

    public function store(Request $request)
    {
        $gatePass = new YarnGatePassChallanScan();
        $gatePass->yarn_issue_id = $request->get('yarn_issue_id');
        $gatePass->issue_no = $request->get('issue_no');
        $gatePass->challan_no = $request->get('challan_no');
        $gatePass->challan_date = $request->get('issue_date');
        $gatePass->supplier_id = $request->get('party_name');
        $gatePass->gate_pass_no = $request->get('gate_pass_no');
        $gatePass->vehicle_number = $request->get('vehicle_number');
        $gatePass->lock_no = $request->get('lock_no');
        $gatePass->driver_name = $request->get('driver_name');
        $gatePass->save();

        return response()->json([
            'message' => 'Challan Scan Successfully'
        ]);

    }

    public function show()
    {
        $gatePassChallan = YarnGatePassChallanScan::query()->with([
            'supplier'
        ])->latest()->paginate();

        return view('inventory::yarns.yarn-gate-pass-challan-scan.list', [
            'gatePassChallan' => $gatePassChallan,
        ]);

    }


}
