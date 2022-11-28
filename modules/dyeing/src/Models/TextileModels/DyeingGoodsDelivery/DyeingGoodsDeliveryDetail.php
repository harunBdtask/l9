<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery\DyeingGoodsDelivery;

class DyeingGoodsDeliveryDetail extends Model
{
    use SoftDeletes, CommonModelTrait, BelongsToFactory;

    protected $table = 'dyeing_goods_delivery_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'dyeing_goods_delivery_id',
        'dyeing_batch_id',
        'dyeing_batch_no',
        'dyeing_batch_details_id',
        'textile_order_id',
        'textile_order_no',
        'textile_order_details_id',
        'delivery_date',
        'fabric_composition_id',
        'fabric_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'fabric_description',
        'color_id',
        'color_type_id',
        'req_order_qty',
        'total_roll',
        'reject_roll',
        'reject_qty',
        'delivery_qty',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'dia_type_value',
        'fabric_composition_value',
    ];

    public function getDiaTypeValueAttribute()
    {
        return isset($this->attributes['dia_type_id']) ? DiaTypesService::get($this->attributes['dia_type_id'])['name'] : null;
    }

    public function getFabricCompositionValueAttribute(): ?string
    {
        return isset($this->attributes['fabric_composition_id']) ?
            FabricDescriptionService::description($this->attributes['fabric_composition_id']) : null;
    }

    public function dyeingGoodsDelivery(): BelongsTo
    {
        return $this->belongsTo(DyeingGoodsDelivery::class, 'dyeing_goods_delivery_id')->withDefault();
    }

    public function dyeingBatch(): BelongsTo
    {
        return $this->belongsTo(DyeingBatch::class, 'dyeing_batch_id', 'id')
            ->withDefault();
    }

    public function textileOrder(): BelongsTo
    {
        return $this->belongsTo(TextileOrder::class, 'textile_order_id', 'id')
            ->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function fabricType(): BelongsTo
    {
        return $this->belongsTo(FabricConstructionEntry::class, 'fabric_type_id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }
}
