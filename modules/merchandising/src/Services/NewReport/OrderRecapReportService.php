<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\NewReport;

use Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class OrderRecapReportService
{
    public static function getReport($ids, $dates): array
    {
        list($factoryId, $buyerId, $seasonId, $styleId, $poId) = $ids;
        list($fromDate, $toDate, $dateRangeType) = $dates;
        $po_table = PurchaseOrder::query()->where('factory_id', $factoryId)
            ->when($buyerId, function ($q, $buyerId) {
                $q->where('buyer_id', $buyerId);
            })->when($seasonId, function ($q, $seasonId) {
                $q->whereHas('order', function ($query) use ($seasonId) {
                    $query->where('season_id', $seasonId);
                });
            })->when($styleId, function ($q, $styleId) {
                $q->where('order_id', $styleId);
            })->when($poId, function ($q, $poId) {
                $q->where('id', $poId);
            })->when($dateRangeType, function ($q, $dateRangeType) use ($fromDate, $toDate) {
                if ($dateRangeType == 1) {
                    $q->whereBetween('po_receive_date', [$fromDate, $toDate]);
                } elseif ($dateRangeType == 2) {
                    $q->whereBetween('ex_factory_date', [$fromDate, $toDate]);
                } elseif ($dateRangeType == 3) {
                    $q->whereBetween('country_ship_date', [$fromDate, $toDate]);
                }
            })
            ->get();
        $po_table_report = $po_table->groupBy(['buyer_id', 'order_id']);
        $order_table_summary = $po_table->groupBy('buyer_id')->map(function ($data) {
            return [
                "factory" => Arr::get($data[0], 'factory.factory_name'),
                "buyer" => Arr::get($data[0], "buyer.name"),
                "order_qty" => $data->sum('po_quantity')
            ];
        });
        return ['table_report' => $po_table_report, 'summary' => $order_table_summary];
    }
}
