<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class DailyBasisCuttingService
{

    public function response($request): array
    {
        $data = array();
        $poId = $request->get('po_id');
        $colorId = $request->get('color_id');

        $queryData = DB::select("SELECT * FROM (
                    SELECT date(bundle_card_generation_details.created_at) as prod_date, bundle_cards.color_id as bundle_color_id, bundle_cards.size_id as bundle_size_id, bundle_card_generation_details.sid as bundle_sid, sum(bundle_cards.quantity) as cut_qty, sum(bundle_cards.total_rejection) as cut_rej, sid
                        FROM bundle_card_generation_details
                        JOIN bundle_cards on bundle_cards.bundle_card_generation_detail_id = bundle_card_generation_details.sid
                        WHERE bundle_card_generation_details.is_manual = 0
                            AND bundle_card_generation_details.is_regenerated = 0
                            AND bundle_cards.status = 1
                            AND bundle_cards.purchase_order_id = {$poId}
                        GROUP BY bundle_cards.color_id, bundle_card_generation_details.sid, date(bundle_card_generation_details.created_at)
                ) query
                JOIN bundle_card_generation_details on bundle_card_generation_details.sid = query.bundle_sid
                    WHERE bundle_card_generation_details.is_manual = 0
                        AND bundle_card_generation_details.is_regenerated = 0
                    ORDER BY bundle_card_generation_details.sid ASC"
        );

        if ($queryData) {
            if ($colorId) {
                $queryData = collect($queryData)->where('bundle_color_id', $colorId);
            }
            collect($queryData)->groupBy('bundle_color_id')
                ->each(function ($bundleCards, $colorId) use (&$data) {
                    $bundleCards->each(function ($bundleCard) use (&$data) {
                        $colorId = $bundleCard->bundle_color_id;
                        $ratios = json_decode($bundleCard->ratios, true);
                        $poDetails = json_decode($bundleCard->po_details, true);
                        $layer = collect(json_decode($bundleCard->rolls))->sum('plys');
                        $sizeWiseRatio = $this->formatColor($ratios);
                        $totalSizeRatio = array_sum($sizeWiseRatio);
                        $lastIndex = isset($data[$colorId], $data[$colorId]['data']) ? count($data[$colorId]['data']) - 1 : 0;
                        $poColorSizeQuantityLast = $data[$colorId]['data'][$lastIndex]['po_color_size_quantity'] ?? [];
                        $poColorSizeQuantity = $this->poColorSizeQuantity(
                            $sizeWiseRatio,
                            $layer,
                            $poColorSizeQuantityLast
                        );

                        $data[$colorId]['po_sizes'] = $poColorSizeQuantity['sizes'];
                        $data[$colorId]['data'][] = [
                            'sid' => $bundleCard->sid,
                            'date' => formatDate($bundleCard->created_at),
                            'cutting_no' => explode(':', $bundleCard->cutting_no)[1] ?? '',
                            'color' => $this->colors[$colorId]['name'] ?? '',
                            'layer' => $layer,
                            'lot_no' => collect(json_decode($bundleCard->lot_ranges))->sum('lot_no'),
                            'sizes' => $sizeWiseRatio,
                            'total_size_ratio' => $totalSizeRatio,
                            'po_color_size_quantity' => $poColorSizeQuantity['data'],
                            'total_po_color_size_quantity' => $poColorSizeQuantity['total']
                        ];
                    });

                });
        }

        return [
            'data' => $data,
            'sizes' => $this->getSizes($data),
            'colors' => Color::query()->get()->keyBy('id'),
        ];
    }

    public function getSizes($data): array
    {
        $data = array_flatten(array_pluck($data, 'data'), 1);
        $sizes = array_pluck($data, 'sizes');

        $allSizes = array();
        collect($sizes)->each(function ($sizes) use(&$allSizes) {
            $allSizes = array_merge($allSizes, array_keys($sizes));
        });

        return array_unique($allSizes);
    }

    public function formatColor($ratios): array
    {
        $data = array();
        $ratios = collect($ratios);
        $ratios->each(function ($ratio) use ($ratios, &$data) {
            $data[$ratio['size_name']] = $ratios->where('size_id', $ratio['size_id'])->sum('ratio');
        });

        return $data;
    }

    public function poColorSizeQuantity($sizeWiseRatio, $layer, $poColorSizeQuantityLast): array
    {
        $data = array();
        collect($sizeWiseRatio)->each(function ($qty, $size) use ($layer, &$data, $poColorSizeQuantityLast) {

                $actualQuantity = $qty * $layer;
                $data[$size] = [
                    'actual_quantity' => $actualQuantity,
                    'previous_quantity' => array_key_exists($size, $poColorSizeQuantityLast)
                        ? $actualQuantity + $poColorSizeQuantityLast[$size]['previous_quantity']
                        : $actualQuantity,
                ];
            });

        return [
            'total' => [
                'actual' => collect($data)->sum('actual_quantity'),
                'with_previous' => collect($data)->sum('previous_quantity'),
            ],
            'data' => $data,
            'sizes' => array_keys($sizeWiseRatio),
        ];
    }
}
