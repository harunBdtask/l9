<?php

namespace SkylarkSoft\GoRMG\Planing\Models\Settings;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Planing\Models\FactoryCapacity;

class ItemCategory extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'pln_item_categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'smv_from',
        'smv_to',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function capacities(): HasMany
    {
        return $this->hasMany(FactoryCapacity::class, 'item_category_id', 'id');
    }
}
