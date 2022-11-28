<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseFloor extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'warehouse_floors';

    protected $fillable = [
        'name',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'warehouseRacks',
        'warehouseCartons',
        'rackCartonPositions',
    ];

    public function factory()
    {
        return $this->belongsTo('Skylarksoft\Systemsettings\Models\Factory', 'factory_id', 'id')->withDefault();
    }

    public function warehouseRacks()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseRack', 'warehouse_floor_id', 'id');
    }

    public function warehouseCartons()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseCarton', 'warehouse_floor_id', 'id');
    }

    public function rackCartonPositions()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\WarehouseManagement\Models\RackCartonPosition', 'warehouse_floor_id', 'id');
    }
}
