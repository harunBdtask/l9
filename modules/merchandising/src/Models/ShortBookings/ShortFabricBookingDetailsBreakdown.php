<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoiceDetails;

class ShortFabricBookingDetailsBreakdown extends Model
{
    use SoftDeletes;

    protected $table = 'short_fabric_booking_details_breakdown';

    /**
     *  adj qty only update for three time.
     *
     *  when create adj qty it inserts into first adj qty,
     *  when second time update it will add into second adj qty,
     *  and three adj qty for third time update.
     *
     *  (first_adj_qty + second_adj_qty + three_adj_qty) update into adj qty
     */

    protected $fillable = [
        'short_booking_id',
        'job_no',
        'po_no',
        'body_part_value',
        'body_part_id',
        'color_type_id',
        'color_type_value',
        'dia_type',
        'dia_type_value',
        'construction',
        'composition',
        'gsm',
        'item_color',
        'gmt_color',
        'color',
        'color_id',
        'size',
        'size_id',
        'dia',
        'process_loss',
        'balance_qty',
        'wo_qty',
        'first_adj_qty',
        'second_adj_qty',
        'third_adj_qty',
        'adj_qty_status',
        'adj_qty',
        'actual_wo_qty',
        'uom_value',
        'uom',
        'rate',
        'amount',
        'total_qty',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function shotFabricBooking(): BelongsTo
    {
        return $this->belongsTo(ShortFabricBookingDetails::class, 'short_booking_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'job_no', 'job_no')->withDefault();
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'job_no', 'job_no')->withDefault();
    }
    public function piDetails()
    {
        return $this->hasMany(ProformaInvoiceDetails::class, 'booking_details_id');
    }
}
