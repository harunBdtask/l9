<?php
namespace SkylarkSoft\GoRMG\McInventory\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MachineUnit extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'mc_machine_units';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type',
        'description',
    ];
    protected $appends = [
        'text'
    ];

    public function getTextAttribute(){
        return $this->name;
    }

    public function machine()
    {
        return $this->hasMany(McMachine::class,'unit_id');
    }

}
