<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UndoRedoSewingPlan extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'undo_redo_sewing_plans';

    protected $fillable = [
        'sewing_plan_id',
        'buyer_id',
        'order_id',
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
        'undo_redo_status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function sewingPlan()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlan', 'sewing_plan_id', 'id')->withDefault();
    }

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id', 'id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id', 'id')->withDefault();
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
}
