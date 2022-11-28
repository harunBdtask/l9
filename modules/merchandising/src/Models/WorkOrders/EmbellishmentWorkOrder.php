<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders;

use App\Helpers\UniqueCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class EmbellishmentWorkOrder extends Model
{
    use SoftDeletes;

    protected $table = 'embellishment_work_orders';

    protected $fillable = [
        'unique_id',
        'factory_id',
        'location',
        'buyer_id',
        'booking_date',
        'delivery_date',
        'supplier_id',
        'pay_mode',
        'source',
        'exchange_rate',
        'currency',
        'attention',
        'remarks',
        'is_short',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_approved',
        'ready_to_approve',
        'unapproved_request',
        'step',
    ];

    protected $appends = [
        'pay_mode_value',
        'source_value',
        'budget_job_no'
    ];

    public function getPayModeValueAttribute(): string
    {
        return array_key_exists($this->pay_mode, TrimsBooking::PAY_MODE) ? TrimsBooking::PAY_MODE[$this->pay_mode] : '';
    }

    public function getSourceValueAttribute(): string
    {
        return array_key_exists($this->source, TrimsBooking::SOURCE) ? TrimsBooking::SOURCE[$this->source] : '';
    }

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->unique_id = UniqueCodeGenerator::generate('EMB', $model->id);
            $model->save();
        });

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => Auth::id(),
                    ]);
            }
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault();
    }

    public function bookingDetails(): HasMany
    {
        return $this->hasMany(EmbellishmentWorkOrderDetails::class, 'embellishment_work_order_id');
    }

    public function bookingItemDetails(): HasMany
    {
        return $this->hasMany(EmbellishmentBookingItemDetails::class, 'embellishment_work_order_id');
    }

    public function getBudgetJobNoAttribute($value): string
    {
        return $this->bookingDetails()->pluck('budget_unique_id')->unique()->implode(',');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function scopeApprovalFilter($query, $request, $previousStep, $step)
    {
        return $query->when($request->get('factory'), function ($q) use ($request) {
            return $q->where('factory_id', $request->get('factory'));
        })
            ->when($request->get('buyer'), function ($q) use ($request) {
                return $q->where('buyer_id', $request->get('buyer'));
            })
            ->when($request->get('year'), function ($q) use ($request) {
                return $q->whereYear('booking_date', $request->get('year'));
            })
            ->when($request->get('booking_no'), function ($q) use ($request) {
                return $q->where('unique_id', $request->get('booking_no'));
            })
            ->when($request->get('style'), function ($query) use ($request) {
                return $query->whereHas('bookingDetails', function ($innerQuery) use ($request) {
                    return $innerQuery->where('style', $request->get('style'));
                });
            })
            ->when($request->get('fromDate') && $request->get('toDate'), function ($q) use ($request) {
                return $q->whereBetween('booking_date', [$request->get('fromDate'), $request->get('toDate')]);
            })
            ->when($request->get('approvalType'), function ($query) use ($request, $previousStep, $step) {
                $query->when($request->get('approvalType') == 1, function ($query) use ($request, $previousStep) {
                    $query->where('ready_to_approve', $request->get('approvalType'))
                        ->where('is_approved', '=', 0)
                        ->where('step', $previousStep);
                })->when($request->get('approvalType') == 2, function ($query) use ($step) {
                    $query->where('step', $step);
                });
            });
    }

}
