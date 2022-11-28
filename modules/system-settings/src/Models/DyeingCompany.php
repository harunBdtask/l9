<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DyeingCompany extends Model
{
    use SoftDeletes;
    protected $table = "dyeing_company";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted()
    {
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }

}