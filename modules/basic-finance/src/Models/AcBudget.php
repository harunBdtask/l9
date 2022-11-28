<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;

class AcBudget extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'bf_ac_budgets';
    protected $primaryKey = 'id';
    protected $fillable = [
        'month',
        'year',
        'date',
        'factory_id',
        'code',
        'total_amount',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'string',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(AcBudgetDetail::class, 'bf_ac_budget_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(AcBudgetApproval::class, 'bf_ac_budget_id');
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    /**
     * @param $query
     * @return CustomQuery
     */
    public function newEloquentBuilder($query): CustomQuery
    {
        return new CustomQuery($query);
    }
}
