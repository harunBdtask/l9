<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;

class FabricStockSummary extends Model
{
    use ModelCommonTrait;

    protected $table = 'fabric_stock_summaries';
    protected $fillable = [
        'factory_id',
        'batch_no',
        'receive_id',
        'style_id',
        'body_part_id',
        'color_type_id',
        'color_id',
        'construction',
        'fabric_description',
        'uom_id',
        'fabric_composition_id',
        'dia',
        'ac_dia',
        'gsm',
        'ac_gsm',
        'store_id',
        'receive_qty',
        'receive_return_qty',
        'issue_qty',
        'issue_return_qty',
        'transfer',
        'balance',
        'balance_amount',
        'receive_amount',
        'gmts_item_id',
        'created_at',
        'updated_at',
    ];
}
