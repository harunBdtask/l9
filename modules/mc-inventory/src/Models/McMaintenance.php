<?php

namespace SkylarkSoft\GoRMG\McInventory\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;


class McMaintenance extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = "mc_maintenance";

    protected $fillable = [
        'machine_id',
        'unit_id',
        'last_maintenance',
        'tenor',
        'next_maintenance',
        'description',
        'parts_change',
        'parts_change_description',
        'mechanic',
        'status'
    ];
    protected $appends = [
        'parts_change_value',
        'status_value'
    ];

    public function machine()
    {
        return $this->belongsTo(McMachine::class,'machine_id','id')->withDefault();
    }

    public function machineUnit()
    {
        return $this->belongsTo(MachineUnit::class,'unit_id','id')->withDefault();
    }

    public function getPartsChangeValueAttribute(){
        return McMachineInventoryConstant::YES_NO[$this->attributes['parts_change']];
    }

    public function getStatusValueAttribute(){
        return McMachineInventoryConstant::MAINTENANCE_STATUS[$this->attributes['status']];
    }
}
