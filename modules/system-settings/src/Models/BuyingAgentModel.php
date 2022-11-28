<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;

class BuyingAgentModel extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $table = 'buying_agent';
    protected $primaryKey = 'id';
    protected $fillable = [
        'buying_agent_name',
        'factory_id',
        'address',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function buyingAgentMerchant(): HasMany
    {
        return $this->hasMany(BuyingAgentMerchantModel::class, 'buying_agent_id');
    }

    public function buyingAgentWiseFactories(): HasMany
    {
        return $this->hasMany(BuyingAgentWiseFactory::class, 'buying_agent_id', 'id');
    }

    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }
}
