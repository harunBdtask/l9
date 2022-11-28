<?php


namespace SkylarkSoft\GoRMG\Inventory\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class StoreRoom extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'store_rooms';

    protected $fillable = [
        'factory_id',
        'store_id',
        'floor_id',
        'name',
        'sequence',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
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
            if ($model->id && $model->status) {
                StoreRack::query()
                    ->where('room_id', $model->id)
                    ->update([
                        'status' => $model->status
                    ]);
                StoreShelf::query()
                    ->where('room_id', $model->id)
                    ->update([
                        'status' => $model->status
                    ]);
                StoreBin::query()
                    ->where('room_id', $model->id)
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

    public function floor(): BelongsTo
    {
        return $this->belongsTo(StoreFloor::class, 'floor_id', 'id')->withDefault();
    }

    public function racks(): HasMany
    {
        return $this->hasMany(StoreRack::class, 'room_id');
    }

    public function shelves(): HasMany
    {
        return $this->hasMany(StoreShelf::class, 'room_id');
    }

    public function bins(): HasMany
    {
        return $this->hasMany(StoreBin::class, 'room_id');
    }
}