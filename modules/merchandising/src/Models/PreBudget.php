<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class PreBudget extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'job_number',
        'buyer_id',
        'factory_id',
        'number_of_machines',
        'production_per_hour',
        'production_days',
        'agent_id',
        'created_by',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(BuyingAgentModel::class, 'agent_id')->withDefault();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(PreBudgetDetail::class, 'pre_budget_id');
    }

    public function fabricCosts(): HasMany
    {
        return $this->hasMany(FabricCost::class, 'pre_budget_id');
    }

    public function knittingDyeingCosts(): HasMany
    {
        return $this->hasMany(KnittingDyeingCost::class, 'pre_budget_id');
    }

    public function trimsCosts(): HasMany
    {
        return $this->hasMany(TrimsAndAccessoriesCost::class, 'pre_budget_id');
    }

    public function othersCosts(): HasMany
    {
        return $this->hasMany(OthersCost::class, 'pre_budget_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->created_by = auth()->id();
        });
    }
}
