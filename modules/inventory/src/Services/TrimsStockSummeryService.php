<?php


namespace SkylarkSoft\GoRMG\Inventory\Services;


use DB;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStockSummery;

class TrimsStockSummeryService
{

    public function summary($styleName, $itemId, $uomId)
    {
        return TrimsStockSummery::where([
            'style_name' => $styleName,
            'item_id'    => $itemId,
            'uom_id'     => $uomId
        ])->first();
    }

    public function balance($styleName, $itemId, $uomId)
    {
        $summery = DB::table('trims_stock_summaries')->where([
            'style_name' => $styleName,
            'item_id'    => $itemId,
            'uom_id'     => $uomId
        ])->first();

        return $summery->balance ?? 0;
    }

    public function totalIssueQty($styleName, $itemId, $uomId)
    {
        $summery = DB::table('trims_stock_summaries')->where([
            'style_name' => $styleName,
            'item_id'    => $itemId,
            'uom_id'     => $uomId
        ])->first();

        return $summery->issue_qty ?? 0;
    }


    public function addNewSummery(TrimsReceiveDetail $detail)
    {

        $styleName = $detail->style_name;
        $itemId = $detail->item_id;
        $uomId = $detail->uom_id;
        $receiveQty = $detail->receive_qty;
        $rate = $detail->rate;
        $amount = $receiveQty * $rate;

        $meta = [
            'item_description' => $detail->item_description,
            'ship_date'        => $detail->ship_date,
            'order_uniq_id'    => $detail->order_uniq_id
        ];

        $newSummary = new TrimsStockSummery([
            'style_name'     => $styleName,
            'item_id'        => $itemId,
            'uom_id'         => $uomId,
            'receive_qty'    => $receiveQty,
            'balance'        => $receiveQty,
            'balance_amount' => $amount,
            'receive_amount' => $amount,
            'meta'           => $meta
        ]);

        $newSummary->save();
    }
}
