<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class CuttingPlan extends Model
{
    use FactoryIdTrait,
        SoftDeletes;

    protected $table = 'cutting_plans';
    protected $fillable = [
        'plan_date',
        'cutting_floor_id',
        'cutting_table_id',
        'section_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'cutting_delivery_date',
        'no_of_marker',
        'plan_qty',
        'rating',
        'start_date',
        'end_date',
        'duration',
        'text',
        'plan_text',
        'plan_color',
        'progress',
        'board_color',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at', 'cutting_delivery_date'];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withDefault();
    }

    public function cuttingFloor()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor', 'cutting_floor_id')->withDefault();
    }

    public function cuttingTable()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable', 'cutting_table_id')->withDefault();
    }

}
