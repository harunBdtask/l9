<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseRack extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'warehouse_racks';

    protected $fillable = [
        'name',
        'warehouse_floor_id',
        'capacity',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'warehouseCartons',
        'rackCartonPositions',
    ];

    public function factory()
    {
        return $this->belongsTo('Skylarksoft\Systemsettings\Models\Factory', 'factory_id', 'id')->withDefault();
    }

    public function warehouseFloor()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseFloor', 'warehouse_floor_id', 'id')->withDefault();
    }

    public function warehouseCartons()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseCarton', 'warehouse_rack_id', 'id');
    }

    public function rackCartonPositions()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\WarehouseManagement\Models\RackCartonPosition', 'warehouse_rack_id', 'id');
    }
}
