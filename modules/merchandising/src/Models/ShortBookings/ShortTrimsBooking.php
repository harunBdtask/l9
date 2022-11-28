<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings;

use App\Casts\Json;
use App\FactoryIdTrait;
use App\Helpers\UniqueCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;


class ShortTrimsBooking extends Model
{
    use SoftDeletes;

    protected $table = 'short_trims_bookings';

    const TYPE = 'short';

    const BOOKING_BASIS = [
        1 => 'Independent With Buyer Style',
        2 => 'Independent Without Buyer Style',
        3 => 'Budget'
    ];
    const MATERIAL_SOURCE = [
        1 => 'Purchase',
        2 => 'Buyer Supplier'
    ];

    public const APPROVED = 1;

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
        'is_approved',
        'step',
        'un_approve_request'
    ];

    protected $appends = [
        'pay_mode_value',
        'source_value',
        'booking_basis_value',
        'material_source_value',
        'budget_job_no',
        'year'
    ];

    public function getPayModeValueAttribute(): string
    {
        return TrimsBooking::PAY_MODE[$this->pay_mode] ?? '';
    }

    public function getSourceValueAttribute(): string
    {
        return TrimsBooking::SOURCE[$this->source] ?? '';
    }

    public function getBookingBasisValueAttribute(): string
    {
        return self::BOOKING_BASIS[$this->booking_basis] ?? '';
    }

    public function getMaterialSourceValueAttribute(): string
    {
        return self::MATERIAL_SOURCE[$this->material_source] ?? '';
    }

    protected $casts = [
        "terms_condition" => Json::class,
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->unique_id = UniqueCodeGenerator::generate('STB', $model->id);
            $model->save();
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
        return $this->hasMany(ShortTrimsBookingDetails::class, 'short_booking_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ShortTrimsBookingDetails::class, 'short_booking_id');
    }


    public function bookingDateYear(): string
    {
        return Carbon::parse($this->booking_date)->format('Y');
    }

    public function getBudgetJobNoAttribute($value): string
    {
        return $this->details()->pluck('budget_unique_id')->unique()->implode(',');
    }

    public function getYearAttribute(): ?int
    {
        if (!$this->booking_date) {
            return null;
        }
        return Carbon::parse($this->booking_date)->year;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
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
                        ->where('is_approved', '=', null)
                        ->where('step', $previousStep);
                })->when($request->get('approvalType') == 2, function ($query) use ($step) {
                    $query->where('step', $step);
                });
            });
    }
}
