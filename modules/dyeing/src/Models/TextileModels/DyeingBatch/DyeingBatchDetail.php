<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch;

use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
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
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrderDetail;

class DyeingBatchDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'dyeing_batch_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'dyeing_batch_id',
        'textile_order_id',
        'textile_order_no',
        'textile_order_detail_id',
        'sub_textile_operation_id',
        'sub_textile_process_id',
        'fabric_composition_id',
        'fabric_type_id',
        'body_part_id',
        'color_id',
        'ld_no',
        'color_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'fabric_description',
        'yarn_details',
        'uom_id',
        'stitch_length',
        'batch_roll',
        'order_qty',
        'batch_weight',
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

    public function dyeingBatch(): BelongsTo
    {
        return $this->belongsTo(DyeingBatch::class, 'dyeing_batch_id', 'id')
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
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function textileOrderDetail(): BelongsTo
    {
        return $this->belongsTo(TextileOrderDetail::class, 'textile_order_detail_id')
            ->withDefault();
    }

}
