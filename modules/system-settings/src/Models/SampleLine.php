<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\SampleFloor;

class SampleLine extends Model
{
    use SoftDeletes;

    protected $table = 'sample_lines';

    protected $fillable = [
        'line_no',
        'floor_id',
        'sort',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
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

    public function factory()
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function floor()
    {
        return $this->belongsTo(SampleFloor::class, 'floor_id');
    }
}
