<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class WarehouseCarton extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'warehouse_cartons';

    protected $fillable = [
        'barcode_no',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'garments_qty',
        'created_status',
        'rack_allocation_status',
        'warehouse_floor_id',
        'warehouse_rack_id',
        'shipment_status',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'warehouseCartonDetails',
        'WarehouseShipmentCarton',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id')->withDefault();
    }

    public function warehouseFloor()
    {
        return $this->belongsTo(WarehouseFloor::class, 'warehouse_floor_id', 'id')->withDefault();
    }

    public function warehouseRack()
    {
        return $this->belongsTo(WarehouseRack::class, 'warehouse_rack_id', 'id')->withDefault();
    }

    public function warehouseCartonDetails()
    {
        return $this->hasMany(WarehouseCartonDetail::class, 'warehouse_carton_id', 'id');
    }

    public function warehouseShipmentCarton()
    {
        return $this->hasMany(WarehouseShipmentCarton::class, 'warehouse_carton_id', 'id');
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }
}
