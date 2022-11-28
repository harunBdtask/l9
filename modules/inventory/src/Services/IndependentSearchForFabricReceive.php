<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class IndependentSearchForFabricReceive
{
    public function getData(Request $request): array
    {
        $purchaseOrderDetails = PurchaseOrderDetail::query()->has('purchaseOrder')
            ->with(['buyer', 'color', 'order', 'purchaseOrder'])
            ->where('buyer_id', $request->get('buyer_id'))
            ->when($request->get('style_id'), function (Builder $builder) use ($request) {
                $builder->where('order_id', $request->get('style_id'));
            })
            ->when($request->get('po_no'), function (Builder $builder) use ($request) {
                $builder->where('purchase_order_id', $request->get('po_no'));
            });

        $colorsId = $purchaseOrderDetails->pluck('color_id')->unique()->values();

        $response = [];
        foreach ($colorsId as $color) {
            $clonePurchaseOrderDetails = clone $purchaseOrderDetails;
            $data = $clonePurchaseOrderDetails->where('color_id', $color)
                ->when($request->get('po_no'), function ($builder) use ($request) {
                    $builder->where('purchase_order_id', $request->get('po_no'));
                })->get();

            $poNo = $data->pluck('purchaseOrder')->whereNotNull('po_no')
                ->pluck('po_no')
                ->unique()
                ->join(', ');

            if (count($data)) {
                $response[] = [
                    'receivable_type' => 'independent',
                    'receivable_id' => null,
                    'buyer_id' => $data->first()->buyer_id,
                    'buyer_name' => $data->first()->buyer->name,
                    'style_name' => $data->first()->order->style_name,
                    'style_id' => $data->first()->order->id,
                    'unique_id' => $data->first()->order->job_no,
                    'po_no' => $poNo,
                    'color_id' => $data->first()->color_id,
                    'color_name' => $data->first()->color->name,
                    'contrast_color_id' => [$data->first()->color_id],
                    'floor_id' => null,
                    'room_id' => null,
                    'rack_id' => null,
                    'shelf_id' => null,
                ];
            }
        }

        return $response;
    }
}
