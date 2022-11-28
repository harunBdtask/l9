<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;

class DailySwingProductionReportService
{
    public static function data($request): Collection
    {
        $factoryId = $request->get('factory_id');
        $floorId = $request->get('floor_id');
        $date = $request->get('date');

        $data = ManualDateWiseSewingReport::query()
            ->with([
                'floor:id,floor_no',
                'factory:id,factory_name',
                'buyer:id,name',
                'order:id,style_name',
                'line:id,line_no',
                'item:id,name',
                'color:id,name',
            ])
            ->whereDate('production_date', $date)
            ->where('factory_id', $factoryId)
            ->where('floor_id', $floorId)
            ->get();
        return $data;
    }

    public static function metaData($request): array
    {
        $factoryId = $request->get('factory_id');
        $floorId = $request->get('floor_id');
        $date = $request->get('date');

        $metadata['factory'] = Factory::query()->find($factoryId)['factory_name'] ?? null;
        $metadata['floor'] = Floor::query()->find($floorId)['floor_no'] ?? null;
        $metadata['date'] = $date != "undefined" ? Carbon::parse($date)->format('d-M-y') : null;

        return $metadata;
    }
}
