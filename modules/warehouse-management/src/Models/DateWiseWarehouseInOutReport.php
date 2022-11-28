<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;

class DateWiseWarehouseInOutReport extends Model
{
    use FactoryIdTrait;

    protected $table = 'date_wise_warehouse_in_out_reports';

    protected $fillable = [
        'production_date',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'factory_id',
        'in_garments_qty',
        'in_carton_qty',
        'out_garments_qty',
        'out_carton_qty',
    ];

    public function factory()
    {
        return $this->belongsTo('Skylarksoft\Systemsettings\Models\Factory', 'factory_id', 'id')->withDefault();
    }

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id', 'id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id', 'id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id', 'id')->withDefault();
    }
}
