<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SampleTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'sample_templates';

    protected $fillable = [
        'type',
        'template_name',
        'items',
        'total_calculation',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'items' => Json::class,
        'total_calculation' => Json::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)->update([
                    'deleted_by' => Auth::id(),
                ]);
            }
        });
    }
}
