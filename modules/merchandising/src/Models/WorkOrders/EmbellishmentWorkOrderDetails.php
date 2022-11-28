<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\EmbellishmentItem;

class EmbellishmentWorkOrderDetails extends Model
{
    protected $table = "embellishment_work_order_details";
    protected $primaryKey = "id";
    protected $fillable = [
        'embellishment_work_order_id',
        'budget_unique_id',
        'po_no',
        'style',
        'embellishment_id',
        'embellishment_type_id',
        'body_part_id',
        'total_qty',
        'current_work_order_qty',
        'total_amount',
        'balance_amount',
        'sensitivity',
        'work_order_qty',
        'balance_qty',
        'work_order_rate',
        'work_order_amount',
        'breakdown',
        'details',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'current_work_order',
        'balance',
    ];

    protected $casts = [
        'breakdown' => Json::class,
        'details' => Json::class,
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function getCurrentWorkOrderAttribute()
    {
        return EmbellishmentBookingItemDetails::where([
            'item_id' => $this->embellishment_id,
            'item_type_id' => $this->embellishment_type_id,
            'budget_unique_id' => $this->budget_unique_id,
        ])->sum('qty');
    }

    public function getBalanceAttribute()
    {
        return $this->total_qty - $this->current_work_order;
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_unique_id', 'job_no');
    }

    public function embellishment(): BelongsTo
    {
        return $this->belongsTo(EmbellishmentItem::class, 'embellishment_id')->withDefault();
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(EmbellishmentWorkOrder::class, 'embellishment_work_order_id');
    }

    public function embellishmentType(): BelongsTo
    {
        return $this->belongsTo(EmbellishmentItem::class, 'embellishment_type_id')->withDefault();
    }
}
