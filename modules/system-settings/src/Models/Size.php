<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Size extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'sizes';

    protected $fillable = [
        'name', 'sort', 'factory_id', 'sort', 'created_by', 'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->factory_id = factoryId();
            $model->created_by = userId();
        });
    }
}
