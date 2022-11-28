<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;

class Fabric_construction extends Model
{
    use FactoryIdTrait;
    protected $table = 'fabric_construction';
    protected $fillable = [
        'construction',
        'gsm_weight',
        'factory_id',
        'created_at',
        'updated_at',
        'factory_id',
    ];
}
