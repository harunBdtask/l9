<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class YarnCount extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;
    protected $table = 'yarn_counts';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->factory_id = factoryId();
            if (in_array('created_by', $model->getFillable())) {
                $model->created_by = userId();
            }
            $model->created_at = Carbon::now()->isFriday() ? Carbon::now()->subDay() : Carbon::now();
        });

        static::saving(function ($model) {
            $model->factory_id = factoryId();
            $model->updated_at = Carbon::now()->isFriday() ? Carbon::now()->subDay() : Carbon::now();
        });

        static::deleting(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => userId(),
                    ]);
            }
        });

        static::updating(function ($model) {
            if (in_array('updated_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)->update([
                    'updated_by' => userId(),
                ]);
            }
        });
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function preparedBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        )->withDefault();
    }

    public function edittedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }

    public function deletedByUser()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by',
            'id'
        );
    }
}
