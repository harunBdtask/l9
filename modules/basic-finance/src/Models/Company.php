<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class Company extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;
    protected $table = 'bf_companies';
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

//    protected $cascadeDeletes = [
//        'projects',
//        'units',
//    ];
//    public function users(): HasMany
//    {
//        return $this->hasMany(User::class, 'company_id');
//    }
//    public function projects(): HasMany
//    {
//        return $this->hasMany(Project::class, 'bf_company_id', 'id');
//    }
//
//    public function units(): HasMany
//    {
//        return $this->hasMany(Unit::class, 'bf_project_id', 'id');
//    }
//
//    public function actualDepartments(): HasMany
//    {
//        return $this->hasMany(AcActualDepartment::class, 'ac_company_id', 'id');
//    }
}
