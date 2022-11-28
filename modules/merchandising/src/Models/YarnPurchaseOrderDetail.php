<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class YarnPurchaseOrderDetail extends Model
{
    use SoftDeletes;
    use BelongsToFactory;
    use CommonModelTrait;

    protected $table = 'yarn_purchase_order_details';

    protected $fillable = [
        'yarn_purchase_order_id',
        'requisition_id',
        'requisition_details_id',
        'buyer_id',
        'factory_id',
        'budget_id',
        'yarn_type_id',
        'yarn_type',
        'uom_id',
        'wo_no',
        'unique_id',
        'style_name',
        'yarn_count_id',
        'yarn_color',
        'yarn_composition_id',
        'percentage',
        'wo_qty',
        'rate',
        'amount',
        'process_loss',
        'total_amount',
        'delivery_start_date',
        'delivery_end_date',
        'fabric_description',
        'fabric_composition_id',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function yarnType(): BelongsTo
    {
        return $this->belongsTo(YarnType::class, 'yarn_type_id')->withDefault();
    }

    public function yarnComposition(): BelongsTo
    {
        return $this->belongsTo(YarnComposition::class, 'yarn_composition_id')->withDefault();
    }

    public function yarnCount(): BelongsTo
    {
        return $this->belongsTo(YarnCount::class, 'yarn_count_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'style_name', 'style_name')->withDefault();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->wo_no = getPrefix() . 'YPR-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
            (new StyleAuditReportAction())
                ->init($model->order->id)
                ->handleOrder()
                ->handleBudget()
                ->handleFabricBooking()
                ->handleYarnPurchase()
                ->saveOrUpdate();
        });

        static::updated(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->order->id)
                ->handleOrder()
                ->handleBudget()
                ->handleFabricBooking()
                ->handleYarnPurchase()
                ->saveOrUpdate();
        });

        static::deleted(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->order->id)
                ->handleOrder()
                ->handleBudget()
                ->handleFabricBooking()
                ->handleYarnPurchase()
                ->saveOrUpdate();
        });
    }
}
