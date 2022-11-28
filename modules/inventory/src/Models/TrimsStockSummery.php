<?php


namespace SkylarkSoft\GoRMG\Inventory\Models;


use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class TrimsStockSummery extends Model
{

    protected $table = 'trims_stock_summaries';

    protected $fillable = [
        'style_name',
        'item_id',
        'uom_id',
        'receive_qty',
        'receive_return_qty',
        'issue_qty',
        'issue_return_qty',
        'balance',
        'balance_amount',
        'receive_amount',
        'transfer',
        'meta',
        'transfer_meta',
    ];

    protected $casts = [
        'meta' => Json::class
    ];

    public function updateBalance()
    {
        $inwardQty = $this->receive_qty + $this->issue_return_qty;
        $outwardQty = $this->receive_return_qty + $this->issue_qty;
        $this->balance = $inwardQty - $outwardQty;
        $this->save();
    }
}
