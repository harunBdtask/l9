<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;

class AcSupplierItem extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'account_supplier_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'account_supplier_id',
        'item_group_id',
        'price_per_unit',
    ];

    public function itemDetail(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_group_id')->withDefault();
    }
}
