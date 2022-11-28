<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class HiddenFields extends Model
{

    protected $table = 'hidden_fields';

    protected $fillable = [
        'factory_id',
        'page',
        'fields'
    ];

    protected $casts = [
        'fields' => Json::class
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->factory_id = factoryId();
            $model->save();
        });
    }
}