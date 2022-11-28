<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Budgets;

use App\Casts\Json;
use App\Contracts\AuditAbleContract;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Model;

class BudgetCostingTemplate extends Model implements AuditAbleContract
{
    use AuditAble;

    protected $table = "budget_costing_templates";
    protected $primaryKey = "id";
    protected $fillable = [
        'factory_id',
        'buyer_id',
        'template_name',
        'type',
        'details',
    ];

    protected $casts = [
        'details' => Json::class,
    ];

    public function moduleName(): string
    {
        return 'merchandising';
    }

    public function path(): string
    {
        return '';
    }
}
