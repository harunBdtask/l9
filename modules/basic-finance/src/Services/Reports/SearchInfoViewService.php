<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports;

class SearchInfoViewService
{
    public function generate(RequiredDataService $requiredDataService) {
        return view('basic-finance::reports.report_search_info_header', [
            'data' => $requiredDataService->getInfo()
        ]);
    }
}
