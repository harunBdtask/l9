<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Services\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\GeneralStore\Models\YarnReceiveDetails;

class YarnStockSummeryReportService
{
    public static function data(Request $request): array
    {
        $reportData['search'] = $request->get('search');
        $reportData['first_date'] = $request->get('first_date') ?? Carbon::now()->format('Y-m-d');
        $reportData['last_date'] = $request->get('last_date') ?? Carbon::now()->format('Y-m-d');

        $reportData['yarn_count'] = YarnReceiveDetails::filter($request->get('search'))->with('yarnCount')
            ->select('yarn_count_id')
            ->groupBy('yarn_count_id')
            ->paginate();

        $reportData['other_details'] = YarnReceiveDetails::with('yarnType')
            ->with('yarnCount')
            ->select('yarn_count_id', 'yarn_brand', 'yarn_lot', 'yarn_type_id', 'rate')
            ->groupBy('yarn_count_id', 'yarn_brand', 'yarn_lot', 'yarn_type_id')
            ->get();

        return $reportData;
    }
}
