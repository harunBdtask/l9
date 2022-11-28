<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Fabric_construction;

class NewFabricComposition extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = "new_fabric_compositions";

    protected $fillable = [
        'fabric_nature_id',
        'construction',
        'color_range_id',
        'gsm',
        'machine_dia',
        'finish_fabric_dia',
        'machine_gg',
        'stitch_length',
        'fabric_code',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'newFabricCompositionDetails',
    ];

    const STATUS = ['1' => 'Active', '2' => 'Inactive'];
    const ACTIVE_STATUS = 1;
    const INACTIVE_STATUS = 2;

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

    public function fabricNature()
    {
        return $this->belongsTo(FabricNature::class, 'fabric_nature_id', 'id')->withDefault();
    }

    public function colorRange()
    {
        return $this->belongsTo(ColorRange::class, 'color_range_id', 'id')->withDefault();
    }

    public function newFabricCompositionDetails()
    {
        return $this->hasMany(NewFabricCompositionDetail::class, 'new_fab_comp_id', 'id');
    }
}
