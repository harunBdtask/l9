<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class Washing extends Model
{
	use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'bundle_card_id',
        'washing_challan_no',
        'status',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'washing_received_challan_no',
        'received_status',
        'received_challan_status',
        'user_id',
        'factory_id'
    ];

    protected $dates = ['deleted_at'];

    public function bundlecard()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard', 'bundle_card_id');
    }

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

    public function sewingoutput()
    {
        return $this->hasOne('SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput', 'bundle_card_id', 'bundle_card_id');
    }
}
