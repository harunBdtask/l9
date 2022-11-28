<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Report;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class ReportService
{
    public static function getOrderRecapReport(Request $request): array
    {
        $buyer_id = $request->get('buyer_id') ?? null;
        $month = $request->get('month') ?? date('n', time());
        $year = $request->get('year') ?? date('Y', time());
        $data['reports'] = PurchaseOrder::query()->when($buyer_id, function ($q, $buyer_id) {
            $q->where('buyer_id', $buyer_id);
        })->whereMonth('ex_factory_date', $month)
            ->whereYear('ex_factory_date', $year)->orderBy('buyer_id', 'asc')->get()->groupBy(['buyer_id', 'order_id']);
        $data['buyers'] = Buyer::query()->pluck("name as text", "id");
        return $data;
    }

    public static function getBomReport(Request $request)
    {
        $style_id = $request->get('style_id') ?? null;
        $data['unique_id'] = null;
        $budgetData = [];
        if ($style_id) {
            $budget = Budget::query()->find($style_id);
            $data['unique_id'] = $jobNo = $budget->job_no ?? null;
            $data['buyer'] = $budget->buyer->name ?? null;
            $budgetData = BudgetReportService::getBudgetByJobNo($jobNo)->budgetData();
            $data = $budgetData ? collect($data)->merge($budgetData) : $data;
        }
        return $data;
    }
}
