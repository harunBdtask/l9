<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports;

use Carbon\Carbon;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssue;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Inventory\Exports\FinishFabricStockReportExport;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService\FinishFabricStockReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class FinishFabricMonthlyStockReportController
{
    public function finishFabricStockReport()
    {
        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        $colors = Color::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view('inventory::reports.finish_fabric_monthly_stock_report.index', [
            'buyers' => $buyers,
            'colors' => $colors,
        ]);
    }

    public function dateWiseFinishFabricStockReport(Request $request, FinishFabricStockReportService $reportService)
    {
        $reportData = $reportService->getReportData($request);
        return view('inventory::reports.finish_fabric_monthly_stock_report.table', [
            'fabricReceives' => $reportData
        ]);
    }

    public function excel(Request $request, FinishFabricStockReportService $reportService)
    {
        $reportData = $reportService->getReportData($request);
        return Excel::download(new FinishFabricStockReportExport($reportData), 'finish_fabric_stock_report_report.xlsx');
    }
}
