<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCompany extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'service_company';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'address',
    ];

}
