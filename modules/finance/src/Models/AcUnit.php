<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcUnit extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'ac_units';

    protected $fillable = [
        'ac_company_id',
        'unit',
        'project_head_name',
        'phone_no',
        'email',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'departments'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(AcCompany::class, 'ac_company_id')->withDefault();
    }

    public function departments(): HasMany
    {
        return $this->hasMany(AcDepartment::class, 'ac_unit_id', 'id');
    }

    public function actualDepartments(): HasMany
    {
        return $this->hasMany(AcActualDepartment::class, 'ac_unit_id', 'id');
    }
}
