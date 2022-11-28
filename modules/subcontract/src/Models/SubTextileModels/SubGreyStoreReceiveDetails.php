<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Casts\Json;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class SubGreyStoreReceiveDetails extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = "sub_grey_store_receive_details";
    protected $fillable = [
        'factory_id',
        'sub_textile_operation_id',
        'sub_grey_store_receive_id',
        'supplier_id',
        'sub_textile_order_id',
        'sub_textile_order_detail_id',
        'sub_grey_store_id',
        'challan_no',
        'challan_date',
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
        'grey_required_qty',
        'unit_of_measurement_id',
        'total_roll',
        'receive_qty',
        'return_roll',
        'receive_return_qty',
        'remarks',
    ];

    protected $casts = [
        'yarn_details' => Json::class,
    ];

    protected $appends = [
        'dia_type_value',
        'fabric_composition_value',
    ];

    const DYEING = 1;

    public function getDiaTypeValueAttribute()
    {
        return isset($this->attributes['dia_type_id']) ? DiaTypesService::get($this->attributes['dia_type_id']) : null;
    }

    public function getFabricCompositionValueAttribute(): ?string
    {
        return isset($this->attributes['fabric_composition_id']) ?
            FabricDescriptionService::description($this->attributes['fabric_composition_id']) : null;
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

    public function operation(): BelongsTo
    {
        return $this->belongsTo(SubTextileOperation::class, 'sub_textile_operation_id')->withDefault();
    }

    public function subDyeingOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id', 'id')
            ->withDefault();
    }

    public function subDyeingOrderDetail(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrderDetail::class, 'sub_textile_order_detail_id', 'id')
            ->withDefault();
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
    }

    public function textileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id', 'id')
            ->withDefault();
    }

    public function barcodes(): HasMany
    {
        return $this->hasMany(SubGreyStoreBarcodeDetail::class, 'sub_grey_store_receive_detail_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

//    public function subDyeingBatchDetails(): HasMany
//    {
//        return $this->hasMany(
//            SubDyeingBatchDetail::class,
//            'sub_textile_order_detail_id',
//            'sub_textile_order_detail_id'
//        )->when($this->attributes, function (Builder $query) {
//            $query->where('material_description', $this->attributes['fabric_description'])
//                ->where('color_id', $this->attributes['color_id'])
//                ->where('fabric_type_id', $this->attributes['fabric_type_id']);
//        });
//    }

    public function subDyeingBatchDetails(): HasManyThrough
    {
        return $this->hasManyThrough(
            SubDyeingBatchDetail::class,
            SubTextileOrderDetail::class,
            'id',
            'sub_textile_order_detail_id',
            'sub_textile_order_detail_id',
        );
    }

    public function subDyeingGoodsDeliveryDetails(): HasMany
    {
        return $this->hasMany(
            SubDyeingGoodsDeliveryDetail::class,
            'order_details_id',
            'sub_textile_order_detail_id'
        )->whereNull('batch_details_id');
    }

    public function subGreyStoreReceive(): BelongsTo
    {
        return $this->belongsTo(SubGreyStoreReceive::class, 'sub_grey_store_receive_id')->withDefault();
    }
}
