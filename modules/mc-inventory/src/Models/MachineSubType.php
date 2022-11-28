<?php
namespace SkylarkSoft\GoRMG\McInventory\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\McInventory\Models\MachineType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class MachineSubType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'mc_machine_sub_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'machine_category',
        'machine_type',
        'machine_sub_type',
        'description'
    ];

    protected $appends = [
        'text',
        'machine_category_value',
    ];
    public function getTextAttribute(){
        return $this->machine_sub_type;
    }

    public function getMachineCategoryValueAttribute(){
        return McMachineInventoryConstant::MACHINE_CATEGORIES[$this->attributes['machine_category']];
    }

    public function machineType()
    {
        return $this->belongsTo(MachineType::class,'machine_type','id')->withDefault();
    }

    public function machine()
    {
        return $this->hasMany(McMachine::class,'sub_type_id','id');
    }

}
