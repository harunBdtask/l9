<?php

namespace SkylarkSoft\GoRMG\Planing\Models;

use App\Traits\Booted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Planing\Models\Settings\ItemCategory;

class FactoryCapacity extends Model
{
    use SoftDeletes;
    use Booted;

    protected $table = 'factory_capacities';
    protected $fillable = [
        "date",
        "factory_id",
        "floor_id",
        "line_id",
        "item_category_id",
        "smv",
        "efficiency",
        "operator_machine",
        "helper",
        "wh",
        "capacity_pcs",
        "capacity_available_mins",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public function itemCategory(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id', 'id')->withDefault();
    }
}
