<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ColorType extends Model
{
    use SoftDeletes;
    protected $table = 'color_types';
    protected $fillable = [
        'id',
        'color_types',
        'is_stripe',
        'status',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->factory_id = Auth::user()->factory_id;
        });
        static::saving(function ($model) {
            $model->created_by = Auth::id();
        });
        static::updating(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'updated_by' => Auth::id(),
            ]);
        });
        static::deleting(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'deleted_by' => Auth::id(),
            ]);
        });
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }
}
