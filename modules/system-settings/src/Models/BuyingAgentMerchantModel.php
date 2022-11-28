<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyingAgentMerchantModel extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $table = 'buying_agent_merchants';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'buying_agent_id',
        'buying_agent_merchant_name',
        'mobile',
        'email',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function buyingAgent(): BelongsTo
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id');
    }
}
