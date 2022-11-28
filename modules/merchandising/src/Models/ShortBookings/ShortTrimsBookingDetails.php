<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;

class ShortTrimsBookingDetails extends Model
{
    use HasFactory;

    protected $table = "short_trims_booking_details";
    protected $primaryKey = "id";
    protected $fillable = [
        'short_booking_id',
        'item_id',
        'nominated_supplier_id',
        'budget_unique_id',
        'style_name',
        'po_no',
        'item_name',
        'item_description',
        'total_qty',
        'cons_uom_value',
        'cons_uom_id',
        'current_work_order_qty',
        'total_amount',
        'balance_amount',
        'balance_qty',
        'sensitivity',
        'work_order_qty',
        'work_order_rate',
        'work_order_amount',
        'breakdown',
        'details',
    ];

    protected $casts = [
        'breakdown' => Json::class,
        'details' => Json::class,
    ];

    protected $appends = [
        'current_work_order',
        'balance',
    ];

    public function budget(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_unique_id', 'job_no');
    }

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ShortTrimsBooking::class, 'short_booking_id');
    }

    public function getCurrentWorkOrderAttribute()
    {
        return ShortTrimsBookingItemDetails::where([
            'item_id' => $this->item_id,
            'budget_unique_id' => $this->budget_unique_id,
        ])->sum('qty');
    }

    public function getBalanceAttribute()
    {
        return $this->total_qty - $this->current_work_order;
    }
}
