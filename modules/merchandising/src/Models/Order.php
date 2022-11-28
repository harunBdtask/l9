<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use App\Contracts\AuditAbleContract;
use App\Helpers\UniqueCodeGenerator;
use App\Traits\AuditAble;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;
use SkylarkSoft\GoRMG\Merchandising\Services\Order\AvgUnitPriceCalculator;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductCateory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAReports;

class Order extends Model implements AuditAbleContract
{
    use SoftDeletes, CascadeSoftDeletes, AuditAble;

    protected $table = 'orders';

    const PREFIX = 'UGL#';
    const PCS = 1;
    const SET = 2;
    const PROJECTION = 2;
    const PO_PROJECTION = 'Projection';

    protected $casts = [
        'item_details' => Json::class,
        'factory_smv' => Json::class,
    ];

    protected $fillable = [
        'factory_id',
        'job_no',
        'location',
        'buyer_id',
        'style_name',
        'repeated_style',
        'style_description',
        'price_quotation_id',
        'item_details',
        'packing_ratio',
        'product_category_id',
        'product_dept_id',
        'fabrication',
        'order_uom_id',
        'smv',
        'region',
        'order_copy_from',
        'team_leader_id',
        'dealing_merchant_id',
        'factory_merchant_id',
        'season_id',
        'ship_mode',
        'packing',
        'currency_id',
        'repeat_no',
        'buying_agent_id',
        'quality_label',
        'style_owner',
        'shipment_date',
        'po_received_date',
        'lead_time',
        'client',
        'remarks',
        'copy_status',
        'images',
        'created_by',
        'updated_by',
        'deleted_at',
        'factory_smv',
        'is_repeated',
        'reference_no',
        'combo',
        'garments_item_group',
        'fabric_composition',
        'fabric_type',
        'gsm',
        'pcd_date',
        'pcd_remarks',
        'ie_remarks',
        'sustainable_material',
        'approve_date',
        'ready_to_approved',
        'is_approve',
        'un_approve_request',
        'step',
        'rework_status',
        'cancel_status',
        'file',
        'order_status_id',
        'projection_po',
        'projection_qty',
    ];

    protected $appends = [
        'pq_qty_sum',
        'po_pcs_qty',
        'avg_unit_price',
        'common_file_name',
        'po_no',
        'sustainable_material_name',
    ];

    protected $cascadeDeletes = ['purchaseOrders'];

    protected $dates = ['deleted_at'];

    public const SUSTAINABLE_MATERIAL = [
        1 => 'GOTS',
        2 => 'OCS',
        3 => 'RCS/GRS',
        4 => 'OEKOTEX',
        5 => 'BCI',
        6 => 'CONVENTIONAL',
        7 => 'COMBINED (OCS & RCS/GRS)',
        8 => 'COMBINED (GOTS & RCS/GRS)',
        9 => 'COMBINED (BCI & OCS/GRS/RCS)',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->job_no = UniqueCodeGenerator::generate('OE', $model->id);
            $model->save();
        });

        static::creating(function ($order) {
            $order->created_by = Auth::id();
        });

        static::updating(function ($order) {
            $order->updated_by = Auth::id();
        });

        static::deleted(function ($order) {
            $order->deleted_by = Auth::id();
        });
    }

    public static function getByStyleName($styleName)
    {
        return static::where('style_name', $styleName)->first();
    }

    public function scopeFilter($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->whereHas("purchaseOrders", function ($query) use ($search) {
                $query->where("po_no", "LIKE", "%{$search['po_no']}%");
            });
        });
    }

    public function getAvgUnitPriceAttribute($value): float
    {
        if (isset($this->attributes['order_uom_id']) && $this->attributes['order_uom_id'] == self::PCS) {
            return AvgUnitPriceCalculator::calculateForPcs($this->purchaseOrders());
        } else {
            return AvgUnitPriceCalculator::calculateForSet($this->purchaseOrders());
        }
    }

    public function getUnitOfMeasurementAttribute(): string
    {
        if ($this->attributes['order_uom_id'] == self::PCS) {
            return 'PCS';
        }
        return 'SET';
    }

    public function getPqQtySumAttribute($value): int
    {
        return $this->purchaseOrders()->sum("po_quantity");
    }

    public function getPoPcsQtyAttribute($value): int
    {
        $po_qty = $this->purchaseOrders()->sum("po_pc_quantity");
        return $po_qty > 0 ? $po_qty : $this->purchaseOrders()->sum("po_quantity");
    }

    public function getCommonFileNameAttribute($value)
    {
        return $this->purchaseOrders()->first()->comm_file_no ?? null;
    }

    public function getSustainableMaterialNameAttribute(): ?string
    {
        if (!array_key_exists('sustainable_material', $this->attributes)) {
            return '';
        }

        return self::SUSTAINABLE_MATERIAL[$this->attributes['sustainable_material']] ?? '';
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCateory::class, 'product_category_id')->withDefault();
    }

    public function teamLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_leader_id')->withDefault();
    }

    public function dealingMerchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dealing_merchant_id')->withDefault([
            'screen_name' => 'N\A'
        ]);
    }

    public function factoryMerchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'factory_merchant_id')->withDefault();
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withDefault();
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class)->orderBy('id', 'asc');
    }

    public function approvedPurchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class)
            ->where('ready_to_approved', 1)
            ->where('is_approved', 1)
            ->orderBy('id', 'asc');
    }

    public function productDepartment(): BelongsTo
    {
        return $this->belongsTo(ProductDepartments::class, 'product_dept_id')->withDefault();
    }

    public function priceQuotation(): BelongsTo
    {
        return $this->belongsTo(PriceQuotation::class, 'order_copy_from')->withDefault();
    }

    public function styleEntry(): HasOne
    {
        return $this->hasOne(StyleEntry::class, 'order_id', 'id');
    }

    public function orderPriceQuotation(): HasOne
    {
        return $this->hasOne(PriceQuotation::class, 'style_name', 'style_name');
    }

    public function buyingAgent(): BelongsTo
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id', 'id')->withDefault();
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'copy_from_id')->withDefault();
    }

    public function budgetData()
    {
        return $this->belongsTo(Budget::class, 'style_name', 'style_name')->withDefault();
    }

    public function tna()
    {
        return $this->hasMany(TNAReports::class, 'order_id', 'id');
    }

    public function bundleCards()
    {
        return $this->hasMany(BundleCard::class, 'order_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(OrderAttachment::class,'order_id', 'id');
    }

    public function approveForBudgetFilterFormat(): array
    {
        return [
            'unique_id' => $this->job_no,
            'factory_id' => $this->factory_id,
            'internal_ref' => collect($this->purchaseOrders)->pluck('internal_ref_no')->first(),
            'buyer' => $this->buyer->name,
            'buyer_id' => $this->buyer_id,
            'year' => $this->created_at->format('Y'),
            'style_name' => $this->style_name,
            'insert_by' => $this->dealingMerchant->screen_name,
            'details' => collect($this->purchaseOrders)->map(function ($purchaseOrderDetails) {
                return [
                    'po_no' => $purchaseOrderDetails->po_no,
                    'unit_price' => $purchaseOrderDetails->avg_rate_pc_set,
                    'before_po_qty' => null,
                    'revise_po_qty' => $purchaseOrderDetails->po_quantity,
                    'remarks' => $purchaseOrderDetails->remarks,
                    'ship_date' => $purchaseOrderDetails->country_ship_date,
                    'approve_date' => $purchaseOrderDetails->approve_date,
                    'approve_remarks' => null,
                ];
            }),
        ];
    }


    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'order_uom_id')->withDefault();
    }

    public function purchaseOrderDetails(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'order_id');
    }

    public function garmentsItemGroup(): BelongsTo
    {
        return $this->belongsTo(GarmentsItemGroup::class, 'garments_item_group')->withDefault();
    }

    public function getPoNoAttribute(): string
    {
        return $this->purchaseOrders()->pluck('po_no')->unique()->implode(', ');
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public static function getBuyerOrders($buyer_id)
    {
        return self::where('buyer_id', $buyer_id)
            ->pluck('style_name', 'id')
            ->toArray();
    }

    public static function getOrderItemWiseSMV($order_id, $garments_item_id, $withoutGLobalScopes = false)
    {
        $smv = 0;
        $order = self::query()
            ->when($withoutGLobalScopes, function ($query) {
                $query->withoutGlobalScopes();
            })
            ->find($order_id);
        if ($order) {
            $item_details = $order->item_details && is_array($order->item_details) && array_key_exists('details', $order->item_details) ? $order->item_details['details'] : null;
            $itemData = $item_details && is_array($item_details) ? collect($item_details)->where('item_id', $garments_item_id)->first() : null;
            $smv = $itemData && is_array($itemData) && count($itemData) && array_key_exists('item_smv', $itemData) ? $itemData['item_smv'] : 0;
        }
        return $smv;
    }

    public static function getOrderItemWiseFactorySMV($order_id, $garments_item_id, $withoutGLobalScopes = false)
    {
        $smv = null;
        $order = self::query()
            ->when($withoutGLobalScopes, function ($query) {
                $query->withoutGlobalScopes();
            })
            ->find($order_id);
        if ($order) {
            $item_details = $order->factory_smv && is_array($order->factory_smv) && array_key_exists('details', $order->factory_smv) ? $order->factory_smv['details'] : null;
            $itemData = $item_details && is_array($item_details) ? collect($item_details)->where('item_id', $garments_item_id)->first() : null;
            $smv = $itemData && is_array($itemData) && count($itemData) && array_key_exists('item_smv', $itemData) ? $itemData['item_smv'] : null;
        }
        return $smv;
    }

    public function getToolTip()
    {
        $dealingMerchant = $this->dealingMerchant->screen_name ?? '';
        $creator = $this->createdBy->screen_name ?? '';
        $dealingMerchantText = $dealingMerchant ? "<strong>Dealing Merchant: </strong>" . $dealingMerchant : '';
        $creatorText = $creator ? "<strong>Created By: </strong>" . $creator : '';
        return "<div class='tooltip-info'><span>$dealingMerchantText</span><br>
                <span>$creatorText</span><br>
                <br><span><strong>Created at: </strong>" . date("F j, Y, g:i a", strtotime($this->attributes['created_at'])) . "
                </span><br><span><strong>Updated at: </strong>" . date("F j, Y, g:i a", strtotime($this->attributes['updated_at'])) .
            "</span></div>";
    }

    public function moduleName(): string
    {
        return 'merchandising';
    }

    public function path(): string
    {
        return url("orders/edit?order_id=" . $this->id);
    }

    public function tnaReport(): HasMany
    {
        return $this->hasMany(TNAReports::class, 'order_id', 'id');
    }
}
