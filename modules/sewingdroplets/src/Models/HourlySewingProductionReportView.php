<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class HourlySewingProductionReportView extends Model
{
    use FactoryIdTrait;

    protected $table = "hourly_sewing_production_report_views";
    protected $fillable = [
        'production_date',
        'floor_id',
        'floor',
        'line_id',
        'line_no',
        'buyer_id',
        'buyer',
        'order_id',
        'garments_item_id',
        'ordr_no',
        'booking_no',
        'purchase_order_id',
        'po',
        'smv',
        'color_id',
        'color',
        'hour_0',
        'hour_1',
        'hour_2',
        'hour_3',
        'hour_4',
        'hour_5',
        'hour_6',
        'hour_7',
        'hour_8',
        'hour_9',
        'hour_10',
        'hour_11',
        'hour_12',
        'hour_13',
        'hour_14',
        'hour_15',
        'hour_16',
        'hour_17',
        'hour_18',
        'hour_19',
        'hour_20',
        'hour_21',
        'hour_22',
        'hour_23',
        'sewing_rejection',
        'factory_id',
    ];
    

    public function line()
    {
        return $this->belongsTo(Line::class, 'line_id', 'id');
    }

    public function getColorWiseOutputAttribute()
    {
        return $this->attributes['hour_0']
            + $this->attributes['hour_1']
            + $this->attributes['hour_2']
            + $this->attributes['hour_3']
            + $this->attributes['hour_4']
            + $this->attributes['hour_5']
            + $this->attributes['hour_6']
            + $this->attributes['hour_7']
            + $this->attributes['hour_8']
            + $this->attributes['hour_9']
            + $this->attributes['hour_10']
            + $this->attributes['hour_11']
            + $this->attributes['hour_12']
            + $this->attributes['hour_13']
            + $this->attributes['hour_14']
            + $this->attributes['hour_15']
            + $this->attributes['hour_16']
            + $this->attributes['hour_17']
            + $this->attributes['hour_18']
            + $this->attributes['hour_19']
            + $this->attributes['hour_20']
            + $this->attributes['hour_21']
            + $this->attributes['hour_22']
            + $this->attributes['hour_23'];
    }

    public function getTotalOutputAttribute()
    {
        $this->attributes['total_output'] =
            $this->attributes['hour_0']
            + $this->attributes['hour_1']
            + $this->attributes['hour_2']
            + $this->attributes['hour_3']
            + $this->attributes['hour_4']
            + $this->attributes['hour_5']
            + $this->attributes['hour_6']
            + $this->attributes['hour_7']
            + $this->attributes['hour_8']
            + $this->attributes['hour_9']
            + $this->attributes['hour_10']
            + $this->attributes['hour_11']
            + $this->attributes['hour_12']
            + $this->attributes['hour_13']
            + $this->attributes['hour_14']
            + $this->attributes['hour_15']
            + $this->attributes['hour_16']
            + $this->attributes['hour_17']
            + $this->attributes['hour_18']
            + $this->attributes['hour_19']
            + $this->attributes['hour_20']
            + $this->attributes['hour_21']
            + $this->attributes['hour_22']
            + $this->attributes['hour_23'];

        return $this->attributes['total_output'];

    }

    public function garmentsItem()
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id', 'id')->withDefault();
    }
}
