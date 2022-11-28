<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class SubDyeingGoodsDeliveryDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_goods_delivery_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sub_dyeing_goods_delivery_id',
        'batch_id',
        'batch_no',
        'batch_details_id',
        'order_id',
        'order_no',
        'order_details_id',
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
        'grey_weight_fabric',
        'reject_roll',
        'reject_qty',
        'delivery_qty',
        'rate',
        'total_value',
        'shade',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
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

    public function subDyeingGoodsDelivery(): BelongsTo
    {
        return $this->belongsTo(SubDyeingGoodsDelivery::class, 'sub_dyeing_goods_delivery_id')->withDefault();
    }

    public function subTextileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'order_id')->withDefault();
    }

    public function subTextileOrderDetail(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrderDetail::class, 'order_details_id')->withDefault();
    }

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatch::class, 'batch_id', 'id')->withDefault();
    }

    public function subDyeingBatchDetail(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatchDetail::class, 'batch_details_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function fabricType(): BelongsTo
    {
        return $this->belongsTo(FabricConstructionEntry::class, 'fabric_type_id')->withDefault();
    }

    public function fabricComposition(): BelongsTo
    {
        return $this->belongsTo(NewFabricComposition::class, 'fabric_composition_id')->withDefault();
    }
}
