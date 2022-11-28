<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\Bookings;

use App\Casts\Json;
use App\Helpers\UniqueCodeGenerator;
use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

/**
 * @property string fabric_source
 */
class FabricBooking extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'fabric_bookings';

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
        'internal_ref_no',
        'attention',
        'level',
        'booking_percent',
        'file_no',
        'fabric_composition',
        'remarks',
        'terms_condition',
        'process_loss',
        'collar_cuff_info',
        'ready_to_approved',
        'un_approve_request',
        'rework_status',
        'cancel_status',
        'details',
        'created_by',
        'updated_by',
        'deleted_by',
        'step',
        'attachment_note',
        'attachment',
        'control',
        'silicon_engyme_wash',
        'dealing_merchant',
    ];

    protected $casts = [
        'terms_condition' => Json::class,
        'details' => Json::class,
        'process_loss' => Json::class,
        'collar_cuff_info' => Json::class,
    ];

    protected $appends = [
        'fabric_source_name', 'level_name', 'style_name', 'budget_job_no', 'po_no', 'year', 'location',
        'item_description', 'uom', 'rate', 'amount', 'total_fabric_booking_qty'
    ];

    public const APPROVED = 1;

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->unique_id = UniqueCodeGenerator::generate('FB', $model->id);
            $model->save();
        });

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    protected $cascadeDeletes = ['details', 'detailsBreakdown'];

    public function getYearAttribute(): ?int
    {
        if (!$this->booking_date) {
            return null;
        }
        return Carbon::parse($this->booking_date)->year;
    }

    public function getLocationAttribute()
    {
        return $this->factory->factory_address;
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
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FabricBookingDetails::class, 'booking_id', 'id');
    }

    public function detailsBreakdown(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FabricBookingDetailsBreakdown::class, 'booking_id');
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_unique_id', 'job_no')->withDefault();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function getStyleNameAttribute($value): string
    {
        $budgetUniqueId = $this->detailsBreakdown()->pluck('job_no')->unique()->values();
        return Budget::query()
            ->whereIn('job_no', $budgetUniqueId)
            ->pluck('style_name')->unique()->implode(',');
    }

    public function getBudgetJobNoAttribute($value): string
    {
        return $this->detailsBreakdown()->pluck('job_no')->unique()->implode(',');
    }

    public function getPoNoAttribute(): string
    {
        return $this->detailsBreakdown()->pluck('po_no')->unique()->implode(',');
    }

    public function getItemDescriptionAttribute(): string
    {
        return $this->detailsBreakdown()->pluck('garments_item_name')->unique()->implode(',');
    }

    public function getUomAttribute(): string
    {
        return $this->detailsBreakdown()->pluck('uom_value')->unique()->implode(',');
    }

    public function getRateAttribute()
    {
        return $this->detailsBreakdown()->avg('rate');
    }

    public function getAmountAttribute()
    {
        return $this->detailsBreakdown()->sum('amount');
    }

    public function getTotalFabricBookingQtyAttribute()
    {
        return $this->detailsBreakdown()->sum('total_qty');
    }

    public function filter($request)
    {
        $factory_id = $request->get('factory_id');
        $buyer_id = $request->get('buyer_id');
        $supplier_id = $request->get('supplier_id');
        $wo_no = $request->get('wo_no');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $fabricBookings = FabricBooking::query()
            ->where('factory_id', $factory_id)
            ->push
            ->when($supplier_id, function ($query) use ($supplier_id) {
                return $query->where('supplier_id', $supplier_id);
            })
            ->when($wo_no, function ($query) use ($wo_no) {
                return $query->where(\DB::raw('substr(unique_id, -5)'), 'LIKE', '%' . $wo_no);
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query->whereBetween('booking_date', [$from_date, $to_date]);
            })
            ->has('detailsBreakdown')
            ->with('detailsBreakdown', 'buyer:id,name', 'supplier:id,name')
            ->get();
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function scopeApprovalFilter($query, $request, $previousStep, $step)
    {
        return $query->when($request->get('factory'), function ($q) use ($request) {
            return $q->where('factory_id', $request->get('factory'));
        })
            ->when($request->get('buyer'), function ($q) use ($request) {
                return $q->where('buyer_id', $request->get('buyer'));
            })
            ->when($request->get('internal_ref'), function ($q) use ($request) {
                return $q->where('internal_ref_no', $request->get('internal_ref'));
            })
            ->when($request->get('file_no'), function ($q) use ($request) {
                return $q->where('file_no', $request->get('file_no'));
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

    public function fabricSalesOrder(): HasMany
    {
        return $this->hasMany(FabricSalesOrder::class, 'booking_no', 'unique_id')
            ->where('booking_type', 'main');
    }
}
