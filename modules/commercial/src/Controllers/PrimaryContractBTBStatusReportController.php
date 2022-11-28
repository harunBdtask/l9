<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Commercial\Exports\PrimaryContractBTBStatusReportExcel;
use SkylarkSoft\GoRMG\Commercial\Services\Report\BTBStatusReportService;

class PrimaryContractBTBStatusReportController extends Controller
{
    public function view(Request $request)
    {
        $data = BTBStatusReportService::fetchData($request);
        return view('commercial::btb-status-report.view', compact('data'));
    }

    public function getExcel(Request $request)
    {
        $data = BTBStatusReportService::fetchData($request);
        return Excel::download(new PrimaryContractBTBStatusReportExcel($data), 'primary_contract_btb_status_report.xlsx');
    }
}
