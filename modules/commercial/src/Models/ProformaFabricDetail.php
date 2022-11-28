<?php

namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;

class ProformaFabricDetail extends Model
{
    use HasFactory;

    protected $table = "proforma_fabric_details";

    protected $fillable = [
        'proforma_invoice_id',
        'gsm',
        'uom',
        'uom_id',
        'rate',
        'type',
        'color',
        'color_id',
        'po_nos',
        'purchase_order_ids',
        'wo_no',
        'amount',
        'hs_code',
        'buyer_id',
        'buyer_name',
        'body_part',
        'body_part_id',
        'quantity',
        'dia',
        'dia_type',
        'dia_type_value',
        'unique_id', // fabric_bookings table unique_id column
        'booking_id',
        'details_id',
        'style_name',
        'composition',
        'construction',
        'style_unique_id', // orders table job_no column
        'order_id',
        'fabric_composition_id',
        'contrast_color_id',
        'contrast_colors',
        'garments_item_id',
        'garments_item',
        'color_type_id',
        'color_type',
    ];

    protected $casts = [
        'purchase_order_ids' => Json::class,
        'contrast_color_id' => Json::class,
    ];

    public function proformaInvoice(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class, 'proforma_invoice_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
    }

    public function fabricBooking(): BelongsTo
    {
        return $this->belongsTo(FabricBooking::class, 'booking_id')->withDefault();
    }

    public function fabricBookingDetailsBreakdown(): BelongsTo
    {
        return $this->belongsTo(FabricBookingDetailsBreakdown::class, 'details_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function newFabricComposition(): BelongsTo
    {
        return $this->belongsTo(NewFabricComposition::class, 'fabric_composition_id')->withDefault();
    }

    public function garmentsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }
}
