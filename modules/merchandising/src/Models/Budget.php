<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use App\Contracts\AuditAbleContract;
use App\Models\BelongsToBuyer;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentBookingItemDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrderDetails;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class Budget extends Model implements AuditAbleContract
{
    use SoftDeletes;
    use BelongsToBuyer;
    use AuditAble;

    const COSTING_DETAILS = 'details.details';
    protected $table = 'budgets';

    protected $fillable = [
        'job_no',
        'factory_id',
        'buyer_id',
        'quotation_id',
        'style_name',
        'style_desc',
        'job_qty',
        'product_department_id',
        'order_uom_id',
        'incoterm_id',
        'incoterm_place',
        'buying_agent_id',
        'costing_date',
        'costing_per',
        'region',
        'machine_line',
        'currency_id',
        'sewing_smv',
        'cut_efficiency',
        'budget_minute',
        'prod_line_hr',
        'sew_efficiency',
        'cut_smv',
        'fin_smv',
        'fin_eff',
        'copy_from',
        'copy_from_id',
        'file_no',
        'internal_ref',
        'image',
        'approve_status',
        'remarks',
        'file',
        'ready_to_approved',
        'un_approve_request',
        'rework_status',
        'cancel_status',
        'costing',
        'created_by',
        'updated_by',
        'deleted_by',
        'approve_date'
    ];

    protected $casts = [
        'costing' => Json::class,
    ];

    protected $appends = [
        "costing_multiplier",
    ];

    const FABRIC_NATURE = [
        'KNIT' => 1,
        'WOVEN' => 2,
    ];

    const UOM = BudgetService::UOM;

    const PCS = 1;
    const SET = 2;
    public const APPROVED = 1;

    public function getCostingMultiplierAttribute()
    {
        if (isset(PriceQuotation::COSTING_PER[$this->costing_per])) {
            switch (PriceQuotation::COSTING_PER[$this->costing_per]) {
                case '1 Dzn':
                    $costing_per = 12;

                    break;
                case '1 Pc':
                    $costing_per = 1;

                    break;
                case '2 Dzn':
                    $costing_per = 24;

                    break;
                case '3 Dzn':
                    $costing_per = 36;

                    break;
                case '4 Dzn':
                    $costing_per = 48;

                    break;
                default:
                    $costing_per = 0;

                    break;
            }

            return $costing_per ?? 1;
        } else {
            return 1;
        }
    }

    public function getUnitOfMeasurementAttribute(): string
    {
        if ($this->attributes['order_uom_id'] == self::PCS) {
            return 'PCS';
        }
        return 'SET';
    }

    protected static function booted()
    {

        self::creating(function ($budget) {
            $budget->created_by = Auth::id();
        });

        self::updating(function ($budget) {
            $budget->updated_by = Auth::id();
        });

        self::deleting(function ($budget) {
            $budget->deleted_by = Auth::id();
        });
    }

    public function getJobQtyAttribute()
    {
        $order = Order::where('id', $this->attributes['copy_from_id'])->first();

        return $order->pq_qty_sum ?? 0;
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(PriceQuotation::class, 'quotation_id', 'quotation_id');
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'order_uom_id', 'id');
    }

    public function productDepartment(): BelongsTo
    {
        return $this->belongsTo(ProductDepartments::class, 'product_department_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'copy_from_id')->withDefault();
    }

    public function purchaseOrders(): HasOneThrough
    {
        return $this->hasOneThrough(PurchaseOrder::class,
            Order::class,
            'id',
            'order_id',
            'copy_from_id');
    }

    public function costings(): HasMany
    {
        return $this->hasMany(BudgetCostingDetails::class, 'budget_id');
    }

    public function trimCosting(): HasOne
    {
        return $this->hasOne(BudgetCostingDetails::class, 'budget_id')->where('type', 'trims_costing');
    }

    public function fabricCosting(): HasOne
    {
        return $this->hasOne(BudgetCostingDetails::class, 'budget_id')->where('type', 'fabric_costing');
    }

    public function embellishmentCosting(): HasOne
    {
        return $this->hasOne(BudgetCostingDetails::class, 'budget_id')->where('type', 'embellishment_cost');
    }

    public function washCosting(): HasOne
    {
        return $this->hasOne(BudgetCostingDetails::class, 'budget_id')->where('type', 'wash_cost');
    }

    public function commercialCosting(): HasOne
    {
        return $this->hasOne(BudgetCostingDetails::class, 'budget_id')->where('type', 'commercial_cost');
    }

    public function commissionCosting(): HasOne
    {
        return $this->hasOne(BudgetCostingDetails::class, 'budget_id')->where('type', 'commission_cost');
    }

    public function trimDetails(): Collection
    {
        return collect($this->trimCosting()->get())->pluck(self::COSTING_DETAILS)->flatten(1);
    }

    public function fabricDetails(): Collection
    {
        return collect($this->fabricCosting()->get())->pluck('details.details.fabricForm')->flatten(1);
    }

    public function embellishmentDetails(): Collection
    {
        return collect($this->embellishmentCosting()->get())->pluck(self::COSTING_DETAILS)->flatten(1);
    }

    public function commissionDetails(): Collection
    {
        return collect($this->commissionCosting()->get())->pluck(self::COSTING_DETAILS)->flatten(1);
    }

    public function commercialDetails(): Collection
    {
        return collect($this->commercialCosting()->get())->pluck(self::COSTING_DETAILS)->flatten(1);
    }

    public function knitFabric(): Collection
    {
        return $this->fabricDetails()->where('fabric_nature_id', self::FABRIC_NATURE['KNIT']);
    }

    public function wovenFabric(): Collection
    {
        return collect($this->fabricDetails())->where('fabric_nature_id', self::FABRIC_NATURE['WOVEN']);
    }

    public function yarnDetails(): Collection
    {
        return collect($this->fabricCosting()->get())->pluck('details.details.yarnCostForm')->flatten(1);
    }

    public function fabricCalculation(): Collection
    {
        return collect($this->fabricCosting()->get())->pluck('details.calculation');
    }

    public function conversionDetails(): Collection
    {
        return collect($this->fabricCosting()->get())->pluck('details.details.conversionCostForm')->flatten(1);
    }

    public function items(): Collection
    {
        return collect($this->order()->get())->pluck('item_details.details')->flatten(1);
    }

    public function washDetails(): Collection
    {
        return collect($this->washCosting()->get())->pluck(self::COSTING_DETAILS)->flatten(1);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }

    public function scopeApprovalFilter($query, $request, $previousStep, $step)
    {
        return $query->when($request->get('factory'), function ($query) use ($request) {
            $query->where('factory_id', $request->get('factory'));
        })
            ->when($request->get('buyer'), function ($query) use ($request) {
                $query->where('buyer_id', $request->get('buyer'));
            })
            ->when($request->get('uniqueId'), function ($query) use ($request) {
                $query->where('job_no', $request->get('uniqueId'));
            })
            ->when($request->get('style'), function ($query) use ($request) {
                $query->where('style_name', $request->get('style'));
            })
            ->when($request->get('internalRef'), function ($query) use ($request) {
                $query->where('internal_ref', $request->get('internalRef'));
            })
            ->when($request->get('fileNo'), function ($query) use ($request) {
                $query->where('file_no', $request->get('fileNo'));
            })
            ->when($request->get('year'), function ($query) use ($request) {
                $query->whereYear('created_at', $request->get('year'));
            })
            ->when($request->get('approvalType'), function ($query) use ($request, $previousStep, $step) {
                $query->when($request->get('approvalType') == '1', function ($query) use ($previousStep, $step) {
                    $query->where('ready_to_approved', '=', 'Yes')
                        ->where('is_approve', '=', null)
//                                ->where('step', '<', $step);
                        ->where('step', $previousStep);
                })->when($request->get('approvalType') == '2', function ($query) use ($step) {
                    $query->where('step', $step);
//                    $query->where('is_approve', 1);
                });
            });
    }

    public function trimsBookings()
    {
        return $this->hasMany(TrimsBookingDetails::class, 'style_name', 'style_name');
    }

    public function fabricBookingDetails()
    {
        return $this->hasMany(FabricBookingDetailsBreakdown::class, 'job_no', 'job_no');
    }

    public function fabricBookings()
    {
        return $this->hasMany(FabricBookingDetails::class, 'style_name', 'style_name');
    }

    public function emblBookings()
    {
        return $this->hasMany(EmbellishmentWorkOrderDetails::class, 'style', 'style_name');
    }


    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function moduleName(): string
    {
        return 'merchandising';
    }

    public function path(): string
    {
        return url("budgeting/create?budget_id=".$this->id);
    }
}
