<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery;

use Illuminate\Http\Request;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\DyeingGoodsDeliveryService;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery\DyeingGoodsDeliveryDetail;


class DyeingGoodsDelivery extends Model
{
    use SoftDeletes, CommonModelTrait, BelongsToFactory;

    protected $table = 'dyeing_goods_delivery';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'buyer_id',
        'entry_basis',
        'dyeing_batch_id',
        'dyeing_batch_no',
        'textile_order_id',
        'textile_order_no',
        'delivery_date',
        'sub_dyeing_unit_id',
        'challan_no',
        'vehicle_no',
        'shift_id',
        'driver_name',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        "entry_basis_value"
    ];

    protected const ENTRY_BASIS = [
        1 => 'BATCH',
        2 => 'ORDER',
    ];

    public function getEntryBasisValueAttribute(): ?string
    {
        return self::ENTRY_BASIS[$this->attributes['entry_basis']] ?? null;
    }

    public function scopeSearch($query,Request $request)
    {
        $delivery_date = $request->get('delivery_date');
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $order_no = $request->get('dyeing_order_no');
        $batch_no = $request->get('dyeing_batch_no');

        $query->when($delivery_date,Filter::applyFilter('delivery_date',$delivery_date))
            ->when($factoryId,Filter::applyFilter('factory_id',$factoryId))
            ->when($buyerId,Filter::applyFilter('buyer_id',$buyerId))
            ->when($order_no,Filter::applyFilter('textile_order_no',$order_no))
            ->when($batch_no,Filter::applyFilter('dyeing_batch_no',$batch_no));
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = DyeingGoodsDeliveryService::generateUniqueId();
            }
        });
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function dyeingBatch(): BelongsTo
    {
        return $this->belongsTo(DyeingBatch::class, 'dyeing_batch_id', 'id')
            ->withDefault();
    }

    public function textileOrder(): BelongsTo
    {
        return $this->belongsTo(TextileOrder::class, 'textile_order_id', 'id')
            ->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')->withDefault();
    }

    public function dyeingGoodsDeliveryDetails(): HasMany
    {
        return $this->hasMany(
            DyeingGoodsDeliveryDetail::class,
            'dyeing_goods_delivery_id',
            'id'
        );
    }

    public function subDyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'sub_dyeing_unit_id')->withDefault();
    }
}
