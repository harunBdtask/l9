<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class SalesTarget extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'sales_targets';
    protected $guarded = [];

    protected $cascadeDeletes = ['details'];
    protected $dates = ['deleted_at'];

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function buyingAgent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BuyingAgentModel::class, 'buying_agent_id')->withDefault();
    }

    public function teamLeader(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'team_leader_id')->withDefault();
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesTargetDetails::class, 'sales_target_id');
    }
}
