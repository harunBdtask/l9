<?php
namespace SkylarkSoft\GoRMG\McInventory\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MachineBrand extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'mc_machine_brands';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];
    protected $appends = [
        'text',
    ];
    public function getTextAttribute(){
        return $this->name;
    }

}
