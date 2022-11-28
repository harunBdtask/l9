<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'visitors_tracking';
    protected $fillable = [
        'name',
        'designation',
        'company_name',
        'mobile_number',
        'email',
        'meeting_person',
        'in_time',
        'out_time',
        'status',
    ];
    protected $dates = ['deleted_at'];
}
