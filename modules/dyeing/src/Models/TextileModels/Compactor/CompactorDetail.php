<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Compactor;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatchDetail;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class CompactorDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'compactor_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'compactor_id',
        'textile_order_id',
        'textile_order_no',
        'textile_order_details_id',
        'dyeing_batch_id',
        'dyeing_batch_no',
        'dyeing_batch_details_id',
        'production_date',
        'fabric_description',
        'fabric_composition_id',
        'fabric_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'color_id',
        'color_type_id',
        'req_order_qty',
        'fin_no_of_roll',
        'finish_qty',
        'reject_roll_qty',
        'reject_qty',
        'unit_cost',
        'total_cost',
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

    public function compactor(): BelongsTo
    {
        return $this->belongsTo(Compactor::class, 'compactor_id')
            ->withDefault();
    }

    public function textileOrder(): BelongsTo
    {
        return $this->belongsTo(TextileOrder::class, 'textile_order_id')
            ->withDefault();
    }

    public function textileOrderDetail(): BelongsTo
    {
        return $this->belongsTo(TextileOrderDetail::class, 'textile_order_details_id')
            ->withDefault();
    }

    public function dyeingBatch(): BelongsTo
    {
        return $this->belongsTo(DyeingBatch::class, 'dyeing_batch_id')
            ->withDefault();
    }

    public function dyeingBatchDetail(): BelongsTo
    {
        return $this->belongsTo(DyeingBatchDetail::class, 'dyeing_batch_details_id')
            ->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')
            ->withDefault();
    }

}
