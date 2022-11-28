<?php
namespace SkylarkSoft\GoRMG\McInventory\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\McInventory\Models\MachineSubType;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class MachineType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'mc_machine_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'machine_category',
        'machine_type',
        'description',
    ];

    protected $appends = [
        'text',
        'machine_category_value',
    ];
    public function getTextAttribute(){
        return $this->machine_type;
    }

    public function getMachineCategoryValueAttribute(){
        return McMachineInventoryConstant::MACHINE_CATEGORIES[$this->attributes['machine_category']];
    }

    public function machine()
    {
        return $this->hasMany(McMachine::class,'type_id');
    }

    public function machineSubType(){
        return $this->hasMany(MachineSubType::class,'machine_type');
    }

}
