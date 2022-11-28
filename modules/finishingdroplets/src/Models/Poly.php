<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingTable;

class Poly extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'production_date',
        'finishing_floor_id',
        'finishing_table_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'poly_qty',
        'poly_rejection_qty',
        'iron_qty',
        'iron_rejection_qty',
        'packing_qty',
        'packing_rejection_qty',
        'reason',
        'remarks',
        'factory_id'
    ];

    protected $dates = ['deleted_at'];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withDefault();
    }

    public function finishingFloor()
    {
        return $this->belongsTo(FinishingFloor::class, 'finishing_floor_id')->withDefault();
    }

    public function finishingTable()
    {
        return $this->belongsTo(FinishingTable::class, 'finishing_table_id')->withDefault();
    }

    public static function getReason($order_id, $color_id)
    {
        $query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id
        ])->orderBy('id', 'desc');

        if ($query->count() > 0) {
            return $query->first()->reason;
        }
        return null;
    }
}
