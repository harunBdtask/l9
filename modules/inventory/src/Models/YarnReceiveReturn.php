<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class YarnReceiveReturn extends Model
{

    use SoftDeletes, ModelCommonTrait;

    protected $fillable = [
        'factory_id',
        'receive_id',
        'return_date',
        'return_to',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function booted()
    {
        static::created(function ($model) {
            $generate = str_pad($model->id, 5, "0", STR_PAD_LEFT);
            $model->receive_return_no = getPrefix() . 'YRR-' . date('y') . '-' . $generate;
            $model->save();
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(YarnReceiveReturnDetail::class, 'receive_return_id');
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class, 'id', 'return_to')->select('id', 'name')->withDefault();
    }

    public function company(): HasOne
    {
        return $this->hasOne(Factory::class, 'id', 'factory_id')->withDefault();
    }

    public function yarn_receive(): HasOne
    {
        return $this->hasOne(YarnReceive::class, 'id', 'receive_id');
    }

    public function scopeYarnCount(Builder $query, $countId): Builder
    {
        if (!$countId) {
            return $query;
        }
        return $query->whereHas('details', function($q) use ($countId) {
            $q->where('yarn_count_id', $countId);
        });
    }

    public function scopeYarnComposition(Builder $query, $compositionId): Builder
    {
        if (!$compositionId) {
            return $query;
        }
        return $query->whereHas('details', function($q) use ($compositionId) {
            $q->where('yarn_composition_id', $compositionId);
        });
    }

    public function scopeYarnType(Builder $query, $typeId): Builder
    {
        if (!$typeId) {
            return $query;
        }
        return $query->whereHas('details', function($q) use ($typeId) {
            $q->where('yarn_type_id', $typeId);
        });
    }
}
