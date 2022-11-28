<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;

class BuyerStyleWiseCuttingReportService
{
    protected $month;
    protected $from;
    protected $to;
    protected $dateRange;

    public function __construct(Request $request)
    {
        $this->month = $request->get('month');
        $this->from = Carbon::create($this->month)->startOfMonth()->format('Y-m-d');
        $this->to = Carbon::create($this->month)->endOfMonth()->format('Y-m-d');
        $this->dateRange = Carbon::parse($this->from)->range($this->to);
    }

    /**
     * @return array
     */
    public function report(): array
    {
        $cuttingProductions = DateTableWiseCutProductionReport::query()
            ->select(DB::raw('buyer_id, order_id, production_date, SUM(cutting_qty-cutting_rejection_qty) AS total_cutting_qty'))
            ->with('buyer:id,name', 'order:id,style_name')
            ->where('cutting_qty', '>', 0)
            ->whereDate('production_date', '>=', $this->from)
            ->whereDate('production_date', '<=', $this->to)
            ->groupBy('buyer_id', 'order_id', 'production_date')
            ->orderBy('production_date')
            ->get();

        $totalQty = [];
        foreach ($this->dateRange as $date) {
            $totalQty[$date->toDateString()] = $cuttingProductions->where('production_date', $date->toDateString())->sum('total_cutting_qty') ?? 0;
        }

        $reportData['data'] = $cuttingProductions->groupBy(['buyer_id', 'order_id'])
            ->map(function ($buyerWise) use (&$totalQty) {

                return collect($buyerWise)->map(function ($orderWise) use (&$totalQty) {
                    return collect($orderWise)->flatMap(function ($cuttingData) use ($orderWise, &$totalQty) {

                        $data = [
                            'buyer' => $cuttingData->buyer->name,
                            'style' => $cuttingData->order->style_name,
                            'dates' => []
                        ];

                        foreach ($this->dateRange as $date) {
                            $date = Carbon::make($date)->format('Y-m-d');
                            $cuttingQty = collect($orderWise)->where('production_date', $date)->first()['total_cutting_qty'] ?? 0;
                            $data['dates'][] = [
                                'date' => $date,
                                'qty' => $cuttingQty
                            ];
                        }

                        return $data;
                    });
                });
            });

        $reportData['dates'] = $this->dateRange;
        $reportData['totals'] = $totalQty;
        return $reportData;
    }
}
