<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorSkill extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'operator_skills';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',
    ];
}
