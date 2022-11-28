<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Services;

use SkylarkSoft\GoRMG\Finishingdroplets\Models\ErpPackingList;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\ErpPackingListDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class ErpPackingListFormatterService
{

    public static function fetchPoDetails($request, $erpPackingList)
    {
        $po_no = $request->get('po_no');
        $pos = Order::query()->with(['purchaseOrders' => function ($query) use ($po_no) {
            return $query->where('po_no', $po_no);
        }, 'purchaseOrders.poDetails'])->where([
            'factory_id' => $request->get('factoryId'),
            'buyer_id' => $request->get('buyerId'),
            'style_name' => $request->get('style_name')
        ])->first();

//        return $pos;
//        $poDetails = $pos->pluck('purchaseOrders')->flatten()->pluck('poDetails')->flatten();
        $data = [];
        $data['factory_id'] = $request->get('factoryId');
        $data['buyer_id'] = $request->get('buyerId');
        $data['style_name'] = $request->get('style_name');
        $data['order_id'] = isset($pos->purchaseOrders) ? $pos->purchaseOrders[0]['order_id'] : null;
        $data['purchase_order_id'] = isset($pos->purchaseOrders) ? $pos->purchaseOrders[0]['id'] : null;
        $data['customer_name'] = isset($pos->purchaseOrders) ? $pos->purchaseOrders[0]['customer'] : null;

        $poDetails = isset($pos->purchaseOrders) ? $pos->purchaseOrders->pluck('poDetails')->flatten() : [];
        $poDetails = isset($poDetails) ? collect($poDetails)->pluck('quantity_matrix')->collapse()->where('particular', 'Qty.')->values() : [];


//        return $erpPackingList ?? null;
        $poDetails = collect($poDetails)->groupBy('color_id')->map(function ($item, $key) use ($erpPackingList){
            $colorWisePackingData = isset($erpPackingList) ? collect($erpPackingList['details'])->firstWhere('color_id', $key) : null;
            $sizeRatios = isset($colorWisePackingData)  ? $colorWisePackingData['size_ratio'] : null;
            return [
                'color_name' => collect($item)->pluck('color')->unique()->values()->implode(''),
                'color_id' => collect($item)->pluck('color_id')->unique()->values()->implode(''),
                'size_id' => collect($item)->pluck('size_id')->unique()->values(),
                'size_ratio' => collect($item)->map(function ($size) use($sizeRatios){
                    $sizeWiseData = isset($sizeRatios) ? collect($sizeRatios)->firstWhere('size_id', $size['size_id']) : null;
                    return [
                        'size_id' => $size['size_id'],
                        'size_name' => $size['size'],
                        'qty' => isset($sizeWiseData)? $sizeWiseData['qty'] : null ,
                    ];
                }),
                'order_qty' => collect($item)->sum('value'),
            ];
        });

        return self::formatPoDetails($poDetails, $data, $erpPackingList);
    }

    public static function formatPoDetails($poDetails, $data, $erpPackingList)
    {

        return collect($poDetails)->map(function ($item, $key) use($data, $erpPackingList){
            $colorWisePackingData = isset($erpPackingList) ? collect($erpPackingList['details'])->firstWhere('color_id', $key) : null;

            return [
                'id' => isset($colorWisePackingData) ? $colorWisePackingData['id'] : null,
                'erp_packing_list_id' => isset($colorWisePackingData) ? $colorWisePackingData['erp_packing_list_id'] : null,
                'factory_id' => $data['factory_id'],
                'customer_name' => isset($colorWisePackingData) ? $colorWisePackingData['customer_name'] : $data['customer_name'],
                'order_id' => $data['order_id'],
                'buyer_id' => $data['buyer_id'],
                'purchase_order_id' => $data['purchase_order_id'],
                'ctn_no_from' => isset($colorWisePackingData) ? $colorWisePackingData['ctn_no_from'] :  null,
                'ctn_no_to' => isset($colorWisePackingData) ? $colorWisePackingData['ctn_no_to'] :  null,
                'ctn_qty' => isset($colorWisePackingData) ? $colorWisePackingData['ctn_qty'] :  null,
                'color_id' => $key,
                'team_or_color' => $item['color_name'],
                'size_ratio' => $item['size_ratio'],
                'qty_pcs_per_ctn' => isset($colorWisePackingData) ? $colorWisePackingData['qty_pcs_per_ctn'] : null,
                'ttl_qty_in_pcs' => isset($colorWisePackingData) ? $colorWisePackingData['ttl_qty_in_pcs'] : null,
                'net_weight' => isset($colorWisePackingData) ? $colorWisePackingData['net_weight'] : null,
                'grs_weight' => isset($colorWisePackingData) ? $colorWisePackingData['grs_weight'] : null,
                'total_net_weight' => isset($colorWisePackingData) ? $colorWisePackingData['total_net_weight'] : null,
                'total_grs_weight' => isset($colorWisePackingData) ? $colorWisePackingData['total_grs_weight'] : null,
                'length' => isset($colorWisePackingData) ? $colorWisePackingData['length'] : null,
                'width' => isset($colorWisePackingData) ? $colorWisePackingData['width'] : null,
                'height' => isset($colorWisePackingData) ? $colorWisePackingData['height'] : null,
                'cbm' => isset($colorWisePackingData) ? $colorWisePackingData['cbm'] : null,
                'sizes' => $item['size_id'],
                'order_qty' => $item['order_qty'],

            ];
        })->values();

    }

}
