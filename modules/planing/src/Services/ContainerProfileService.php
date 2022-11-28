<?php

namespace SkylarkSoft\GoRMG\Planing\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Planing\Models\ContainerProfile\ContainerProfileDetail;
use SkylarkSoft\GoRMG\Planing\Models\ContainerProfile\ContainerSummaries;

class ContainerProfileService
{
    public static function containerProfiles(Request $request)
    {
        $containerSummaries = ContainerSummaries::query()
            ->where('ex_factory_date', '>=', date('Y-m-d'))
            ->when($request->query('id'), function (Builder $query) use ($request) {
                $query->whereNotIn('id', (array) $request->query('id'));
            })
            ->get()
            ->pluck('container_id')
            ->unique();

        return ContainerProfileDetail::query()
            ->whereNotIn('id', $containerSummaries)
            ->where('ex_factory_date', '>=', date('Y-m-d'))
            ->get()
            ->map(function ($collection, $key) {
                $containerSummaries = ContainerSummaries::query()
                    ->where('container_id', $collection->id)
                    ->first();

                $usedCBM = isset($containerSummaries) ? collect($containerSummaries->po_list)->sum('cbm') : 0;
                $balance = $collection->cbm - $usedCBM;

                return [
                    'id' => isset($containerSummaries) ? $containerSummaries->id : null,
                    'index' => $key + 1,
                    'container_id' => $collection->id,
                    'name' => $collection->container_no,
                    'ex_factory_date' => $collection->ex_factory_date,
                    'cbm' => $collection->cbm,
                    'balance' => $balance,
                    'items' => isset($containerSummaries) ? $containerSummaries->po_list : [],
                ];
            });
    }

    public static function containerProfilesEdit(Request $request)
    {
        $containerSummaries = ContainerSummaries::query()
            ->where('ex_factory_date', '>=', date('Y-m-d'))
            ->when($request->query('id'), function (Builder $query) use ($request) {
                $query->whereIn('id', (array) $request->query('id'));
            })
            ->get()
            ->pluck('container_id')
            ->unique();


        return ContainerProfileDetail::query()
            ->whereIn('id', $containerSummaries)
            ->where('ex_factory_date', '>=', date('Y-m-d'))
            ->get()
            ->map(function ($collection, $key) {
                $containerSummaries = ContainerSummaries::query()
                    ->where('container_id', $collection->id)
                    ->first();

                $usedCBM = isset($containerSummaries) ? collect($containerSummaries->po_list)->sum('cbm') : 0;
                $balance = $collection->cbm - $usedCBM;

                return [
                    'id' => isset($containerSummaries) ? $containerSummaries->id : null,
                    'index' => $key + 1,
                    'container_id' => $collection->id,
                    'name' => $collection->container_no,
                    'ex_factory_date' => $collection->ex_factory_date,
                    'cbm' => $collection->cbm,
                    'balance' => $balance,
                    'items' => isset($containerSummaries) ? $containerSummaries->po_list : [],
                ];
            });
    }
}
