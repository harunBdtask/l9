<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileProcess;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class SubGreyStoreIssueDetail extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'sub_grey_store_issue_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'sub_grey_store_issue_id',
        'supplier_id',
        'sub_textile_order_id',
        'sub_textile_order_detail_id',
        'sub_grey_store_id',
        'sub_dyeing_unit_id',
        'challan_no',
        'challan_date',
        'body_part_id',
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
        'fabric_description',
        'yarn_details', // Json
        'grey_required_qty',
        'unit_of_measurement_id',
        'total_roll',
        'issue_qty',
        'return_roll',
        'issue_return_qty',
        'total_batch_assigned_qty',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    const DYEING = 1;

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
        return isset($this->attributes['fabric_composition_id']) ?
            FabricDescriptionService::description($this->attributes['fabric_composition_id']) : null;
    }

    public function subDyeingOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id', 'id')
            ->withDefault();
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

    public function subGreyStoreIssue(): BelongsTo
    {
        return $this->belongsTo(SubGreyStoreReceive::class, 'sub_grey_store_issue_id')->withDefault();
    }

    public function subGreyStoreOrderDetail(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrderDetail::class, 'sub_textile_order_detail_id')->withDefault();
    }
}
