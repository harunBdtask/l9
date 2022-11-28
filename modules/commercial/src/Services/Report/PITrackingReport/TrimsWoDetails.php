<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Report\PITrackingReport;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class TrimsWoDetails implements PITrackingDetailsInterface
{
    public function get($details): Collection
    {
        if (request('buyer_id')) {
            $details = collect($details)->filter(function ($item) {
                $buyerId = Buyer::query()->where('name', $item->buyer)->first()->id ?? '';
                return $buyerId == request('buyer_id') ? $item : null;
            })->values();
        }
        return collect($details)->groupBy('wo_no')->map(function ($item, $woNo) {
            $woData = TrimsBooking::query()
                ->with('details')
                ->where('unique_id', $woNo)
                ->first();
            return [
                'wo_no' => $woNo,
                'wo_date' => $woData->booking_date,
                'style_name' => collect($item)->pluck('style_name')->unique()->values()->join(', '),
                'wo_value' => $woData->details->sum('work_order_amount'),
            ];
        })->values();
    }
}
