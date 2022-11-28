<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Models\V3;

use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\TrimsStore\Traits\CommonBooted;

class TrimsStoreDailyStockSummary extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'v3_trims_store_daily_stock_summaries';
    protected $primaryKey = 'id';
    protected $fillable = [
        'transaction_date',
        'factory_id',
        'buyer_id',
        'style_id',
        'garments_item_id',
        'garments_item_name',
        'item_id',
        'item_description',
        'sensitivity_id',
        'supplier_id',
        'color_id',
        'size_id',
        'uom_id',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'bin_id',
        'receive_qty',
        'receive_reject_qty',
        'receive_return_qty',
        'issue_qty',
        'issue_reject_qty',
        'issue_return_qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
