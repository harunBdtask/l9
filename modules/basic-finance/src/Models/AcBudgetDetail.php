<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\QueryBuilders\CustomQuery;

class AcBudgetDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'bf_ac_budget_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bf_ac_budget_id',
        'bf_account_id',
        'factory_id',
        'amount',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'bf_ac_budget_id' => 'integer',
        'bf_account_id' => 'integer',
        'amount' => 'decimal:2',
        'remarks' => 'string',
    ];

    public function bfBudget(): BelongsTo
    {
        return $this->belongsTo(AcBudget::class, 'bf_ac_budget_id')->withDefault();
    }

    public function bfAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'bf_account_id')->withDefault();
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
