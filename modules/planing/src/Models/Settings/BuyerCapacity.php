<?php

namespace SkylarkSoft\GoRMG\Planing\Models\Settings;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Planing\Models\FactoryCapacity;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class BuyerCapacity extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'pln_buyer_capacities';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'buyer_id',
        'month',
        'year',
        'capacity',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'factory_id', 'id');
    }
}
