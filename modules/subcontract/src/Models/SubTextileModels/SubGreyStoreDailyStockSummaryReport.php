<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubContractGreyStore;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class SubGreyStoreDailyStockSummaryReport extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    public const DIA_TYPES = [
        1 => 'Open',
        2 => 'Tubular',
        3 => 'Needle Open',
    ];

    protected $table = 'sub_grey_store_daily_stock_summary_reports';
    protected $primaryKey = 'id';
    protected $fillable = [
        'production_date',
        'factory_id',
        'supplier_id',
        'sub_grey_store_id',
        'sub_textile_operation_id',
        'body_part_id',
        'fabric_composition_id',
        'fabric_type_id',
        'color_id',
        'ld_no',
        'color_type_id',
        'finish_dia',
        'dia_type_id',
        'gsm',
        'material_description',
        'receive_qty',
        'receive_return_qty',
        'issue_qty',
        'issue_return_qty',
        'receive_transfer_qty',
        'transfer_qty',
        'total_issue_roll',
        'return_issue_roll',
        'total_receive_roll',
        'return_receive_roll',
        'unit_of_measurement_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public $appends = [
        'dia_type_value',
    ];

    public function getDiaTypeValueAttribute(): string
    {
        return self::DIA_TYPES[$this->attributes['dia_type_id']] ?? '';
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id', 'id')->withDefault();
    }

    public function subGreyStore(): BelongsTo
    {
        return $this->belongsTo(SubContractGreyStore::class, 'sub_grey_store_id', 'id');
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
}
