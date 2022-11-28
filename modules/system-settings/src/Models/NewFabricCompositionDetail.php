<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class NewFabricCompositionDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "new_fabric_composition_details";

    protected $fillable = [
        'new_fab_comp_id',
        'yarn_composition_id',
        'percentage',
        'yarn_count_id',
        'composition_type_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

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

    public function newFabricComposition()
    {
        return $this->belongsTo(NewFabricComposition::class, 'new_fab_comp_id', 'id')->withDefault();
    }

    public function yarnComposition()
    {
        return $this->belongsTo(YarnComposition::class, 'yarn_composition_id', 'id')->withDefault();
    }

    public function yarnCount()
    {
        return $this->belongsTo(YarnCount::class, 'yarn_count_id', 'id')->withDefault();
    }

    public function compositionType()
    {
        return $this->belongsTo(CompositionType::class, 'composition_type_id', 'id')->withDefault();
    }
}
