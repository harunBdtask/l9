<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceive\GoodReceiveWithLcOpenReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

class GoodReceivedWithLCOpenReportController extends Controller
{
    public function requiredData($request): array
    {
        $data['yarnReceive'] = YarnReceive::query()->get();

        $data['loanParty'] = Supplier::query()
            ->where('party_type', 'like', '%' . Supplier::LOAN_PARTY . '%')
            ->get();

        $data['piNos'] = $data['yarnReceive']->where('receive_basis', 'pi')
            ->pluck('receive_basis_no')
            ->unique()->values();

        $data['lcNos'] = $data['yarnReceive']->whereNotNull('lc_no')->pluck('lc_no')->unique()->values();

        $data['yarnCount'] = YarnCount::query()->get();

        $data['type'] = CompositionType::query()->get();

        $data['composition'] = YarnComposition::query()->get();

        return $data;
    }

    public function index(Request $request)
    {
        $reportData = $this->requiredData($request);
        return view('inventory::yarns.reports.GoodsReceivedWithLcOpen.index', $reportData);
    }

    public function getReport(Request $request, GoodReceiveWithLcOpenReportService $reportService)
    {
        $reportData['reportData'] = $reportService->getReportData($request);
//        dd($reportData['reportData']);
        return view('inventory::yarns.reports.GoodsReceivedWithLcOpen.table', $reportData);
    }

    public function pdf(Request $request,  GoodReceiveWithLcOpenReportService $reportService)
    {
        $reportData['reportData'] = $reportService->getReportData($request);
        $pdf = PDF::loadView('inventory::yarns.reports.GoodsReceivedWithLcOpen.pdf', $reportData)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('daily_yarn_receive_report.pdf');
    }

}
