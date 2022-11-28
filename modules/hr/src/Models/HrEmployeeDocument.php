<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrEmployeeDocument extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_employee_documents';

    protected $fillable = [
        'id',
        'employee_id',
        'nid',
        'birth_certificate',
        'photo',
        'character_certificate',
        'ssc_certificate',
        'hsc_certificate',
        'biodata',
        'medical_certificate',
        'signature',
        'masters',
        'hons',
        'others',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
