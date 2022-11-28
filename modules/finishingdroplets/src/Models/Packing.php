<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class Packing extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    
    protected $fillable = [
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'challan_no',
        'quantity',
        'factory_id',
        'status',
        'user_id',
        'created_at',
        'updated_at'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
