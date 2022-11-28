<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationDriver extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $table = 'register_drivers';
    protected $fillable = [
        'id',
        'factory_id',
        'name',
        'address',
        'license_no',
        'status',
    ];
    protected $dates = ['deleted_at'];

    public function vehicle_assign()
    {
        return $this->belongsTo(VehicleAssign::class, 'driver_id', 'id');
    }
}
