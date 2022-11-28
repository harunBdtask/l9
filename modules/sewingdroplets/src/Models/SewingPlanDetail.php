<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class SewingPlanDetail extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'sewing_plan_details';

    protected $fillable = [
        'sewing_plan_id',
        'garments_item_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'allocated_qty',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function sewingPlan()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlan', 'sewing_plan_id', 'id')->withDefault();
    }

    public function garmentsItem()
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id', 'id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id', 'id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id', 'id')->withDefault();
    }

    public function size()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Size', 'size_id', 'id')->withDefault();
    }
    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id', 'id')->withDefault();
    }

}
