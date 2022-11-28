<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssigningFactory extends Model
{
    use SoftDeletes;

    protected $table = 'assigning_factories';
    protected $fillable = [
        'name',
        'address'
    ];
    protected $dates = ['deleted_at'];

    public static function booted()
    {
        static::saving(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        static::deleting(function ($model) {
            $model->deleted_by = auth()->user()->id;
            $model->save();
        });

    }
}
