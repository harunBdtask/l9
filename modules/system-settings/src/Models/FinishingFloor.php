<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinishingFloor extends Model
{
    use SoftDeletes, CommonModelTrait;

    protected $table = 'finishing_floors';
    protected $fillable = [
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
                ->orWhereHas('factory', function ($factory) use ($searchKey) {
                    return $factory->where('factory_name', 'LIKE', "%{$searchKey}%");
                });
        });
    }

    public function tables(): HasMany
    {
        return $this->hasMany(FinishingTable::class, 'floor_id');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
