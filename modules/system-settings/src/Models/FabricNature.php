<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FabricNature extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'fabric_natures';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });
        static::saving(function ($model) {
            $model->created_by = auth()->user()->id;
        });
        static::updating(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'updated_by' => auth()->user()->id,
            ]);
        });
        static::deleting(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'deleted_by' => auth()->user()->id,
            ]);
        });
    }
}
