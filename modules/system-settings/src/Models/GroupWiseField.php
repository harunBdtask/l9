<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\SystemSettings\Services\FieldsService;

class GroupWiseField extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'group_wise_fields';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'group_name',
        'fields',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'fields' => Json::class
    ];

    protected $appends = [
        'fields_value'
    ];

    public function getFieldsValueAttribute(): Collection
    {
        return collect($this->getAttribute('fields'))->map(function ($item) {
            return FieldsService::getValue($item);
        });
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'group_name', 'id')->withDefault();
    }
}
