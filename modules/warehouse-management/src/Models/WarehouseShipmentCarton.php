<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseShipmentCarton extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'warehouse_shipment_cartons';

    protected $fillable = [
        'challan_no',
        'warehouse_carton_id',
        'challan_status',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [];

    public function factory()
    {
        return $this->belongsTo('Skylarksoft\Systemsettings\Models\Factory', 'factory_id', 'id')->withDefault();
    }

    public function warehouseShipmentChallan()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseShipmentChallan', 'challan_no', 'challan_no')->withDefault();
    }

    public function warehouseCarton()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseCarton', 'warehouse_carton_id', 'id')->withDefault();
    }
}
