<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'lots';

    protected $fillable = [
        'lot_no',
        'color_id',
        'fabric_received',
        'fabric_received_at',
        'buyer_id',
        'order_id',
        'factory_id',
    ];

    protected $dates = [
        'fabric_received_at',
        'deleted_at',
    ];

    public function setFabricReceivedAtAttribute($date)
    {
        $this->attributes['fabric_received_at'] = Carbon::parse($date);
    }

    public function getFabricReceivedAtAttribute($date): ?string
    {
        return $date ? Carbon::parse($date)->toDateString() : null;
    }

    public function color(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public static function getLots($order_id)
    {
        return self::where('order_id', $order_id)->pluck('lot_no', 'id')->all();
    }

    public function bundleCards()
    {
        return $this->hasMany(BundleCard::class, 'lot_id');
    }

    public function purchaseOrders(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PurchaseOrder::class, 'lot_order', 'lot_id', 'purchase_order_id');
    }

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public static function lots($orderId)
    {
        $lotsData = self::with('color:id,name')
            ->where('order_id', $orderId)
            ->get();

        foreach ($lotsData as $lot) {
            $lots[$lot->id] = $lot->lot_no . ' (' .($lot->color->name ?? '') . ')';
        }

        return $lots ?? [];
    }
}
