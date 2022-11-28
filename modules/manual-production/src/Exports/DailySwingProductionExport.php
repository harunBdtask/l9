<?php
namespace SkylarkSoft\GoRMG\ManualProduction\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWisePrintEmbrReport;
use SkylarkSoft\GoRMG\ManualProduction\Services\DailySwingProductionReportService;

class DailySwingProductionExport implements FromView, ShouldAutoSize
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        // TODO: Implement view() method.
        $data = DailySwingProductionReportService::data($this->request);
        $metaData = DailySwingProductionReportService::metaData($this->request);

        return view('manual-production::reports.dailySewingProductionReport.data', compact(
            'data', 'metaData'
        ));
    }
}
