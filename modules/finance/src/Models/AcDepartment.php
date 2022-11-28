<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ac_departments';

    protected $fillable = [
        'ac_company_id',
        'ac_unit_id',
        'name',
        'sub_cost_center',
    ];

    protected $dates = ['deleted_at'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(AcCompany::class, 'ac_company_id')->withDefault();
    }
    public function unit(): BelongsTo
    {
        return $this->belongsTo(AcUnit::class, 'ac_unit_id')->withDefault();
    }
    public function actualDepartments(): HasMany
    {
        return $this->hasMany(AcActualDepartment::class, 'ac_cost_center_id', 'id');
    }
}
