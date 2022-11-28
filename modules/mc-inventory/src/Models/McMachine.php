<?php

namespace SkylarkSoft\GoRMG\McInventory\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\McInventory\Models\MachineUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class McMachine extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = "mc_machines";

    protected $fillable = [
        'name',
        'barcode',
        'barcode_generation_id',
        'factory_id',
        'brand_id',
        'model_no',
        'category_id',
        'sub_type_id',
        'origin',
        'serial_no',
        'location_id',
        'unit_id',
        'description',
        'purchase_date',
        'last_maintenance',
        'tenor',
        'next_maintenance',
        'status'
    ];

    protected $appends = [
        'category',
        'origin_value',
    ];
    public function getCategoryAttribute()
    {
        if($this->attributes['category_id']){
            return McMachineInventoryConstant::MACHINE_CATEGORIES[$this->attributes['category_id']];
        }
        return '';
    }
    public function getOriginValueAttribute()
    {
        if($this->attributes['origin']){
            return McMachineInventoryConstant::MACHINE_ORIGINS[$this->attributes['origin']];
        }
        return '';
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }
    public function machineLocation()
    {
        return $this->belongsTo(MachineLocation::class,'location_id','id')->withDefault();
    }
    public function unit()
    {
        return $this->belongsTo(MachineUnit::class,'unit_id');
    }
    public function brand()
    {
        return $this->belongsTo(MachineBrand::class,'brand_id');
    }


    public function type()
    {
        return $this->belongsTo(MachineType::class,'type_id');
    }

    public function subtype()
    {
        return $this->belongsTo(MachineSubType::class,'sub_type_id');
    }


}
