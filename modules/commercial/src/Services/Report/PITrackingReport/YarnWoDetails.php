<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Report\PITrackingReport;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;

class YarnWoDetails implements PITrackingDetailsInterface
{
    public function get($details): Collection
    {
        if (request('buyer_id')) {
            $details = collect($details)->filter(function ($item) {
                return $item->buyer_id == request('buyer_id') ? $item : null;
            })->values();
        }
        return collect($details)->groupBy('wo_no')->map(function ($item, $woNo) {
            $woData = YarnPurchaseOrder::query()
                ->with('details')
                ->where('wo_no', $woNo)
                ->first();
            return [
                'wo_no' => $woNo,
                'wo_date' => $woData->wo_date,
                'style_name' => collect($item)->pluck('style_name')->unique()->values()->join(', '),
                'wo_value' => collect($woData->details)->sum('amount'),
            ];
        })->values();
    }
}
