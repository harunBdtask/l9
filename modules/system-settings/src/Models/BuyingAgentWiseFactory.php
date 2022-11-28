<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyingAgentWiseFactory extends Model
{
    use SoftDeletes;

    protected $table='buying_agent_wise_factories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'buying_agent_id',
        'factory_id'
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function buyingAgent(): BelongsTo
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id')->withDefault();
    }
}
