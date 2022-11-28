<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleAssign extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'driver_to_vehicle_assign';

    public function vehicle()
    {
        return $this->hasOne(RegistrationVehicle::class, 'id', 'vehicle_id');
    }

    public function driver()
    {
        return $this->hasOne(RegistrationDriver::class, 'id', 'driver_id');
    }
}
