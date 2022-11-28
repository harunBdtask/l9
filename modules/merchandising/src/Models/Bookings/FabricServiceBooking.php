<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\Bookings;

use App\Helpers\UniqueCodeGenerator;
use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\BelongsToSupplier;
use App\Models\CommonModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Process;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class FabricServiceBooking extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToBuyer;
    use BelongsToFactory;
    use BelongsToSupplier;

    protected $table = 'fabric_service_bookings';

    protected $fillable = [
        'booking_no',
        'factory_id',
        'buyer_id',
        'supplier_id',
        'booking_date',
        'delivery_date',
        'pay_mode',
        'source',
        'exchange_rate',
        'attention',
        'label',
        'ready_to_approve',
        'unapproved_request',
        'currency',
        'process',
        'delivery_date',
        'created_by',
        'updated_by',
        'deleted_by',
        'step',
        'rework_status',
        'cancel_status',
    ];

    protected $appends = ['budget_job_no'];

    public function approve()
    {
        $this->attributes['is_approved'] = 1;
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->booking_no = UniqueCodeGenerator::generate('FSB', $model->id);
            $model->save();
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(FabricServiceBookingDetail::class, 'service_booking_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function processInfo(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process')->withDefault();
    }

    public function setBookingDateAttribute($value)
    {
        $this->attributes['booking_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function setDeliveryDateAttribute($value)
    {
        $this->attributes['delivery_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function getBookingDateAttribute()
    {
        if ($this->attributes['booking_date']) {
            return Carbon::make($this->attributes['booking_date'])->format('d-m-Y');
        }
        return null;
    }

    public function getDeliveryDateAttribute()
    {
        if ($this->attributes['delivery_date']) {
            return Carbon::make($this->attributes['delivery_date'])->format('d-m-Y');
        }
        return null;
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function getBudgetJobNoAttribute($value): string
    {
        return $this->details()->get()->map(function ($detail) {
            return $detail->budget->job_no;
        })->implode(',');
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
                return $q->where('booking_no', $request->get('booking_no'));
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
                        ->where('is_approved', '=', 0)
                        ->where('step', $previousStep);
                })->when($request->get('approvalType') == 2, function ($query) use ($step) {
                    $query->where('step', $step);
                });
            });
    }

}
