<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationVehicle extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $table = 'register_vehicles';
    protected $fillable = [
        'factory_id',
        'name',
        'model_no',
        'register_no',
        'engine_no',
        'vehicle_type',
        'status',
    ];
    protected $dates = ['deleted_at'];

    public function vehicle_assign()
    {
        return $this->belongsTo(VehicleAssign::class, 'vehicle_id', 'id');
    }
}
