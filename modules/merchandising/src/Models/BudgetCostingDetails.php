<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use App\Contracts\AuditAbleContract;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;

class BudgetCostingDetails extends Model implements AuditAbleContract
{
    use AuditAble;

    protected $table = 'budget_costing_details';
    protected $primaryKey = "id";
    protected $fillable = ['budget_id', 'type', 'details'];

    const FABRIC_COSTING = 'fabric_costing';

    protected $casts = [
        'details' => Json::class,
    ];

    const COSTING_TYPES = [
        "fabric_cost" => "Fabric Cost",
        "trims_cost" => "Trims Cost",
        "embel_cost" => "Embel. Cost",
        "gmts_wash_cost" => "Gmts.Wash",
        "comml_cost" => "Comml. Cost",
        "lab_test" => "Lab Test",
        "inspection" => "Inspection",
        "freight" => "Freight",
        "courier_cost" => "Courier Cost",
        "certificate_cost" => "Certificate Cost",
        "deffd_lc_cost" => "Deffd. LC Cost",
        "design_cost" => "Design Cost",
        "studio_cost" => "Studio Cost",
        "openrt_exp" => "Opert. Exp.",
        "cm_cost" => "CM Cost",
        "interest" => "Interest",
        "income_tax" => "Income Tax",
        "depc_amort" => "Depc. & Amort",
        "commission" => "Commission",
        "total_cost" => "Total Cost",
        "price_per_dzn" => "Price/Dzn",
        "margin_per_dzn" => "Margin/Dzn",
        "price_per_pcs" => "Price/Pcs",
        "final_cost_per_pcs" => "Final Cost/Pcs",
        "margin_per_pcs" => "Margin/Pcs",
        "bom_margin_per_pcs" => "BOM Margin/Pcs",
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class)->withDefault();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->budget->order->id)
                ->handleOrder()
                ->handleBudget()
                ->saveOrUpdate();
        });
        static::saving(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->budget->order->id)
                ->handleOrder()
                ->handleBudget()
                ->saveOrUpdate();
        });
        static::deleted(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->budget->order->id)
                ->handleOrder()
                ->handleBudget()
                ->saveOrUpdate();
        });
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
