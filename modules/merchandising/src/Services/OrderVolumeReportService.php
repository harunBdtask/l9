<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class OrderVolumeReportService
{
    private $from, $to;

    public function __construct(Request $request)
    {
        $this->from = $request->get('from_date') ?? Carbon::now()->firstOfMonth()->format('Y-m-d');
        $this->to = $request->get('to_date') ?? Carbon::now()->lastOfMonth()->format('Y-m-d');
    }

    public function report(): array
    {
        $report['reportData'] = PurchaseOrder::query()
            ->whereHas('order')
            ->with('buyer')
            ->whereDate('po_receive_date', '>=', $this->from)
            ->whereDate('po_receive_date', '<=', $this->to)
            ->selectRaw(DB::raw('id,buyer_id,
            SUM(po_quantity) AS total_qty, SUM(po_quantity * avg_rate_pc_set) AS total_value'))
            ->groupBy('buyer_id')
            ->get()
            ->sortByDesc('total_value');
        $report['buyers'] = collect($report['reportData'])->pluck('buyer.name')->values();
        $report['totalValue'] = collect($report['reportData'])->pluck('total_value')->values();
        return $report;
    }
}
