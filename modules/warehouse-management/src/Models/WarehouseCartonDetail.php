<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Models;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class WarehouseCartonDetail extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'warehouse_carton_details';

    protected $fillable = [
        'warehouse_carton_id',
        'color_id',
        'size_id',
        'quantity',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [];

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id')->withDefault();
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id')->withDefault();
    }

    public function warehouseCarton()
    {
        return $this->hasMany(WarehouseCarton::class, 'warehouse_carton_id', 'id')->withDefault();
    }
}
