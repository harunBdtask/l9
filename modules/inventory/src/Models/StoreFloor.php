<?php


namespace SkylarkSoft\GoRMG\Inventory\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class StoreFloor extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'store_floors';

    protected $fillable = [
        'factory_id',
        'store_id',
        'name',
        'sequence',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'rooms',
        'racks',
        'shelves',
        'bins',
    ];

    public static function boot()
    {
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
            if ($model->id && $model->status) {
                StoreRoom::query()
                    ->where('floor_id', $model->id)
                    ->update([
                        'status' => $model->status
                    ]);
                StoreRack::query()
                    ->where('floor_id', $model->id)
                    ->update([
                        'status' => $model->status
                    ]);
                StoreShelf::query()
                    ->where('floor_id', $model->id)
                    ->update([
                        'status' => $model->status
                    ]);
                StoreBin::query()
                    ->where('floor_id', $model->id)
                    ->update([
                        'status' => $model->status
                    ]);
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

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'id')->withDefault();
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(StoreRoom::class, 'floor_id');
    }

    public function racks(): HasMany
    {
        return $this->hasMany(StoreRack::class, 'floor_id');
    }

    public function shelves(): HasMany
    {
        return $this->hasMany(StoreShelf::class, 'floor_id');
    }

    public function bins(): HasMany
    {
        return $this->hasMany(StoreBin::class, 'floor_id');
    }
}
