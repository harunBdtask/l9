<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\Database\Factories\TextileOrderFactory;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\TextileOrderService;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;

class TextileOrder extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory, HasFactory {
        HasFactory::factory as modelFactory;
        BelongsToFactory::factory insteadof HasFactory;
    }

    protected $table = 'textile_orders';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'buyer_id',
        'fabric_sales_order_id',
        'fabric_sales_order_no',
        'description',
        'receive_date',
        'currency_id',
        'payment_basis',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'payment_basis_value'
    ];

    public function getPaymentBasisValueAttribute(): ?string
    {
        return isset($this->attributes['payment_basis'])
            ? self::PAYMENT_BASIS[$this->attributes['payment_basis']]
            : null;
    }

    const PAYMENT_BASIS = [
        1 => 'Credit',
        2 => 'At Sight',
        3 => 'Bill Receive',
    ];

    public function scopeSearch(Builder $query, Request $request)
    {
        $uniqueId = $request->input('unique_id');
        $receiveDate = $request->input('receive_date');
        $factoryId = $request->input('factory_id');
        $buyerId = $request->input('buyer_id');
        $currencyId = $request->input('currency_id');
        $paymentBasis = $request->input('payment_basis');
        $type = $request->input('type');

        $query->when($uniqueId, Filter::applyFilter('unique_id', $uniqueId))
            ->when($receiveDate, Filter::applyFilter('receive_date', $receiveDate))
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($currencyId, Filter::applyFilter('currency_id', $currencyId))
            ->when($paymentBasis, Filter::applyFilter('payment_basis', $paymentBasis))
            ->when($type, function ($query) use ($type) {
                $query->whereHas('fabricSalesOrder', function ($q) use ($type) {
                    $q->where('booking_type', $type);
                });
            });
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TextileOrderService::generateUniqueId();
            }
        });
    }

    public static function newFactory(): TextileOrderFactory
    {
        return TextileOrderFactory::new();
    }

    public function textileOrderDetails(): HasMany
    {
        return $this->hasMany(TextileOrderDetail::class, 'textile_order_id', 'id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function fabricSalesOrder(): BelongsTo
    {
        return $this->belongsTo(FabricSalesOrder::class, 'fabric_sales_order_id')
            ->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

}
