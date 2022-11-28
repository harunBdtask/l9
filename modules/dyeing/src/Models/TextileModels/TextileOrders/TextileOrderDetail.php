<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Illuminate\Database\Eloquent\Relations\HasOne;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileProcess;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatchDetail;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\TextileOrderDetailService;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingProduction\DyeingProductionDetail;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery\DyeingGoodsDeliveryDetail;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingFinishingProduction\DyeingFinishingProductionDetail;

class TextileOrderDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'textile_order_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'textile_order_id',
        'fabric_sales_order_detail_id',
        'sub_textile_operation_id',
        'sub_textile_process_id',
        'operation_description',
        'body_part_id',
        'fabric_composition_id',
        'fabric_type_id',
        'item_color_id',
        'gmt_color_id',
        'ld_no',
        'color_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'yarn_details',
        'customer_buyer',
        'customer_style',
        'order_qty',
        'uom_id',
        'price_rate',
        'total_value',
        'conv_rate',
        'total_amount_bdt',
        'delivery_date',
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
        return isset($this->attributes['dia_type_id'])
            ? DiaTypesService::get($this->attributes['dia_type_id'])['name']
            : null;
    }

    public function getFabricCompositionValueAttribute(): ?string
    {
        return isset($this->attributes['fabric_composition_id']) ?
            FabricDescriptionService::description($this->attributes['fabric_composition_id']) : null;
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TextileOrderDetailService::generateUniqueId();
            }
        });
    }

    public function fabricSalesOrderDetailId(): BelongsTo
    {
        return $this->belongsTo(FabricSalesOrderDetail::class, 'fabric_sales_order_detail_id')
            ->withDefault();
    }

    public function textileOrder(): BelongsTo
    {
        return $this->belongsTo(TextileOrder::class, 'textile_order_id')->withDefault();
    }

    public function subTextileOperation(): BelongsTo
    {
        return $this->belongsTo(SubTextileOperation::class, 'sub_textile_operation_id')->withDefault();
    }

    public function subTextileProcess(): BelongsTo
    {
        return $this->belongsTo(SubTextileProcess::class, 'sub_textile_process_id')->withDefault();
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
        return $this->belongsTo(CompositionType::class, 'fabric_type_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'item_color_id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function customerBuyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'customer_buyer')->withDefault();
    }

    public function customerStyle(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'customer_style')->withDefault();
    }

    public function dyeingBatchDetail(): HasOne
    {
        return $this->hasOne(DyeingBatchDetail::class, 'textile_order_detail_id')->withDefault();
    }

    public function dyeingFinishingProductionDetail(): HasOne
    {
        return $this->hasOne(DyeingFinishingProductionDetail::class, 'textile_order_details_id')->withDefault();
    }

    public function dyeingProductionDetails(): HasOne
    {
        return $this->hasOne(DyeingProductionDetail::class,'dyeing_order_id')->withDefault();
    }

    public function dyeingGoodsDeliveryDetail(): HasOne
    {
        return $this->hasOne(DyeingGoodsDeliveryDetail::class,'textile_order_id')->withDefault();
    }

}
