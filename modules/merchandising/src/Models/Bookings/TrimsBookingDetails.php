<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Bookings;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;

class TrimsBookingDetails extends Model
{
    use HasFactory;

    const SENSITIVITY = [
        1 => 'As Per Gmts. Color',
        2 => 'Contrast Color',
        3 => 'Size Sensitivity',
        4 => 'Color & Size Sensitivity',
    ];

    protected $table = "trims_booking_details";
    protected $primaryKey = "id";
    protected $fillable = [
        'booking_id',
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
        'previous_booking_qty',
        'balance',
        'unique_budget_internal_ref'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(TrimsBooking::class, 'booking_id');
    }

    public function itemGroup(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function getCurrentWorkOrderAttribute()
    {
        $query = TrimsBookingItemDetails::where([
            'item_id' => $this->item_id,
            'budget_unique_id' => $this->budget_unique_id,
            'po_no' => $this->po_no
        ]);
        return $query->sum('qty');
    }

    public function getPreviousBookingQtyAttribute()
    {
        return TrimsBookingItemDetails::where([
            'item_id' => $this->item_id,
            'budget_unique_id' => $this->budget_unique_id,
            'po_no' => $this->po_no
        ])->sum('qty');
    }

    public function getUniqueBudgetInternalRefAttribute()
    {
        return $this->budget()->distinct()->get()->implode('internal_ref', ',');
    }

    public function getBalanceAttribute()
    {
        return format($this->total_qty - $this->current_work_order);
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_unique_id', 'job_no')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'style_name', 'style_name')->withDefault();
    }
}
