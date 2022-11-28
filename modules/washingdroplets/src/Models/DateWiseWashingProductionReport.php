<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class DateWiseWashingProductionReport extends Model
{
    use FactoryIdTrait;

    protected $table = "date_wise_washing_production_reports";

    protected $fillable = [
        'washing_date',
        'washing_details',
        'total_washing_sent',
        'total_washing_received',
        'total_washing_rejection',
        'factory_id',
    ];

    protected $dates = [
        'washing_date',
        'created_at',
        'updated_at'
    ];

    public function getWashingDetailsAttribute($value)
    {
        // Washing Details : [{"buyer_id": 1, "style_id": 1,  "order_id": 1, "color_id": 2, "washing_sent": 20, "washing_received": 0, "washing_rejection": 0}]
        return json_decode($value, true);
    }

    public function setWashingDetailsAttribute($value)
    {
        $this->attributes['washing_details'] = json_encode($value);
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id');
    }

    public static function getPurchaseOrder($purchase_order_id)
    {
        return PurchaseOrder::where('id',$purchase_order_id)->first();
    }

    public static function getColor($color_id)
    {
        return Color::where('id',$color_id)->first();
    }
}
