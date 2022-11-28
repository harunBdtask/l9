<?php

namespace SkylarkSoft\GoRMG\Planing\Models\ContainerProfile;

use App\Casts\Json;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContainerSummaries extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'container_summaries';
    protected $primaryKey = 'id';
    protected $fillable = [
        'container_id',
        'ex_factory_date',
        'po_list',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'po_list' => Json::class,
    ];

    public function scopeSearch(Builder $query, $search)
    {
        return $query->when($search, function (Builder $query) use ($search) {
            return $query->where('po_list', 'LIKE', "%{$search}%")
                ->orWhereHas('containerProfile', function (Builder $query) use ($search) {
                    return $query->where('container_no', 'LIKE', "%{$search}%");
                });
        });
    }

    public function containerProfile(): BelongsTo
    {
        return $this->belongsTo(ContainerProfileDetail::class, 'container_id');
    }
}
