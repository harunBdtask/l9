<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class SubDyeingHtSetDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = "sub_dyeing_ht_set_details";
    protected $primaryKey = "id";
    protected $fillable = [
        "sub_dyeing_ht_set_id",
        "order_id",
        "order_no",
        "batch_id",
        "batch_no",
        "batch_details_id",
        "order_details_id",
        "production_date",
        "fabric_description",
        "fabric_composition_id",
        "fabric_type_id",
        "finish_dia",
        "dia_type_id",
        "gsm",
        "color_id",
        "color_type_id",
        "req_order_qty",
        "fin_no_of_roll",
        "finish_qty",
        "reject_roll_qty",
        "reject_qty",
        "unit_cost",
        "total_cost",
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

    public function subDyeingHtSet(): BelongsTo
    {
        return $this->belongsTo(SubDyeingHtSet::class, 'sub_dyeing_ht_set_id')->withDefault();
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
        return $this->belongsTo(SubDyeingBatch::class, 'batch_id')->withDefault();
    }

    public function subDyeingBatchDetail(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatchDetail::class, 'batch_details_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }
}
