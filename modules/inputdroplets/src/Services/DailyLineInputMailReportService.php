<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;

class DailyLineInputMailReportService
{
    private $totalInputs = [];

    /**
     * @return Builder[]
     */
    public function getReport($date): array
    {
        $lineInputs = FinishingProductionReport::query()
            ->with([
                'buyer:id,name', 'order:id,job_no,style_name', 'purchaseOrder:id,po_no,po_quantity', 'color:id,name', 'line:id,line_no', 'floor:id,floor_no'
            ])
            ->whereDate('production_date', $date)
            ->where('sewing_input', '>', 0)
            ->get();

        $targets = SewingLineTarget::query()
            ->where('target_date', $date)
            ->whereIn('floor_id', $lineInputs->pluck('floor_id')->unique()->values()->toArray())
            ->get();

        $this->totalInputs = $this->totalInputs($lineInputs);

        $data['reportByOrderStylePo'] = $lineInputs->map(function ($item) {
            $totalInput = $this->itemWiseTotalInput($item);
            return [
                'buyer_name' => $item->buyer->name ?? '',
                'reference_no' => $item->order->job_no ?? '',
                'order_style_no' => $item->order->style_name ?? '',
                'po_no' => $item->purchaseOrder->po_no ?? '',
                'color' => $item->color->name ?? '',
                'total_qty' => $item->purchaseOrder->po_quantity ?? 0,
                'today_input' => $item->sewing_input,
                'total_input' => $totalInput,
                'bal_input' => $totalInput - $item->sewing_input,
            ];
        });

        $data['reportFloorWise'] = $lineInputs->map(function ($item) {
            return [
                'line_no' => $item->line->line_no ?? '',
                'floor_no' => $item->floor->floor_no ?? '',
                'input_qty' => $item->sewing_input,
            ];
        })->groupBy('floor_no');

        $data['totalSummary'] = [
            'total_input_target' => $targets->sum('target'),
            'total_input' => $lineInputs->sum('sewing_input'),
            'no_of_line' => $lineInputs->pluck('line_id')->unique()->count(),
        ];

        return $data;
    }

    /**
     * @param $lineInputs
     * @return Builder[]|Collection
     */
    private function totalInputs($lineInputs)
    {
        return FinishingProductionReport::query()
            ->whereIn('buyer_id', $lineInputs->pluck('buyer_id')->unique()->values())
            ->whereIn('order_id', $lineInputs->pluck('order_id')->unique()->values())
            ->whereIn('purchase_order_id', $lineInputs->pluck('purchase_order_id')->unique()->values())
            ->whereIn('color_id', $lineInputs->pluck('color_id')->unique()->values())
            ->get();
    }

    /**
     * @param $item
     * @return mixed
     */
    private function itemWiseTotalInput($item)
    {
        return collect($this->totalInputs)
            ->where('buyer_id', $item['buyer_id'])
            ->where('order_id', $item['order_id'])
            ->where('purchase_order_id', $item['purchase_order_id'])
            ->where('color_id', $item['color_id'])
            ->sum('sewing_input');
    }
}
