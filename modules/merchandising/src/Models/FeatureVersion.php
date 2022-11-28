<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FeatureVersion extends Model
{
    use SoftDeletes;

    protected $table = 'feature_versions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'feature_id',
        'feature_name',
        'version',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted()
    {

        self::creating(function ($model) {
            $model->factory_id = factoryId();
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
