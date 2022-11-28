<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class Store extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = [
        'item_category_id',
        'factory_id',
        'location',
        'status',
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'floors',
        'rooms',
        'racks',
        'shelves',
        'bins',
    ];

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            if (in_array('created_by', $model->getFillable())) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (in_array('updated_by', $model->getFillable())) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => auth()->id(),
                    ]);
            }
        });
    }
    
    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function floors(): HasMany
    {
        return $this->hasMany(StoreFloor::class, 'store_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(StoreRoom::class, 'store_id');
    }

    public function racks(): HasMany
    {
        return $this->hasMany(StoreRack::class, 'store_id');
    }

    public function shelves(): HasMany
    {
        return $this->hasMany(StoreShelf::class, 'store_id');
    }

    public function bins(): HasMany
    {
        return $this->hasMany(StoreBin::class, 'store_id');
    }
}
