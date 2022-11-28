<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;

class FabricDateWiseStockSummary extends Model
{
    use ModelCommonTrait;

    protected $table = 'fabric_date_wise_stock_summaries';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'date',
        'batch_no',
        'style_id',
        'body_part_id',
        'color_type_id',
        'gmts_item_id',
        'color_id',
        'construction',
        'fabric_description',
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
        'rate',
        'uom_id',
        'receive_qty',
        'rate',
        'created_at',
        'updated_at'
    ];

}
