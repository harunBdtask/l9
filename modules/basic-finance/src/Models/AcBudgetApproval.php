<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class AcBudgetApproval extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'bf_ac_budget_approvals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bf_ac_budget_id',
        'bf_ac_budget_detail_id',
        'bf_account_id',
        'factory_id',
        'date',
        'code',
        'apprv_amount',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'bf_ac_budget_id' => 'integer',
        'bf_ac_budget_detail_id' => 'integer',
        'remarks' => 'string',
    ];

    public function acBudget(): BelongsTo
    {
        return $this->belongsTo(AcBudget::class, 'bf_ac_budget_id')->withDefault();
    }

    public function acBudgetDetail(): BelongsTo
    {
        return $this->belongsTo(AcBudgetDetail::class, 'bf_ac_budget_detail_id')->withDefault();
    }

    public function bfAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'bf_account_id')->withDefault();
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
