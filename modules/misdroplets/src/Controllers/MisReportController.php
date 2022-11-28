<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Misdroplets\Services\FactoryWiseCuttingReportService;
use SkylarkSoft\GoRMG\Misdroplets\Services\FactoryWiseInputOutputReportService;
use SkylarkSoft\GoRMG\Misdroplets\Services\FactoryWisePrintSendReceivedReportService;

class MisReportController extends Controller
{
    /**
     * Get Factory List for Select Field Options
     * 
     * @return array
     */
    public function getFactoryListForDropdown(): array
    {
        return Factory::pluck('factory_name', 'id')->all();
    }

    /**
     * Generate Factory Wise Cutting Report
     * 
     * @param Request
     * @return View
     */
    public function factoryWiseCuttingReport(Request $request): View
    {
        $fromDate = $request->from_date ?? now()->subDays(366)->toDateString();
        $toDate = $request->to_date ?? now()->toDateString();
        $factoryId = $request->factory_id ?? null;

        $reportData = (new FactoryWiseCuttingReportService)
            ->setFromDate($fromDate)
            ->setToDate($toDate)
            ->setFactoryId($factoryId)
            ->fetch();

        return view('misdroplets::reports.factory_wise_cutting_report', [
            'factories' => $this->getFactoryListForDropdown(),
            'report_data' => $reportData,
        ]);
    }

    /**
     * Generate Factory Wise Print Send and Receive Report
     * 
     * @param Request
     * @return View
     */
    public function factoryWisePrintSentReceivedReport(Request $request): View
    {
        $fromDate = $request->from_date ?? now()->subDays(366)->toDateString();
        $toDate = $request->to_date ?? now()->toDateString();
        $factoryId = $request->factory_id ?? null;

        $factory_wise_report_data = (new FactoryWisePrintSendReceivedReportService)
            ->setFromDate($fromDate)
            ->setToDate($toDate)
            ->setFactoryId($factoryId)
            ->fetch();

        return view('misdroplets::reports.factory-wise-print-sent-received-report', [
            'factories' => $this->getFactoryListForDropdown(),
            'factory_wise_report_data' => $factory_wise_report_data
        ]);
    }

    /**
     * Generate Factory Wise Input Output Report
     * 
     * @param Request
     * @return View
     */
    public function factoryWiseInputOutputReport(Request $request): View
    {
        $factoryId = $request->factory_id ?? null;
        $fromDate = $request->from_date ?? now()->subDays(366)->toDateString();
        $toDate = $request->to_date ?? now()->toDateString();

        $report_data = (new FactoryWiseInputOutputReportService)
            ->setFromDate($fromDate)
            ->setToDate($toDate)
            ->setFactoryId($factoryId)
            ->fetch();

        return view('misdroplets::reports.factory_wise_input_output_report', [
            'factories' => $this->getFactoryListForDropdown(),
            'report_data' => $report_data
        ]);
    }
}
