<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Iedroplets\Models\CuttingTarget;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class DateWiseCuttingProductionReport extends Model
{
    use FactoryIdTrait;

    protected $table = 'date_wise_cutting_production_reports';

    protected $fillable = [
        'cutting_date',
        'cutting_floor_id',
        'cutting_table_id',
        'cutting_details',
        'total_cutting',
        'total_rejection',
        'factory_id',
    ];

    protected $dates = [
        'cutting_date',
        'created_at',
        'updated_at'
    ];

    public function getCuttingDetailsAttribute($value)
    {
        // Cutting Details : [{"size_id": 1, "color_id": 2, "order_id": 1, "cutting_qty": 20, "cutting_rejection": 0}, {"size_id": 1, "color_id": 1, "order_id": 1, "cutting_qty": 30, "cutting_rejection": 0}]
        return json_decode($value, true);
    }

    public function setCuttingDetailsAttribute($value)
    {
        $this->attributes['cutting_details'] = json_encode($value);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }

    public function cutting_floors()
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id', 'id');
    }

    public function cutting_floorsWithoutGlobalScopes()
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id', 'id')->withoutGlobalScopes();
    }

    public function cutting_tables()
    {
        return $this->belongsTo(CuttingTable::class, 'cutting_table_id', 'id');
    }

    public function cutting_tablesWithoutGlobalScopes()
    {
        return $this->belongsTo(CuttingTable::class, 'cutting_table_id', 'id')->withoutGlobalScopes();
    }

    public function cutting_target($cutting_table_id, $date)
    {
        return CuttingTarget::where('cutting_table_id',$cutting_table_id)
            ->whereDate('target_date',$date)
            ->first();
    }

    public function cutting_targetWithoutGlobalScopes($cutting_table_id, $date)
    {
        return CuttingTarget::withoutGlobalScopes()->where('cutting_table_id',$cutting_table_id)
            ->whereDate('target_date',$date)
            ->first();
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
        return Color::withoutGlobalScopes()->where('id',$color_id)->first();
    }

    public static function getSize($size_id)
    {
        return Size::where('id',$size_id)->first();
    }
}
