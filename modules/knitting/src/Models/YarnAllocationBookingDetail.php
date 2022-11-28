<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorRange;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Process;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class YarnAllocationBookingDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $fillable = [
        'yarn_allocation_id',
        'garments_item_id',
        'body_part_id',
        'color_type_id',
        'fabric_description',
        'fabric_gsm',
        'fabric_dia',
        'dia_type_id',
        'dia_type',
        'gmt_color_id',
        'gmt_color',
        'item_color_id',
        'item_color',
        'color_range_id',
        'color_range',
        'cons_uom',
        'booking_qty',
        'average_price',
        'amount',
        'prog_uom',
        'finish_qty',
        'process_loss',
        'gray_qty',
        'process_id',
        'fabric_nature_id',
        'fabric_nature',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function yarnAllocation(): BelongsTo
    {
        return $this->belongsTo(YarnAllocation::class, 'yarn_allocation_id')->withDefault();
    }

    public function garmentsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }

    public function gmtColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'gmt_color_id')->withDefault();
    }

    public function itemColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'item_color_id')->withDefault();
    }

    public function colorRange(): BelongsTo
    {
        return $this->belongsTo(ColorRange::class, 'color_range_id')->withDefault();
    }

    public function consUnitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'cons_uom')->withDefault();
    }

    public function progUnitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'prog_uom')->withDefault();
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id')->withDefault();
    }

    public function fabricNature(): BelongsTo
    {
        return $this->belongsTo(FabricNature::class, 'fabric_nature_id')->withDefault();
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    public function deletedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by')->withDefault();
    }
}
