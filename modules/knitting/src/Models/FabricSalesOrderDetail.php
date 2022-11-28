<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\Process;

class FabricSalesOrderDetail extends Model
{
    use CommonModelTrait;

    protected $table = 'fabric_sales_order_details';

    protected $fillable = [
        'cus_buyer',
        'cus_style',
        'body_part_id',
        'color_type_id',
        'garments_item_id',
        'breakdown_id',
        'fabric_description',
        'fabric_sales_order_id',
        'fabric_composition_id',
        'fabric_gsm',
        'fabric_dia',
        'dia_type_id',
        'gmt_color_id',
        'gmt_color',
        'item_color_id',
        'item_color',
        'ld_no',
        'color_range',
        'color_range_id',
        'cons_uom',
        'booking_qty',
        'average_price',
        'amount',
        'prog_uom',
        'finish_qty',
        'process_loss',
        'gray_qty',
        'process_id',
        'fabric_nature',
        'fabric_nature_id',
        'remarks',
        'created_by',
        'updated_by'
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

    public function fabricSalesOrder(): BelongsTo
    {
        return $this->belongsTo(FabricSalesOrder::class, 'fabric_sales_order_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
    }

    public function fabricColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'gmt_color_id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'item_color_id')->withDefault();
    }

    public function programUOM(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'prog_uom')->withDefault();
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id')->withDefault();
    }
}
