<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
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
 * @property $balance
 * @property $balance_amount
 * @property $receive_amount;
 * @property $issue_amount;
 * @property $receive_return_amount;
 * @property $issue_return_amount;
 * @property $meta
 * @property $store_id
 * @property $transferred_from
 * @property $transfer_qty
 */
class YarnStockSummary extends Model
{
    use ModelCommonTrait;
    protected $table = 'yarn_stock_summaries';

    protected $fillable = [
        'factory_id',
        'yarn_count_id',
        'yarn_composition_id',
        'yarn_type_id',
        'yarn_color',
        'yarn_brand',
        'yarn_lot',
        'uom_id',
        'receive_qty',
        'receive_return_qty',
        'receive_return_amount',
        'issue_qty',
        'issue_amount',
        'issue_return_qty',
        'issue_return_amount',
        'balance',
        'balance_amount',
        'receive_amount',
        'store_id',
        'transferred_from',
        'transfer_qty',
        'meta',
        'receive_return_qty',
        'issue_qty',
        'issue_return_qty',
    ];

    protected $casts = [
        'meta' => Json::class
    ];

    public function yarnBalance()
    {
        return $this->inwardQty() - $this->outwardQty();
    }

    public function inwardQty()
    {
        return $this->attributes['receive_qty'] + $this->attributes['issue_return_qty'];
    }

    public function outwardQty()
    {
        return $this->attributes['issue_qty'] + $this->attributes['receive_return_qty'];
    }

    public function yarnRate()
    {
        return $this->attributes['receive_amount'] / $this->attributes['receive_qty'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function composition(): BelongsTo
    {
        return $this->belongsTo(YarnComposition::class, 'yarn_composition_id')->withDefault();
    }

    public function yarnCount(): BelongsTo
    {
        return $this->belongsTo(YarnCount::class, 'yarn_count_id')->withDefault();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CompositionType::class, 'yarn_type_id')->withDefault();
    }

}
