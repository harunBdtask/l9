<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Reports;

use PDF;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Services\Report\FinalCostingReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContractDetail;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class FinalCostingReportController
{

    public function index()
    {
        $companies = Factory::query()->get(['id', 'factory_name']);
        return view("merchandising::reports.final_costing_report.index", compact('companies'));
    }

    public function view(Request $request)
    {
        $data = (new FinalCostingReportService())->reportData($request);
        return view("merchandising::reports.final_costing_report.report", $data);
    }

    public function pdf(Request $request)
    {
        $data = (new FinalCostingReportService())->reportData($request);
        $signature = $data['signature'];
        $pdf = PDF::loadView('merchandising::reports.final_costing_report.pdf', $data)
            ->setPaper('a4')->setOrientation('portrait')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);
        return $pdf->stream('final_costing_report.pdf');
    }
}
