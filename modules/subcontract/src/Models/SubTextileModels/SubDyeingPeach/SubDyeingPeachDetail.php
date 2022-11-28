<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingPeach;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class SubDyeingPeachDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'sub_dyeing_peach_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sub_dyeing_peach_id',
        'sub_dyeing_batch_id',
        'sub_dyeing_batch_no',
        'sub_dyeing_batch_details_id',
        'sub_textile_order_id',
        'sub_textile_order_no',
        'sub_textile_order_details_id',
        'production_date',
        'fabric_composition_id',
        'fabric_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'fabric_description',
        'color_id',
        'color_type_id',
        'batch_qty',
        'order_qty',
        'no_of_roll',
        'finish_qty',
        'reject_roll',
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
        return isset($this->attributes['dia_type_id']) ? DiaTypesService::get($this->attributes['dia_type_id']) : null;
    }

    public function getFabricCompositionValueAttribute(): ?string
    {
        return isset($this->attributes['fabric_composition_id']) ?
            FabricDescriptionService::description($this->attributes['fabric_composition_id']) : null;
    }

    /*------------------------------------------------ Start Relations -----------------------------------------------*/

    public function peach(): BelongsTo
    {
        return $this->belongsTo(
            SubDyeingPeach::class,
            'sub_dyeing_peach_id',
            'id'
        )->withDefault();
    }

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatch::class, 'sub_dyeing_batch_id', 'id')
            ->withDefault();
    }

    public function subTextileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id', 'id')
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

    /*------------------------------------------------- End Relations ------------------------------------------------*/
}
