<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;

class Operator extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'operators';

    protected $fillable = [
        'operator_name',
        'operator_code',
        'operator_type',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function operatorRolls()
    {
        return $this->hasMany(KnitProgramRoll::class);
    }
}
