<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Contracts\AuditAbleContract;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BudgetCostingDetailHistory extends Model implements AuditAbleContract
{
    use SoftDeletes;
    use AuditAble;

    protected $table = 'budget_costing_detail_history';

    protected $guarded = [
        'budget_costing_detail_id', 'details', 'created_by', 'updated_by',
    ];

    protected static function booted()
    {

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function budgetCostingDetails(): BelongsTo
    {
        return $this->belongsTo(BudgetCostingDetails::class, 'budget_costing_detail_id')->withDefault();
    }

    public function moduleName(): string
    {
        return 'merchandising';
    }

    public function path(): string
    {
        return 'merchandising';
    }
}
