<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinishingTable extends Model
{
    use SoftDeletes, CommonModelTrait;

    protected $table = 'finishing_tables';
    protected $fillable = [
        'floor_id',
        'factory_id',
        'name',
        'sorting',
        'responsible_person',
    ];

    public function scopeSearch($query, $searchKey)
    {
        return $query->when($searchKey, function ($q, $searchKey) {
            return $q->where('name', 'LIKE', "%{$searchKey}%")
                ->orWhere('responsible_person', 'LIKE', "%{$searchKey}%")
                ->orWhereHas('floor', function ($floor) use ($searchKey) {
                    return $floor->where('name', 'LIKE', "%{$searchKey}%");
                })
                ->orWhereHas('factory', function ($factory) use ($searchKey) {
                    return $factory->where('factory_name', 'LIKE', "%{$searchKey}%");
                });
        });
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(FinishingFloor::class, 'floor_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
