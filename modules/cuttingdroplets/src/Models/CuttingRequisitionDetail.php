<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Fabric_composition;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use App\FactoryIdTrait;

class CuttingRequisitionDetail extends Model
{
    use FactoryIdTrait, SoftDeletes;
    
    protected $table = 'cutting_requisition_details';
    protected $fillable = [
    	'cutting_requisition_id',
    	'buyer_id',
    	'order_id',
        'fabric_type',
        'composition_fabric_id',
    	'color_id',
    	'requisition_amount',
        'unit_of_measurement_id',
        'balance_amount',
        'garments_part_id',
        'batch_no',
    	'remark',
    	'approval_status',
    	'created_by',
    	'updated_by',
    	'deleted_by',
    	'factory_id'
    ];

    protected $dates = ['deleted_at'];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withDefault();
    }

    public function garments_part()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Part', 'garments_part_id')->withDefault();
    }

    public function fab_type()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\FabricType', 'fabric_type', 'id')->withDefault();
    }

    public function fabric_composition()
    {
        return $this->belongsTo(Fabric_composition::class, 'composition_fabric_id');
    }

    public function cuttingRequisition()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Cuttingdroplets\Models\CuttingRequisition', 'cutting_requisition_id')->withDefault();
    }

    public function unit_of_measurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_of_measurement_id', 'id')->withDefault()->withoutGlobalScope('factoryId');
    }

    public static function getRequisitionNoWiseBookingNo($cuttingRequisitionId)
    {
        $booking_no = self::with('order:id,booking_no')->where('cutting_requisition_id', $cuttingRequisitionId)
            ->get()
            ->map(function($order){
                return $order->order->booking_no ?? 'N/A';
            })->values()->all();
            // does not work
            /*->unique('id')
            ->values()
            ->all();*/
        $booking_no = array_unique($booking_no);

        return $booking_no;
    }
}
