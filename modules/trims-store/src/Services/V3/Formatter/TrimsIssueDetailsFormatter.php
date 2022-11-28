<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3\Formatter;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue\TrimsStoreIssueDetail;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\StockSummaryService;

class TrimsIssueDetailsFormatter
{
    public static function format(TrimsStoreIssueDetail $detail): array
    {
        $stockSummary = StockSummaryService::setCriteria($detail);

        return [
            'id' => $detail['id'],
            'factory_id' => $detail['factory_id'],
            'buyer_id' => $detail['buyer_id'],
            'sensitivity_id' => $detail['sensitivity_id'],
            'buyer_name' => $detail['buyer']['name'],
            'style_id' => $detail['style_id'],
            'style_name' => $detail['order']['style_name'],
            'item_id' => $detail['item_id'],
            'item_name' => $detail['itemGroup']['item_group'],
            'item_code' => $detail['item_code'],
            'brand_name' => $detail['brand_name'],
            'garments_item_id' => $detail['garments_item_id'],
            'garments_item_name' => collect($detail['order']['item_details']['details'])
                    ->pluck('item_name')
                    ->join(', ') ?? null,
            'item_description' => $detail['item_description'],
            'color_id' => $detail['color_id'],
            'color' => $detail['color']['name'],
            'size_id' => $detail['size_id'],
            'order_qty' => $detail['order_qty'],
            'wo_qty' => $detail['wo_qty'],
            'issue_qty' => $detail['issue_qty'],
            'uom_id' => $detail['uom_id'],
            'uom_name' => $detail['uom']['unit_of_measurement'],
            'currency_id' => $detail['currency_id'],
            'currency_name' => $detail['currency']['currency_name'],
            'rate' => $detail['rate'],
            'amount' => number_format((float) $detail['issue_qty'] * (float) $detail['rate'], 2),
            'exchange_rate' => $detail['exchange_rate'],
            'floor_id' => $detail['floor_id'],
            'floor_name' => $detail['floor']['name'],
            'room_id' => $detail['room_id'],
            'room_name' => $detail['room']['name'],
            'rack_id' => $detail['rack_id'],
            'rack_name' => $detail['rack']['name'],
            'shelf_id' => $detail['shelf_id'],
            'shelf_name' => $detail['shelf']['name'],
            'bin_id' => $detail['bin_id'],
            'bin_name' => $detail['bin']['name'],
            'remarks' => $detail['remarks'],
            'po_numbers' => $detail['po_numbers'],
            'supplier_id' => $detail['supplier_id'],
            'supplier_name' => $detail['supplier']['name'],
            'total_rcv_qty' => $stockSummary->computeReceiveQty(),
            'prev_issue_qty' => $stockSummary->getStockSummary()['issue_qty'] ?? 0,
            'booking_id' => $detail['booking_id'],
            'booking_no' => $detail['booking_no'],
            'pi_numbers' => $detail['pi_numbers'],
            'transaction_date' => $detail['transaction_date'],
        ];
    }
}
