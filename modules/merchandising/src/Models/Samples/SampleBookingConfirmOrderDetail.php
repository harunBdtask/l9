<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Samples;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class SampleBookingConfirmOrderDetail extends Model
{
    use SoftDeletes;

    protected $table = 'sample_booking_confirm_order_details';

    const FABRIC_SOURCES = [
        1 => 'Production',
        2 => 'Purchase',
        3 => 'Buyer Supplier',
        4 => 'Stock'
    ];

    protected $casts = [
        'po_id'     => Json::class,
        'sample_id' => Json::class
    ];

    protected $fillable = [
        'requisition_id',
        'requisition_detail_id',
        'po_id',
        'sample_id',
        'gmts_item_id',
        'fabric_nature_id',
        'gmts_color_id',
        'color_type_id',
        'fabric_description_id',
        'fabric_source_id',
        'body_part_id',
        'dia',
        'gsm',
        'uom_id',
        'required_qty',
        'process_loss',
        'rate',
        'total_qty',
        'amount',
        'remarks'
    ];

    protected $appends = [
        'fabric_source_value'
    ];

    public function getFabricSourceValueAttribute(): string
    {
        $value = $this->attributes['fabric_source_id'];
        if ( array_key_exists($value, self::FABRIC_SOURCES) ) return self::FABRIC_SOURCES[$value];
        return '';
    }

    public function gmtsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'gmts_item_id');
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
    }

    public function fabricNature(): BelongsTo
    {
        return $this->belongsTo(FabricNature::class, 'fabric_nature_id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }
}