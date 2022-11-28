<?php

namespace SkylarkSoft\GoRMG\McInventory\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class McBarcodeGeneration extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = "mc_barcode_generations";

    protected $fillable = [
        'factory_id',
        'no_of_machine',
    ];
    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    } 
    
    public function machine()
    {
        return $this->hasMany(McMachine::class, 'barcode_generation_id');
    }
    
}
