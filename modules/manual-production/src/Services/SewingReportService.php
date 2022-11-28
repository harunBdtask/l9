<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Services;

use SkylarkSoft\GoRMG\ManualProduction\Models\ManualHourlySewingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualSewingInputProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class SewingReportService
{
    public static function dailyInputUnitWise($factory_id, $from_date, $to_date)
    {
        return ManualSewingInputProduction::query()->where('factory_id', $factory_id)
            ->whereBetween('production_date', [$from_date, $to_date])
            ->orderBy('production_date', 'ASC')->get();
    }

    public static function floorSizeWiseStyleInOut($buyer_id, $order_id): array
    {
        $reports = ManualDateWiseSewingReport::query()->selectRaw('buyer_id, order_id, floor_id, color_id, size_id,
                SUM(input_qty) as total_sewing_input_sum, SUM(sewing_output_qty) as total_sewing_output_sum')
            ->where([
                'buyer_id' => $buyer_id,
                'order_id' => $order_id,
            ])->groupBy('buyer_id', 'order_id', 'floor_id', 'color_id', 'size_id')->get();
        $sizes = $reports->whereNotNull('size_id')->pluck('size.name', 'size_id')->toArray();
        return [$reports, $sizes];
    }

    public static function dateFloorWiseHourlySewingOutput($floor_id, $date): array
    {
        $lines = Line::query()->where('floor_id', $floor_id)->pluck('line_no', 'id');
        $floorwise_manual_productions = ManualHourlySewingProduction::query()->where('floor_id', $floor_id)->get();
        $reports = $floorwise_manual_productions->where('production_date', $date)->sortByDesc('updated_at');
        return [$lines, $floorwise_manual_productions, $reports];
    }
}
