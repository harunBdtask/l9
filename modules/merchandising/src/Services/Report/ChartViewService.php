<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Report;

class ChartViewService
{
    public function generate(RequiredDataService $requiredDataService, ReportViewService $reportViewService)
    {
        $data['colors'] = $reportViewService->getChartColor();
        $data['levels'] = $reportViewService->getChartLevel();
        $data['values'] = $reportViewService->getChartValues();

        switch ($reportViewService->getChartType()) {
            case 'pie':
            case 'bar' :
                return view('merchandising::reports.includes.report_chart_header', $data);
        }
    }
}
