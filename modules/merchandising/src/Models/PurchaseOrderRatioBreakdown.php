<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class PurchaseOrderRatioBreakdown extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $table = 'purchase_order_ratio_breakdowns';
    protected $primaryKey = 'id';
    protected $fillable = [
        'purchase_order_id',
        'item_id',
        'ratio',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
