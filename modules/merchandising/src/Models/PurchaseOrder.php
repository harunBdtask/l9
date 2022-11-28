<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoiceDetail;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLCDetail;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContractDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAReports;

class PurchaseOrder extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'factory_id',
        'order_status',
        'buyer_id',
        'order_id',
        'po_no',
        'ready_to_approved',
        'is_approved',
        'un_approve_request',
        'po_receive_date',
        'po_quantity',
        'po_pc_quantity',
        'ex_factory_date',
        'lead_time',
        'production_lead_time',
        'pi_bunch_budget_date',
        'ex_bom_handover_date',
        'is_locked',
        'booking_status',
        'avg_rate_pc_set',
        'carton_info',
        'internal_ref_no',
        'comm_file_no',
        'packing_ratio',
        'status',
        'customer',
        'league',
        'remarks',
        'print_status',
        'embroidery_status',
        'required_hanger',
        'delay_for',
        'copy_from',
        'country_id',
        'country_code',
        'area',
        'area_code',
        'cut_off_date',
        'cut_off',
        'country_ship_date',
        'pack_type',
        'pcs_per_pack',
        'matrix_type',
        'qty_copy_status',
        'ex_cut_percent_copy_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $cascadeDeletes = ['poDetails'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'booking_status' => Json::class,
    ];

    const COPY_STRING = 'copied-po';

    const QTY = 'Qty.';
    const PLAN_CUT_QTY = 'Plan Cut Qty.';
    const RATE = 'Rate';
    const EX_CUT = 'Ex. Cut %';
    const ARTICLE_NO = 'Article No';

    public const EX_FACTORY_DATE = 1;
    public const COUNTRY_SHIPMENT_DATE = 2;

    const particulars = ['Qty.', 'Plan Cut Qty.'];
    public const APPROVED = 1;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });

        static::saving(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->order_id)
                ->handleOrder()
                ->saveOrUpdate();

            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::created(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->order_id)
                ->handleOrder()
                ->saveOrUpdate();

            $model->created_by = Auth::id();
        });

        static::deleted(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->order_id)
                ->handleOrder()
                ->saveOrUpdate();
        });
    }

    public function setExFactoryDateAttribute($value)
    {
        $this->attributes['ex_factory_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function setPoReceiveDateAttribute($value)
    {
        $this->attributes['po_receive_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function getExFactoryDateAttribute()
    {
        if (isset($this->attributes['ex_factory_date'])) {
            return Carbon::make($this->attributes['ex_factory_date'])->format('d-m-Y');
        }
        return null;
    }

    public function getPoReceiveDateAttribute()
    {
        if ($this->attributes['po_receive_date']) {
            return Carbon::make($this->attributes['po_receive_date'])->format('d-m-Y');
        }
        return null;
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function poDetails(): HasMany
    {
        return $this->hasMany(PoColorSizeBreakdown::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id')->withDefault();
    }

    public function exportInvoiceDetails(): HasMany
    {
        return $this->hasMany(ExportInvoiceDetail::class, 'po_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }

    public function purchaseOrderDetails(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
    }

    public function allBundleCards()
    {
        return $this->hasMany(BundleCard::class, 'purchase_order_id', 'id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'purchase_order_details', 'purchase_order_id', 'color_id');
    }

    public function lots()
    {
        return $this->belongsToMany(Lot::class, 'lot_order', 'order_id', 'lot_id');
    }

    public function bundleCards()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard', 'purchase_order_id')
            ->where('status', 1);
    }

    public static function purchaseOrderByOrder($orderId)
    {
        return self::with('purchaseOrderDetails')
            ->where('order_id', $orderId)
            ->pluck('po_no', 'id')
            ->all();
    }

    public function salesContracts()
    {
        return $this->hasMany(SalesContractDetail::class, 'po_id', 'id');
    }

    public function exportLCs()
    {
        return $this->hasMany(ExportLCDetail::class, 'po_id', 'id');
    }

    public function tnaReport():HasMany
    {
        return $this->hasMany(TNAReports::class,'po_id','id');
    }
}
