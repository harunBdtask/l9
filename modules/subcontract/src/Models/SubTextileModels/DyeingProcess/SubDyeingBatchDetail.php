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
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubContractGreyStore;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileProcess;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingGoodsDeliveryDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class SubDyeingBatchDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_batch_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'sub_dyeing_batch_id',
        'supplier_id',
        'sub_textile_order_id',
        'sub_textile_order_detail_id',
        'sub_grey_store_id',
        'sub_dyeing_unit_id',
        'sub_textile_operation_id',
        'sub_textile_process_id',
        'fabric_composition_id',
        'fabric_type_id',
        'color_id',
        'ld_no',
        'color_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'material_description',
        'yarn_details',
        'grey_required_qty',
        'unit_of_measurement_id',
        'stitch_length',
        'batch_roll',
        'issue_qty',
        'batch_weight',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'yarn_details' => Json::class,
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
//        return isset($this->attributes['fabric_composition_id'])
//            // ? FabricDescriptionService::description($this->attributes['fabric_composition_id'])
//            ? FabricCompositionService::description($this->attributes['fabric_composition_id'])
//            : null;

        return $this->subTextileOrderDetail->fabric_description;
    }

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatch::class, 'sub_dyeing_batch_id', 'id')
            ->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    public function subTextileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id')->withDefault();
    }

    public function subTextileOrderDetail(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrderDetail::class, 'sub_textile_order_detail_id')
            ->withDefault();
    }

    public function subGreyStore(): BelongsTo
    {
        return $this->belongsTo(SubContractGreyStore::class, 'sub_grey_store_id')->withDefault();
    }

    public function subDyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'sub_dyeing_unit_id')->withDefault();
    }

    public function subTextileOperation(): BelongsTo
    {
        return $this->belongsTo(SubTextileOperation::class, 'sub_textile_operation_id')->withDefault();
    }

    public function subTextileProcess(): BelongsTo
    {
        return $this->belongsTo(SubTextileProcess::class, 'sub_textile_process_id')->withDefault();
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

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_of_measurement_id')->withDefault();
    }

    public function subDyeingProductionDetail()
    {
        return $this->hasOne(SubDyeingProductionDetail::class, 'batch_details_id')->withDefault();
    }

    public function subDyeingGoodsDeliveryDetails(): HasMany
    {
        return $this->hasMany(SubDyeingGoodsDeliveryDetail::class, 'batch_details_id');
    }

    public function goodsDeliveryDetails(): HasManyThrough
    {
        return $this->hasManyThrough(
            SubDyeingGoodsDeliveryDetail::class,
            SubDyeingBatch::class,
            'id',
            'batch_id',
            'sub_dyeing_batch_id',
        );
    }
}
