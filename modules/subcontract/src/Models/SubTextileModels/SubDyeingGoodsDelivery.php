<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Casts\Json;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Services\SubDyeingGoodsDeliveryService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class SubDyeingGoodsDelivery extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_goods_deliveries';
    protected $primaryKey = 'id';
    protected $fillable = [
        'goods_delivery_uid',
        'factory_id',
        'supplier_id',
        'entry_basis',
        'batch_id',
        'batch_no',
        'order_id',
        'order_no',
        'delivery_date',
        'dyeing_unit_id',
        'challan_no',
        'vehicle_no',
        'shift_id',
        'driver_name',
        'currency',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'batch_id' => Json::class,
        'batch_no' => Json::class,
        'order_id' => Json::class,
        'order_no' => Json::class,
    ];

    protected $appends = [
        "entry_basis_value",
        "currency_value",
    ];

    protected const ENTRY_BASIS = [
        1 => 'BATCH',
        2 => 'ORDER',
    ];

    protected const CURRENCY = [
        1 => 'USD',
        2 => 'Taka',
    ];

    public function getEntryBasisValueAttribute(): ?string
    {
        return self::ENTRY_BASIS[$this->attributes['entry_basis']] ?? null;
    }

    public function getCurrencyValueAttribute(): ?string
    {
        return isset($this->attributes['currency']) ? self::CURRENCY[$this->attributes['currency']] : null;
    }

    public function scopeSearch($query, Request $request)
    {
        $factoryId = $request->get('factory_id');
        $partyId = $request->get('party_id');
        $entryBasis = $request->get('entry_basis');
        $orderBatchNo = $request->get('order_batch_no');
        $dyeingUnit = $request->get('dyeing_unit');
        $deliveryDate = $request->get('delivery_date');
        $shift = $request->get('shift');
        $color = $request->get('color');
        $goodsDeliveryUID = $request->get('goods_delivery_uid');

        if ($color) {
            $color = Color::query()->where('name', 'LIKE', "%{$color}%")->first()->id;
        }

        return $query->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($partyId, Filter::applyFilter('supplier_id', $partyId))
            ->when($entryBasis, Filter::applyFilter('entry_basis', $entryBasis))
            ->when($goodsDeliveryUID, Filter::applyFilter('goods_delivery_uid', $goodsDeliveryUID))
            ->when($orderBatchNo, function ($query, $orderBatchNo) {
                $query->where('order_no', 'LIKE', "%{$orderBatchNo}%")
                    ->orWhere('batch_no', 'LIKE', "%{$orderBatchNo}%");
            })
            ->when($dyeingUnit, Filter::applyFilter('dyeing_unit_id', $dyeingUnit))
            ->when($shift, Filter::applyFilter('shift_id', $shift))
            ->when($deliveryDate, Filter::applyFilter('delivery_date', $deliveryDate))
            ->when($color, function ($query) use ($color) {
                $query->whereHas('subDyeingGoodsDeliveryDetails', function ($q) use ($color) {
                    return $q->where('color_id', $color);
                });
            });
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->goods_delivery_uid = SubDyeingGoodsDeliveryService::generateUniqueId();
            }
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    public function subDyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'dyeing_unit_id')->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')->withDefault();
    }

    public function subDyeingGoodsDeliveryDetails(): HasMany
    {
        return $this->hasMany(SubDyeingGoodsDeliveryDetail::class, 'sub_dyeing_goods_delivery_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }
}
