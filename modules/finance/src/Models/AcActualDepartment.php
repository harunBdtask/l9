<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcActualDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ac_actual_departments';

    protected $fillable = [
        'ac_company_id',
        'ac_unit_id',
        'ac_cost_center_id',
        'name',
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(AcDepartment::class, 'ac_cost_center_id')->withDefault();
    }
}
