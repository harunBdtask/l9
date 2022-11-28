<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

/**
 * @property $yarn_count_id
 * @property $yarn_composition_id
 * @property $yarn_type_id
 * @property $yarn_color
 * @property $yarn_lot
 * @property $uom_id
 * @property $receive_qty
 * @property $receive_return_qty
 * @property $issue_qty
 * @property $issue_return_qty
 * @property $rate
 */

class YarnDateWiseStockSummary extends Model
{
    use ModelCommonTrait;
    use Compoships;

    protected $table = 'yarn_date_wise_stock_summaries';

    protected $fillable = [
        'factory_id',
        'date',
        'store_id',
        'yarn_count_id',
        'yarn_composition_id',
        'yarn_type_id',
        'yarn_color',
        'yarn_brand',
        'yarn_lot',
        'uom_id',
        'receive_qty',
        'receive_return_qty',
        'issue_qty',
        'issue_return_qty',
        'rate',
        'transferred_from',
        'transfer_qty'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();
    }

    public function composition(): BelongsTo
    {
        return $this->belongsTo(YarnComposition::class, 'yarn_composition_id')->withDefault();
    }

    public function count(): BelongsTo
    {
        return $this->belongsTo(YarnCount::class, 'yarn_count_id')->withDefault();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CompositionType::class, 'yarn_type_id')->withDefault();
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function receiveDetails(): HasMany
    {
        return $this->hasMany(YarnReceiveDetail::class,
            ['yarn_count_id', 'yarn_composition_id', 'yarn_type_id', 'yarn_color', 'yarn_brand', 'yarn_lot', 'uom_id'],
            ['yarn_count_id', 'yarn_composition_id', 'yarn_type_id', 'yarn_color', 'yarn_brand', 'yarn_lot', 'uom_id']);
    }
}
