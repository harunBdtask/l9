<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;

class Shipment extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'ship_quantity',
        'short_reject_qty',
        'remarks',
        'user_id',
        'factory_id',
        'status',
    ];

    protected $dates = ['deleted_at'];

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withDefault();
    }

    public function color(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withDefault();
    }

    public function size(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Size', 'size_id')->withDefault();
    }

    public static function totalShipmentOfOrder($order_id)
    {
        return self::where('order_id', $order_id)->sum('ship_quantity');
    }

    public static function totalShipmentOfPurchaseOrder($purchase_order_id)
    {
        return self::where('purchase_order_id', $purchase_order_id)->sum('ship_quantity');
    }

    public static function todayShipmentQty()
    {
        return self::whereDate('created_at', date('Y-m-d'))->sum('ship_quantity');
    }
}
