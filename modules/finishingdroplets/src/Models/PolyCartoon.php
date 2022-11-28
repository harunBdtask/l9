<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class PolyCartoon extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'poly_cartoons';
    protected $fillable = [
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'received_qty',
        'qty_per_poly',
        'poly_qty',
        'cartoon_qty',
        'short_reject_qty',
        'floor_id',
        'created_by',
        'updated_by',
        'deleted_by',        
        'factory_id',
        'remarks',
    ];

    protected $dates = ['deleted_at'];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id');
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id');
    }

    public function color()
    {       
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id');
    }

    public function size()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Size', 'size_id');
    }

    public function floor()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Floor', 'floor_id');
    }

    public static function getRemarks($order_id, $color_id)
    {
        $query = self::where(['order_id' => $order_id,'color_id' => $color_id])->orderBy('id','desc');
        if($query->count() > 0) {
            return $query->first()->remarks;
        }
        return null;
    }

}
