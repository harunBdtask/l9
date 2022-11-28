<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class DateWisePrintProductionReport extends Model
{
    use FactoryIdTrait;

    protected $table = "date_wise_print_production_reports";

    protected $fillable = [
        'print_date',
        'print_details',
        'total_print_sent',
        'total_print_received',
        'total_print_rejection',
        'factory_id',
    ];

    protected $dates = [
        'print_date',
        'created_at',
        'updated_at'
    ];

    public function getPrintDetailsAttribute($value)
    {        
        return json_decode($value, true);
    }

    public function setPrintDetailsAttribute($value)
    {
        $this->attributes['print_details'] = json_encode($value);
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id');
    }

    public static function getPurchaseOrder($order_id)
    {
        return PurchaseOrder::where('id', $order_id)->first();
    }

    public static function getPurchaseOrderWithoutGlobalScopes($order_id)
    {
        return PurchaseOrder::withoutGlobalScopes()->where('id', $order_id)->first();
    }

    public static function getColor($color_id)
    {
        return Color::where('id', $color_id)->first();
    }

    public static function getColorWithoutGlobalScopes($color_id)
    {
        return Color::withoutGlobalScopes()->where('id', $color_id)->first();
    }

    public static function getSize($size_id)
    {
        return Size::where('id', $size_id)->first();
    }

}
