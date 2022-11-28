<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Bookings;

use App\Casts\Json;
use App\FactoryIdTrait;
use App\Helpers\UniqueCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class TrimsBooking extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'trims_bookings';

    const TYPE = 'main';

    protected $fillable = [
        'factory_id',
        'location',
        'buyer_id',
        'booking_date',
        'delivery_date',
        'supplier_id',
        'source',
        'booking_basis',
        'trims_type',
        'material_source',
        'pay_mode',
        'exchange_rate',
        'level',
        'currency',
        'attention',
        'remarks',
        'delivery_to',
        'ready_to_approve',
        'terms_condition',
        'created_by',
        'updated_by',
        'deleted_by',
        'un_approve_request',
        'rework_status',
        'cancel_status',
    ];

    protected $casts = [
        "terms_condition" => Json::class,
    ];

    const PAY_MODE = [
        '1' => 'Credit',
        '2' => 'Import',
        '3' => 'In House',
        '4' => 'Within Group',
    ];

    const SOURCE = [
        '1' => 'Abroad',
        '2' => 'EPZ',
        '3' => 'Non-EPZ',
    ];

    const CARE_LABELS = [
        1 => 'Woven Satin Label',
        2 => 'Woven Label',
        3 => 'Paper Label',
    ];

    protected $appends = [
        'pay_mode_value',
        'source_value',
        'budget_job_no',
        'style',
        'item_description',
        'uom',
        'po_no',
        'rate',
        'amount',
        'total_trims_booking_qty'
    ];

    public const APPROVED = 1;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->unique_id = UniqueCodeGenerator::generate('TB', $model->id);
            $model->save();
        });

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault();
    }

    public function bookingDetails(): HasMany
    {
        return $this->hasMany(TrimsBookingDetails::class, 'booking_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsBookingDetails::class, 'booking_id');
    }

    public function getBudgetJobNoAttribute($value): string
    {
        return $this->details()->pluck('budget_unique_id')->unique()->implode(',');
    }

    public function getPayModeValueAttribute($attribute): string
    {
        return isset($this->attributes['pay_mode']) ? self::PAY_MODE[$this->attributes['pay_mode']] : '';
    }

    public function getSourceValueAttribute($attribute): string
    {
        return isset($this->attributes['source']) && $this->attributes['source'] > 0 ? self::SOURCE[$this->attributes['source']] : '';
    }

    public function getPayModeAttribute($attribute): string
    {
        return $attribute;
    }

    public function getItemDescriptionAttribute(): string
    {
        return $this->bookingDetails()->pluck('item_name')->unique()->implode(',');
    }

    public function getUomAttribute(): string
    {
        return $this->bookingDetails()->pluck('cons_uom_value')->unique()->implode(',');
    }

    public function getRateAttribute()
    {
        return $this->bookingDetails()->avg('work_order_rate');
    }

    public function getAmountAttribute()
    {
        return $this->bookingDetails()->sum('total_amount');
    }

    public function getTotalTrimsBookingQtyAttribute()
    {
        return $this->bookingDetails()->sum('total_qty');
    }

    public function getStyleAttribute()
    {
        return $this->bookingDetails()->pluck('style_name')->unique()->implode(', ');
    }

    public function getPoNoAttribute()
    {
        return $this->bookingDetails()->pluck('po_no')->unique()->implode(', ');
    }

    public function getSourceAttribute($attribute): string
    {
        return $attribute;
    }

    public function bookingDateYear(): string
    {
        return Carbon::parse($this->booking_date)->format('Y');
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }

    public function deliveryTo(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'delivery_to')->withDefault();
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
                return $query->whereHas('details', function ($innerQuery) use ($request) {
                    return $innerQuery->where('style_name', $request->get('style'));
                });
            })
            ->when($request->get('fromDate') && $request->get('toDate'), function ($q) use ($request) {
                return $q->whereBetween('booking_date', [$request->get('fromDate'), $request->get('toDate')]);
            })
            ->when($request->get('approvalType'), function ($query) use ($request, $previousStep, $step) {
                $query->when($request->get('approvalType') == 1, function ($query) use ($request, $previousStep) {
                    $query->where('ready_to_approve', $request->get('approvalType'))
                        ->where('is_approve', '=', null)
                        ->where('step', $previousStep);
                })->when($request->get('approvalType') == 2, function ($query) use ($step) {
                    $query->where('step', $step);
                });
            });
    }
}
