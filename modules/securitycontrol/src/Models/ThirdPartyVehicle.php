<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThirdPartyVehicle extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $table = 'third_party_vehicles';
    protected $fillable = [
        'factory_id',
        'driver_name',
        'vehicle_name',
        'purpose',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
