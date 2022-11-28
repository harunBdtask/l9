<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class SewingPlan extends Model
{
    use FactoryIdTrait;
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'sewing_plans';

    protected $fillable = [
        'buyer_id',
        'order_id',
        'garments_item_id',
        'smv',
        'purchase_order_id',
        'floor_id',
        'line_id',
        'section_id',
        'allocated_qty',
        'start_date',
        'end_date',
        'required_seconds',
        'text',
        'plan_text',
        'progress',
        'is_locked',
        'board_color',
        'notes',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = ['undoRedoSewingPlans', 'sewingPlanDetails'];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id', 'id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id', 'id')->withDefault();
    }

    public function garmentsItem()
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id', 'id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id', 'id')->withDefault();
    }

    public function floor()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Floor', 'floor_id', 'id')->withDefault();
    }

    public function line()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Line', 'line_id', 'id')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id', 'id')->withDefault();
    }

    public function undoRedoSewingPlans()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Sewingdroplets\Models\UndoRedoSewingPlan', 'sewing_plan_id', 'id');
    }

    public function sewingPlanDetails()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlanDetail', 'sewing_plan_id', 'id');
    }

    public function sewingPlanDetailsWithOutGlobalScope()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlanDetail', 'sewing_plan_id', 'id')->withoutGlobalScope('factoryId');
    }
}
