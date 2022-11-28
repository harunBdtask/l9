<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3\Formatter;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceiveDetail;

class TrimsReceiveDetailsFormatter
{
    public static function format(TrimsStoreReceiveDetail $detail): array
    {
        return [
            'id' => $detail['id'],
            'trims_store_receive_id' => $detail['trims_store_receive_id'],
            'receive_basis_id' => $detail['receive_basis_id'],
            'unique_id' => $detail['unique_id'],
            'transaction_date' => $detail['transaction_date'],
            'factory_id' => $detail['factory_id'],
            'buyer_id' => $detail['buyer_id'],
            'buyer_name' => $detail['buyer']['name'],
            'style_id' => $detail['style_id'],
            'style_name' => $detail['order']['style_name'],
            'po_numbers' => $detail['po_numbers'],
            'booking_id' => $detail['booking_id'],
            'booking_no' => $detail['booking_no'],
            'garments_item_id' => $detail['garments_item_id'],
            'garments_item_name' => $detail['garments_item_name'],
            'item_code' => $detail['item_code'],
            'item_id' => $detail['item_id'],
            'item_name' => $detail['itemGroup']['item_group'],
            'sensitivity_id' => $detail['sensitivity_id'],
            'supplier_id' => $detail['supplier_id'],
            'supplier_name' => $detail['supplier']['name'],
            'brand_name' => $detail['brand_name'],
            'item_description' => $detail['item_description'],
            'color_id' => $detail['color_id'],
            'color' => $detail['color']['name'],
            'size_id' => $detail['size_id'],
            'size' => $detail['size']['name'],
            'order_qty' => $detail['order_qty'],
            'wo_qty' => $detail['wo_qty'],
            'receive_qty' => $detail['receive_qty'],
            'reject_qty' => $detail['reject_qty'],
            'over_receive_qty' => $detail['over_receive_qty'],
            'uom_id' => $detail['uom_id'],
            'uom_value' => $detail['uom']['unit_of_measurement'],
            'currency_id' => $detail['currency_id'],
            'rate' => $detail['rate'],
            'exchange_rate' => $detail['exchange_rate'],
            'floor_id' => $detail['floor_id'],
            'room_id' => $detail['room_id'],
            'rack_id' => $detail['rack_id'],
            'shelf_id' => $detail['shelf_id'],
            'bin_id' => $detail['bin_id'],
            'remarks' => $detail['remarks'],
            'receive_details_qty' => ((float) $detail['receive_qty'] + $detail['over_receive_qty']) - $detail['reject_qty'],
            'amount' => (float) $detail['receive_qty'] * (float) $detail['rate'],
            'balance_qty' => (float) $detail['wo_qty'] - (float) $detail['receive_details_qty'],
        ];
    }
}
