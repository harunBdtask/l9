<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess;

use App\Casts\Json;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipeDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProduction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubCompactorDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDryerDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingFinishingProduction\SubDyeingFinishingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingGoodsDeliveryDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingPeach\SubDyeingPeachDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingStenteringDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingTumble\SubDyeingTumbleDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubSlittingDetail;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\SubTextileBatchService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class SubDyeingBatch extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_batches';
    protected $primaryKey = 'id';
    protected $fillable = [
        'batch_uid',
        'batch_no',
        'batch_entry',
        'total_batch_weight',
        'factory_id',
        'supplier_id',
        'sub_dyeing_unit_id',
        'sub_textile_order_ids',
        'order_nos',
        'batch_date',
        'color_range_id',
        'fabric_composition_id',
        'fabric_type_id',
        'color_id',
        'ld_no',
        'color_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'material_description',
        'unit_of_measurement_id',
        'fabric_color',
        'process_loss',
        'total_machine_capacity',
        'buyer_rate',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'sub_textile_order_ids' => Json::class,
        'order_nos' => 'array',
    ];

    protected $appends = [
        'batch_entry_value',
    ];

    const BATCH_ENTRIES = [
        1 => 'Sample',
        2 => 'Bulk',
    ];

    public function getBatchEntryValueAttribute(): ?string
    {
        if (isset($this->attributes['batch_entry'])) {
            return self::BATCH_ENTRIES[$this->attributes['batch_entry']];
        }

        return null;
    }

    public function scopeSearch($query, Request $request)
    {
        $factory_id = $request->get('factory_id');
        $batch_uid = $request->get('batch_uid');
        $party_id = $request->get('party_id');
        $order_no = $request->get('order_no');
        $batch_no = $request->get('batch_no');
        $fabric_color = $request->get('fabric_color');
        $batch_weight = $request->get('batch_weight');
        $batch_date = $request->get('batch_date');

        return $query->when($factory_id, function ($q, $factory_id) {
            return $q->where('factory_id', $factory_id);
        })->when($batch_uid, function ($q, $batch_uid) {
            return $q->where('batch_uid', $batch_uid);
        })->when($party_id, function ($q, $party_id) {
            return $q->where('supplier_id', $party_id);
        })->when($order_no, function ($q, $order_no) {
            return $q->where('order_nos', 'LIKE', "%{$order_no}%");
        })->when($batch_no, function ($q, $batch_no) {
            return $q->where('batch_no', $batch_no);
        })->when($fabric_color, function ($q, $fabric_color) {
            return $q->where('fabric_color', $fabric_color);
        })->when($batch_weight, function ($q, $batch_weight) {
            return $q->where('total_batch_weight', $batch_weight);
        })->when($batch_date, function ($q, $batch_date) {
            return $q->where('batch_date', $batch_date);
        });
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->batch_uid = SubTextileBatchService::generateUniqueId();
            }
        });
    }

    public function batchDetails(): HasMany
    {
        return $this->hasMany(SubDyeingBatchDetail::class, 'sub_dyeing_batch_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    public function subDyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'sub_dyeing_unit_id')->withDefault();
    }

    public function fabricComposition(): BelongsTo
    {
        return $this->belongsTo(NewFabricComposition::class, 'fabric_composition_id')->withDefault();
    }

    public function fabricType(): BelongsTo
    {
        return $this->belongsTo(FabricConstructionEntry::class, 'fabric_type_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function fabricColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'fabric_color')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_of_measurement_id')->withDefault();
    }

    public function machineAllocations(): HasMany
    {
        return $this->hasMany(SubDyeingBatchMachineAllocation::class, 'sub_dyeing_batch_id', 'id');
    }

    public function subDyeingRecipe(): HasMany
    {
        return $this->hasMany(SubDyeingRecipe::class, 'batch_id', 'id');
    }

    public function batchBuyerRate(): HasMany
    {
        return $this->hasMany(BatchBuyerRate::class, 'batch_id', 'id');
    }

    public function subDyeingRecipeDetails(): HasManyThrough
    {
        return $this->hasManyThrough(
            SubDyeingRecipeDetail::class,
            SubDyeingRecipe::class,
            'batch_id',
            'sub_dyeing_recipe_id',
            'id'
        );
    }

    public function subDyeingProduction(): HasMany
    {
        return $this->hasMany(SubDyeingProduction::class, 'batch_id', 'id');
    }

    public function subDyeingProductionDetail(): HasMany
    {
        return $this->hasMany(SubDyeingProductionDetail::class, 'batch_id', 'id');
    }

    public function subDryerDetail(): HasMany
    {
        return $this->hasMany(SubDryerDetail::class, 'batch_id', 'id');
    }

    public function subSlittingDetail(): HasMany
    {
        return $this->hasMany(SubSlittingDetail::class, 'batch_id', 'id');
    }

    public function subDyeingStenteringDetail(): HasMany
    {
        return $this->hasMany(SubDyeingStenteringDetail::class, 'batch_id', 'id');
    }

    public function subDyeingTumbleDetails(): HasMany
    {
        return $this->hasMany(SubDyeingTumbleDetail::class, 'sub_dyeing_batch_id', 'id');
    }

    public function subDyeingPeachDetail(): HasMany
    {
        return $this->hasMany(SubDyeingPeachDetail::class, 'sub_dyeing_batch_id', 'id');
    }

    public function subCompactorDetail(): HasMany
    {
        return $this->hasMany(SubCompactorDetail::class, 'batch_id', 'id');
    }

    public function subDyeingFinishProductionDetail(): HasMany
    {
        return $this->hasMany(SubDyeingFinishingProductionDetail::class, 'sub_dyeing_batch_id', 'id');
    }

    public function subDyeingTubeCompactingDetail(): HasMany
    {
        return $this->hasMany(SubDyeingTubeCompactingDetail::class, 'batch_id', 'id');
    }

    public function subDyeingSqueezerDetail(): HasMany
    {
        return $this->hasMany(SubDyeingSqueezerDetail::class, 'batch_id', 'id');
    }

    public function subDyeingHtSetDetail(): HasMany
    {
        return $this->hasMany(SubDyeingHtSetDetail::class, 'batch_id', 'id');
    }

    public function subDyeingGoodsDeliveryDetail(): HasMany
    {
        return $this->hasMany(SubDyeingGoodsDeliveryDetail::class, 'batch_id', 'id');
    }
}
