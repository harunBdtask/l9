<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Report;

class SearchInfoViewService
{
    public function generate(RequiredDataService $requiredDataService, ReportViewService $reportViewService) {
        return view('merchandising::reports.includes.report_search_info_header', [
            'data' => $requiredDataService->getInfo($reportViewService)
        ]);
    }
}
