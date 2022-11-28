<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcCompany extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = [
        'name',
        'group_name',
        'corporate_address',
        'factory_address',
        'tin',
        'country',
        'responsible_person',
        'phone_no',
        'email'
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'units', 'departments'
    ];

    public function units(): HasMany
    {
        return $this->hasMany(AcUnit::class, 'ac_company_id', 'id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(AcDepartment::class, 'ac_company_id', 'id');
    }

    public function actualDepartments(): HasMany
    {
        return $this->hasMany(AcActualDepartment::class, 'ac_company_id', 'id');
    }
}
