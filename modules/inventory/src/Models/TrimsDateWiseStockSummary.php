<?php


namespace SkylarkSoft\GoRMG\Inventory\Models;


use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class TrimsDateWiseStockSummary extends Model
{

    protected $table = 'trims_date_wise_stock_summaries';

    protected $fillable = [
        'style_name',
        'item_id',
        'uom_id',
        'date',
        'receive_qty',
        'receive_return_qty',
        'issue_qty',
        'issue_return_qty',
        'rate',
        'meta'
    ];

    protected $casts = [
        'meta' => Json::class
    ];

}