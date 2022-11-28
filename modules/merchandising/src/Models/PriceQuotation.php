<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use App\Contracts\AuditAbleContract;
use App\Helpers\UniqueCodeGenerator;
use App\Models\BelongsToBuyer;
use App\Traits\AuditAble;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorRange;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Incoterm;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class PriceQuotation extends Model implements AuditAbleContract
{
    use HasFactory;
    use SoftDeletes;
    use CascadeSoftDeletes;
    use BelongsToBuyer;
    use AuditAble;

    protected $table = "price_quotations";

    const PREFIX = 'UGL-PQ-';

    protected $fillable = [
        'quotation_id',
        'quotation_inquiry_id',
        'revised_no',
        'factory_id',
        'location',
        'buyer_id',
        'product_department_id',
        'style_name',
        'style_desc',
        'offer_qty',
        'season_id',
        'season_grp',
        'style_uom',
        'item_details',
        'costing_per',
        'costing_multiplier',
        'buying_agent_id',
        'region',
        'currency_id',
        'er',
        'incoterm_id',
        'incoterm_place',
        'machine_line',
        'quotation_date',
        'op_date',
        'est_shipment_date',
        'date_diff',
        'color_range_id',
        'prod_line_hr',
        'sew_smv',
        'cut_smv',
        'sew_eff',
        'cut_eff',
        'fin_smv',
        'fin_eff',
        'bh_merchant',
        'ready_to_approve',
        'is_approve',
        'rework_status',
        'cancel_status',
        'status',
        'remarks',
        'file',
        'image',
        'confirm_date',
        'fab_cost',
        'fab_cost_prcnt',
        'trims_cost',
        'trims_cost_prcnt',
        'embl_cost',
        'embl_cost_prcnt',
        'gmt_wash',
        'gmt_wash_prcnt',
        'comml_cost',
        'comml_cost_prcnt',
        'lab_cost',
        'lab_cost_prcnt',
        'inspect_cost',
        'inspect_cost_prcnt',
        'cm_cost',
        'cm_cost_prcnt',
        'freight_cost',
        'freight_cost_prcnt',
        'currier_cost',
        'currier_cost_prcnt',
        'certif_cost',
        'certif_cost_prcnt',
        'common_oh',
        'common_oh_prcnt',
        'total_cost',
        'total_cost_prcnt',
        'final_cost_pc_set',
        'final_cost_pc_set_prcnt',
        'asking_profit_pc_set',
        'asking_profit_pc_set_prcnt',
        'asking_quoted_pc_set',
        'asking_quoted_pc_set_prcnt',
        'revised_price_pc_set',
        'revised_price_pc_set_prcnt',
        'confirm_price_pc_set',
        'confirm_price_pc_set_prcnt',
        'price_bef_commn_dzn',
        'price_bef_commn_dzn_prcnt',
        'prod_cost_dzn',
        'prod_cost_dzn_prcnt',
        'margin_dzn',
        'margin_dzn_prcnt',
        'commi_dzn',
        'commi_dzn_prcnt',
        'price_with_commn_dzn',
        'price_with_commn_dzn_prcnt',
        'price_with_commn_pcs',
        'price_with_commn_pcs_prcnt',
        'target_price',
        'target_price_prcnt',
        'created_by',
        'updated_by',
        'deleted_by',
        'unapproved_request',
        'additional_costing'
    ];

    protected $casts = [
        'item_details' => Json::class,
        'additional_costing' => Json::class,
    ];

    protected $appends = [
        "style_uom_name",
        "costing_per_name",
        "lead_time_diff",
    ];
    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = ['pqCommissionDetails'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::created(function ($model) {
            $model->quotation_id = UniqueCodeGenerator::generate('PQ', $model->id);
            $model->save();
        });

        static::updating(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'updated_by' => Auth::id(),
            ]);
        });
        static::deleting(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'deleted_by' => auth()->user()->id,
            ]);
        });
    }

    public const APPROVED = 1;
    const STYLE_UOM = ['1' => 'Pcs', '2' => 'Set'];
    const READY_TO_APPROVE = ['1' => 'Yes', '2' => 'No'];
    const STATUS = ['1' => 'Pending', '2' => 'Confirm', '3' => 'Approve'];
    const COSTING_PER = [
        '1' => '1 Dzn',
        '2' => '1 Pc',
        '3' => '2 Dzn',
        '4' => '3 Dzn',
        '5' => '4 Dzn',
    ];
    const REGIONS = [
        'Asia' => 'Asia',
        'Africa' => 'Africa',
        'Australia' => 'Australia',
        'Antarctica' => 'Antarctica',
        'Europe' => 'Europe',
        'North America' => 'North America',
        'South America' => 'South America',
    ];

    //accessors
    public function getStyleUomNameAttribute(): string
    {
        return !array_key_exists($this->style_uom, self::STYLE_UOM) ? '' : self::STYLE_UOM[$this->style_uom];
    }

    public function getCostingPerNameAttribute(): string
    {
        return !array_key_exists($this->costing_per, self::COSTING_PER) ? '' : self::COSTING_PER[$this->costing_per];
    }

    public function getLeadTimeDiffAttribute(): string
    {
        $op_date = date_create($this->op_date);
        $est_shipment_date = date_create($this->est_shipment_date);
        $diff = date_diff($est_shipment_date, $op_date);
        $format = '';
        if ($diff->y) {
            $format .= "%y Year";
        }
        if ($diff->m) {
            $format .= "%m Month";
        }
        if ($diff->d) {
            $format .= "%d Day";
        }

        return $diff->format($format);
    }

    // search
    public function scopeFilter($query, $search)
    {
        return $query
            ->when($search, function ($query) use ($search) {
                $uom = array_search(ucfirst($search), self::STYLE_UOM) ?? $search;
                $approved = strtolower($search) == 'approved' ? 1 : (strtolower($search) == 'un-approved' ? null : $search);

                $query->where("quotation_id", "LIKE", "%{$search}%")
                    ->orWhere('style_uom', $uom)
                    ->orWhere('season_grp', "LIKE", "%{$search}%")
                    ->orWhere('is_approve', $approved)
                    ->orWhereHas("quotationInquiry", function ($query) use ($search) {
                        $query->where('quotation_id', "LIKE", "%{$search}%");
                    })
                    ->orWhere("style_name", "LIKE", "%{$search}%")
                    ->orWhereHas("buyer", function ($query) use ($search) {
                        $query->where('name', "LIKE", "%{$search}%");
                    })
                    ->orWhereHas("productDepartment", function ($query) use ($search) {
                        $query->where('product_department', "LIKE", "%{$search}%");
                    })
                    ->orWhereHas("season", function ($query) use ($search) {
                        $query->where('season_name', "LIKE", "%{$search}%");
                    });
            })->when(request('type') == 'Approved Price Quotation', function ($query) {
                $query->where('is_approve', 1);
            })
            ->when(request('type') == 'UnApproved Price Quotation', function ($query) {
                $query->where('is_approve', 0)->orWhereNull('is_approve');
            })
            ->when(request('from_date') && request('to_date'), function ($query) {
                $query->whereDate('created_at', '>=', request('from_date'))
                    ->whereDate('created_at', '<=', request('to_date'));
            });
    }

    public function styleEntry(): HasOne
    {
        return $this->hasOne(PriceQuotationStyleEntry::class, 'price_quotation_id', 'id')
            ->withDefault([
                'cbm_per_carton' => 0,
                'pcs_per_carton' => 0
            ]);
    }

    public function quotationInquiry(): BelongsTo
    {
        return $this->belongsTo(QuotationInquiry::class, 'quotation_inquiry_id', 'id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function productDepartment(): BelongsTo
    {
        return $this->belongsTo(ProductDepartments::class, 'product_department_id', 'id')->withDefault();
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id', 'id')->withDefault();
    }

    public function buyingAgent(): BelongsTo
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id', 'id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id')->withDefault();
    }

    public function incoterm(): BelongsTo
    {
        return $this->belongsTo(Incoterm::class, 'incoterm_id', 'id')->withDefault();
    }

    public function bhMerchant(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'bh_merchant', 'id')->withDefault();
    }

    public function pqCommissionDetails(): HasMany
    {
        return $this->hasMany(PqCommissionDetail::class, 'quotation_id', 'quotation_id');
    }

    public function costingDetails(): HasMany
    {
        return $this->hasMany(CostingDetails::class, 'price_quotation_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }

    public function colorRange()
    {
        return $this->belongsTo(ColorRange::class, 'color_range_id')->withDefault();
    }

    public function scopeApprovalFilter($query, $request, $previousStep, $step)
    {
        return $query->when($request->get('factory'), function ($query) use ($request) {
            $query->where('factory_id', $request->get('factory'));
        })
            ->when($request->get('buyer'), function ($query) use ($request) {
                $query->where('buyer_id', $request->get('buyer'));
            })
            ->when($request->get('priceQuotationId'), function ($query) use ($request) {
                $query->where('quotation_id', $request->get('priceQuotationId'));
            })
            ->when($request->get('style'), function ($query) use ($request) {
                $query->where('style_name', $request->get('style'));
            })
            ->when($request->get('fromDate') && $request->get('toDate'), function ($query) use ($request) {
                $query->whereBetween('quotation_date', [$request->get('fromDate'), $request->get('toDate')]);
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

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function moduleName(): string
    {
        return 'merchandising';
    }

    public function path(): string
    {
        return url("price-quotations/main-section-form?quotation_id=" . $this->quotation_id);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PriceQuotationAttachment::class, 'price_quotation_id');
    }
}
