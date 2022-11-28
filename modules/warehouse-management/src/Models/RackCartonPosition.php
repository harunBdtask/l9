<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RackCartonPosition extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'rack_carton_positions';

    protected $fillable = [
        'warehouse_floor_id',
        'warehouse_rack_id',
        'position_no',
        'warehouse_carton_id',
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

    public function warehouseFloor()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseFloor', 'warehouse_floor_id', 'id')->withDefault();
    }

    public function warehouseRack()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseRack', 'warehouse_rack_id', 'id')->withDefault();
    }

    public function warehouseCarton()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseCarton', 'warehouse_carton_id', 'id')->withDefault();
    }
}
