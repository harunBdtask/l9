<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use App\Facades\DecorateWithCacheFacade;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Sample;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;

class Buyer extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'buyers';

    protected $fillable = [
        'name',
        'short_name',
        'country_id',
        'factory_id',
        'contact_person',
        'designation',
        'exporters_ref',
        'email',
        'web_address',
        'address_1',
        'address_2',
        'party_type',
        'supplier_id',
        'day_credit_limit',
        'amount_credit_limit',
        'currency_id',
        'discount_method',
        'security_deducted',
        'ait_deducted',
        'sewing_efficiency_marketing',
        'sewing_efficiency_planing',
        'team_name',
        'status',
        'buyer_code',
        'pdf_conversion_key',
        'remarks',
        'dyeing_process_info',
        'link',
        'control_ledger_id',
        'ledger_account_id',
        'conversion_rate',
        'created_by',
        'updated_by',
        'contact_no'
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['orders', 'samples', 'buyer_wise_factories'];

    protected $casts = [
        'dyeing_process_info' => Json::class,
    ];

    protected static function booted()
    {
        self::creating(function ($model) {
            $model->factory_id = factoryId();
            $model->created_by = Auth::id();
            DecorateWithCacheFacade::as("factory_{$model->factory_id}_buyers")->invalidate();
        });

        self::saving(function ($model) {
            DecorateWithCacheFacade::as("factory_{$model->factory_id}_buyers")->invalidate();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
            DecorateWithCacheFacade::as("factory_{$model->factory_id}_buyers")->invalidate();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            DecorateWithCacheFacade::as("factory_{$model->factory_id}_buyers")->invalidate();
        });
    }

    public function buyerWiseFactories(): HasMany
    {
        return $this->hasMany(BuyerWiseFactory::class, 'buyer_id', 'id');
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id')->withDefault();
    }

    public function party(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function discount_method(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class, 'discount_method');
    }

    public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'buyer_id');
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(OrderItemDetail::class, Order::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class, 'buyer_id');
    }

    public function knit_cards()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnitCard', 'buyer_id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function knittingAllocations()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnittingAllocation', 'buyer_id', 'id');
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }

    public function lienBanks()
    {
        return $this->belongsToMany(LienBank::class, 'buyer_lien_bank', 'buyer_id', 'lien_bank_id')->withTimestamps();
    }

    public function advisingBanks()
    {
        return $this->belongsToMany(LienBank::class, 'buyer_advising_bank', 'buyer_id', 'advising_bank_id')->withTimestamps();
    }
}
