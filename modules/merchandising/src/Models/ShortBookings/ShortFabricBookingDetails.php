<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ShortFabricBookingDetails extends Model
{
    use SoftDeletes;

    protected $table = 'short_fabric_booking_details';

    protected $fillable = [
        'short_booking_id',
        'unique_id',
        'style_name',
        'po_no',
        'item_name',
        'item_id',
        'body_part_id',
        'body_part_value',
        'body_part_type',
        'fabric_composition_id',
        'fabric_composition_value',
        'construction',
        'composition',
        'supplier_id',
        'supplier_value',
        'gsm',
        'fabric_nature_id',
        'fabric_source_value',
        'fabric_source',
        'uom',
        'uom_value',
        'level',
        'fabric_nature_value',
        'breakdown',
        'color_type_id',
        'color_type_value',
        'dia_type',
        'dia_type_value',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'breakdown' => Json::class,
    ];

    public function shortFabricBooking(): BelongsTo
    {
        return $this->belongsTo(ShortFabricBooking::class, 'short_booking_id')->withDefault();
    }
}
