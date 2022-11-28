<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings;

use App\Casts\Json;
use App\Helpers\UniqueCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\PackageConst;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class ShortFabricBooking extends Model
{
    use SoftDeletes;

    protected $table = 'short_fabric_bookings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'unique_id',
        'budget_unique_id',
        'buyer_id',
        'supplier_id',
        'fabric_source',
        'factory_id',
        'booking_date',
        'delivery_date',
        'pay_mode',
        'source',
        'currency_id',
        'exchange_rate',
        'ready_to_approve',
        'responsible_person',
        'responsible_department',
        'attention',
        'level',
        'fabric_composition',
        'remarks',
        'terms_condition',
        'process_loss',
        'collar_cuff_info',
        'ready_to_approved',
        'un_approve_request',
        'details',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_approved',
        'step'
    ];

    protected $casts = [
        'terms_condition' => Json::class,
        'details' => Json::class,
        'process_loss' => Json::class,
        'collar_cuff_info' => Json::class,
    ];

    protected $appends = [
        'fabric_source_name', 'level_name', 'budget_job_no', 'year'
    ];

    public const APPROVED = 1;

    public function getYearAttribute(): ?int
    {
        if (!$this->booking_date) {
            return null;
        }
        return Carbon::parse($this->booking_date)->year;
    }

    public function setBookingDateAttribute($value)
    {
        $this->attributes['booking_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function setDeliveryDateAttribute($value)
    {
        $this->attributes['delivery_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function getBookingDateAttribute(): ?string
    {
        if ($this->attributes['booking_date']) {
            return Carbon::make($this->attributes['booking_date'])->format('d-m-Y');
        }
        return null;
    }

    public function getDeliveryDateAttribute(): ?string
    {
        if ($this->attributes['delivery_date']) {
            return Carbon::make($this->attributes['delivery_date'])->format('d-m-Y');
        }
        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->unique_id = UniqueCodeGenerator::generate('SFB', $model->id);
            $model->save();
        });

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    // 1 => Production, 2 => Purchase, 3 => Buyer, 4 => Supplier Stock
    public function getFabricSourceNameAttribute(): string
    {
        if (isset($this->fabric_source)) {
            switch ($this->fabric_source) {
                case 1:
                    return 'Production';
                case 2:
                    return 'Purchase';
                case 3:
                    return 'Buyer';
                case 4:
                    return 'Supplier Stock';
                default:
                    return '';
            }
        }

        return $this->fabric_source;
    }

    // 1 => Job Label, 2 => Po Label
    public function getLevelNameAttribute(): string
    {
        if (isset($this->level)) {
            switch ($this->level) {
                case 1:
                    return 'Style Label';
                case 2:
                    return 'PO Label';
                default:
                    return '';
            }
        }

        return $this->level;
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault();
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShortFabricBookingDetails::class, 'short_booking_id');
    }

    public function detailsBreakdown(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShortFabricBookingDetailsBreakdown::class, 'short_booking_id');
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function getBudgetJobNoAttribute($value): string
    {
        return $this->detailsBreakdown()->pluck('job_no')->unique()->implode(',');
    }

    public function scopeApprovalFilter($query, $request, $previousStep, $step)
    {
        return $query->when($request->get('factory'), function ($q) use ($request) {
            return $q->where('factory_id', $request->get('factory'));
        })
            ->when($request->get('buyer'), function ($q) use ($request) {
                return $q->where('buyer_id', $request->get('buyer'));
            })
            ->when($request->get('style'), function ($query) use ($request) {
                return $query->whereHas('details', function ($innerQuery) use ($request) {
                    return $innerQuery->where('style_name', $request->get('style'));
                });
            })
            ->when($request->get('year'), function ($q) use ($request) {
                return $q->whereYear('booking_date', $request->get('year'));
            })
            ->when($request->get('booking_no'), function ($q) use ($request) {
                return $q->where('unique_id', $request->get('booking_no'));
            })
            ->when($request->get('fromDate') && $request->get('toDate'), function ($q) use ($request) {
                return $q->whereBetween('booking_date', [$request->get('fromDate'), $request->get('toDate')]);
            })
            ->when($request->get('approvalType'), function ($query) use ($request, $previousStep, $step) {
                $query->when($request->get('approvalType') == 1, function ($query) use ($request, $previousStep) {
                    $query->where('ready_to_approved', $request->get('approvalType'))
                        ->where('is_approved', '=', null)
                        ->where('step', $previousStep);
                })->when($request->get('approvalType') == 2, function ($query) use ($step) {
                    $query->where('step', $step);
                });
            });
    }


    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_unique_id', 'job_no')->withDefault();
    }

    public function fabricSalesOrder(): HasMany
    {
        return $this->hasMany(FabricSalesOrder::class, 'booking_no', 'unique_id')
            ->where('booking_type', 'short');
    }
}
