<?php

namespace SkylarkSoft\GoRMG\McInventory\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class MachineLocation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'mc_machine_locations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'location_name',
        'address',
        'contact_no',
        'email',
        'attention',
        'location_type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'text',
        'location_type_value',
    ];
    public function getTextAttribute(){
        return $this->location_name;
    }

    public function getLocationTypeValueAttribute(){
        return McMachineInventoryConstant::LOCATION_TYPES[$this->attributes['location_type']];
    }

    public function machine()
    {
        return $this->hasMany(McMachine::class,'location_id');
    }

}
