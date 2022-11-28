<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class WashingReceive extends Model
{    
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'received_qty',
        'rejection_qty',
        'user_id',
        'factory_id'
    ];

    protected $dates = ['deleted_at'];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer');
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder','purchase_order_id');
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color');
    }
    
}
