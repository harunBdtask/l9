<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileProcess;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingFinishingProduction\SubDyeingFinishingProductionDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class SubTextileOrderDetail extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = "sub_textile_order_details";

    protected $fillable = [
        'uuid',
        'factory_id',
        'supplier_id',
        'order_no',
        'sub_textile_order_id',
        'sub_textile_operation_id',
        'sub_textile_process_id',
        'operation_description',
        'body_part_id',
        'fabric_composition_id',
        'fabric_type_id',
        'color_id',
        'ld_no',
        'color_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'fabric_description',
        'yarn_details',
        'customer_buyer',
        'customer_style',
        'order_qty',
        'unit_of_measurement_id',
        'price_rate',
        'currency_id',
        'total_value',
        'conv_rate',
        'total_amount_bdt',
        'delivery_date',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'yarn_details' => Json::class,
    ];

    protected $appends = [
        'dia_type_value',
        'fabric_composition_value',
    ];

    const DIA_TYPE_OPTIONS = [
        '1' => "Open",
        '2' => "Tubular",
        '3' => "Needle Open",
    ];

    const OPEN_DIA = 1;
    const TUBULAR_DIA = 2;
    const NEEDLE_OPEN_DIA = 3;

    public function getDiaTypeAttribute()
    {
        return array_key_exists($this->attributes['dia_type_id'], self::DIA_TYPE_OPTIONS) ? self::DIA_TYPE_OPTIONS[$this->attributes['dia_type_id']] : null;
    }

    public function getDiaTypeValueAttribute()
    {
        return isset($this->attributes['dia_type_id']) ? DiaTypesService::get($this->attributes['dia_type_id'])['name'] : null;
    }

    public function getFabricCompositionValueAttribute(): ?string
    {
        return isset($this->attributes['fabric_composition_id']) ?
            FabricDescriptionService::description($this->attributes['fabric_composition_id']) : null;
    }

    public function subTextileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id')->withDefault();
    }

    public function subTextileOperation(): BelongsTo
    {
        return $this->belongsTo(SubTextileOperation::class, 'sub_textile_operation_id')->withDefault();
    }

    public function subTextileProcess(): BelongsTo
    {
        return $this->belongsTo(SubTextileProcess::class, 'sub_textile_process_id')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
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

    public function subGreyStoreIssueDetail(): HasOne
    {
        return $this->hasOne(SubGreyStoreIssueDetail::class, 'sub_textile_order_detail_id')->withDefault();
    }

    public function subGreyStoreReceiveDetail(): HasMany
    {
        return $this->hasMany(SubGreyStoreReceiveDetails::class, 'sub_textile_order_detail_id');
    }

    public function subDyeingBatchDetail(): HasOne
    {
        return $this->hasOne(SubDyeingBatchDetail::class, 'sub_textile_order_detail_id')->withDefault();
    }

    public function subDyeingFinishingProductionDetail(): HasOne
    {
        return $this->hasOne(SubDyeingFinishingProductionDetail::class, 'sub_textile_order_details_id')->withDefault();
    }

    public function subDyeingProductionDetails(): HasOne
    {
        return $this->hasOne(SubDyeingProductionDetail::class, 'order_id')->withDefault();
    }

    public function subDyeingGoodsDeliveryDetail(): HasMany
    {
        return $this->hasMany(SubDyeingGoodsDeliveryDetail::class, 'order_id');
    }
}
