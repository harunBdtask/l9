<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\ModelCommonTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingFinishingProduction\SubDyeingFinishingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingPeach\SubDyeingPeachDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingTumble\SubDyeingTumbleDetail;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\SubTextileOrderService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;

class SubTextileOrder extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;
    use CascadeSoftDeletes;

    protected $table = 'sub_textile_orders';

    protected $fillable = [
        'order_uid',
        'factory_id',
        'supplier_id',
        'order_no',
        'ref_no',
        'repeat_order_no',
        'description',
        'revised_no',
        'receive_date',
        'currency_id',
        'payment_basis',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'subTextileOrderDetails',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->order_uid = SubTextileOrderService::generateUniqueId();
            }
        });
    }

    public const PAYMENT_BASIS_OPTIONS = [
        '1' => 'Credit',
        '2' => 'At Sight',
        '3' => 'Bill Recipe',
    ];

    protected $appends = [
        'payment_basis_value',
    ];

    public const CREDIT_PAYMENT = 1;
    public const AT_SIGHT_PAYMENT = 2;
    public const BILL_RECIPE_PAYMENT = 3;

    public function getPaymentBasisValueAttribute(): ?string
    {
        return isset($this->attributes['payment_basis'])
            ? self::PAYMENT_BASIS_OPTIONS[$this->attributes['payment_basis']]
            : null;
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function subTextileOrderDetails(): HasMany
    {
        return $this->hasMany(SubTextileOrderDetail::class, 'sub_textile_order_id');
    }

    public function batch(): HasMany
    {
        return $this->hasMany(SubDyeingBatch::class, 'sub_textile_order_detail_id');
    }

    public function subDyeingProductionDetail(): HasMany
    {
        return $this->hasMany(SubDyeingProductionDetail::class, 'order_id');
    }

    public function subDryerDetail(): HasMany
    {
        return $this->hasMany(SubDryerDetail::class, 'order_id');
    }

    public function subSlittingDetail(): HasMany
    {
        return $this->hasMany(SubSlittingDetail::class, 'order_id');
    }

    public function subDyeingStenteringDetail(): HasMany
    {
        return $this->hasMany(SubDyeingStenteringDetail::class, 'order_id');
    }

    public function subCompactorDetail(): HasMany
    {
        return $this->hasMany(SubCompactorDetail::class, 'order_id');
    }

    public function subDyeingTumbleDetail(): HasMany
    {
        return $this->hasMany(SubDyeingTumbleDetail::class, 'sub_textile_order_id');
    }

    public function subDyeingPeachDetail(): HasMany
    {
        return $this->hasMany(SubDyeingPeachDetail::class, 'sub_textile_order_id');
    }

    public function subDyeingFinishingProduction(): HasMany
    {
        return $this->hasMany(SubDyeingFinishingProductionDetail::class, 'sub_textile_order_id');
    }

    public function subDyeingDeliveryDetails(): HasMany
    {
        return $this->hasMany(SubDyeingGoodsDeliveryDetail::class, 'order_id');
    }
}
