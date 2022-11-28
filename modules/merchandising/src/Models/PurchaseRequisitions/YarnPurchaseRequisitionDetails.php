<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions;

use App\Models\BelongsToDealingMerchant;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class YarnPurchaseRequisitionDetails extends Model
{
    use SoftDeletes;
    use BelongsToFactory;
    use BelongsToDealingMerchant;
    use CommonModelTrait;

    protected $table = 'yarn_purchase_requisition_details';


    protected $fillable = [
        'requisition_id',
        'requisition_no',
        'unique_id',
        'buyer_id',
        'style_name',
        'factory_id',
        'yarn_count',
        'yarn_color',
        'yarn_composition',
        'percentage',
        'yarn_type',
        'uom',
        'requisition_qty',
        'rate',
        'amount',
        'yarn_in_house_date',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom')->withDefault();
    }

    public function yarnType(): BelongsTo
    {
        return $this->belongsTo(YarnType::class, 'yarn_type')->withDefault();
    }

    public function yarnComposition(): BelongsTo
    {
        return $this->belongsTo(YarnComposition::class, 'yarn_composition')->withDefault();
    }

    public function yarnCount(): BelongsTo
    {
        return $this->belongsTo(YarnCount::class, 'yarn_count')->withDefault();
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
