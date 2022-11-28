<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'designations';

    protected $fillable = [
        'designation',
        'factory_id',
    ];
    protected $dates = ['deleted_at'];

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }
}
