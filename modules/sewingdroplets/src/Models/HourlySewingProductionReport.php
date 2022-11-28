<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class HourlySewingProductionReport extends Model
{
    use FactoryIdTrait;
    protected $table = "hourly_sewing_production_reports";
    protected $fillable = [
        'production_date',
        'floor_id',
        'line_id',
        'buyer_id',
        'garments_item_id',
        'order_id',
        'purchase_order_id',
        'color_id',
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

    protected $date = [
        'production_date',
        'created_at',
        'updated_at'
    ];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id', 'id');
    }

    public function buyerWithoutGlobalScope()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id', 'id')->withoutGlobalScopes();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id', 'id');
    }

    public function orderWithoutGlobalScope()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id', 'id')->withoutGlobalScopes();
    }

    public function garmentsItem()
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id', 'id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id', 'id');
    }

    public function purchaseOrderWithoutGlobalScope()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id', 'id')->withoutGlobalScopes();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id', 'id');
    }

    public function colorWithoutGLobalScope()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id', 'id')->withoutGlobalScopes();
    }

    public function floor()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Floor', 'floor_id', 'id');
    }

    public function floorWithoutGLobalScope()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Floor', 'floor_id', 'id')->withoutGlobalScopes();
    }

    public function line()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Line', 'line_id', 'id');
    }

    public function lineWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Line', 'line_id', 'id')->withoutGlobalScopes();
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id', 'id');
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

    public static function calculateDailyPurchaseOrderFloorLineWiseAvailableMinutes($date, $purchase_order_id, $floor_id, $line_id = '')
    {
        $getHourlySewingProductions = self::whereDate('production_date', $date)
            ->selectRaw("line_id, 
            SUM(hour_0) as hour_0,
            SUM(hour_1) as hour_1,
            SUM(hour_2) as hour_2,
            SUM(hour_3) as hour_3,
            SUM(hour_4) as hour_4,
            SUM(hour_5) as hour_5,
            SUM(hour_6) as hour_6,
            SUM(hour_7) as hour_7,
            SUM(hour_8) as hour_8,
            SUM(hour_9) as hour_9,
            SUM(hour_10) as hour_10,
            SUM(hour_11) as hour_11,
            SUM(hour_12) as hour_12,
            SUM(hour_13) as hour_13,
            SUM(hour_14) as hour_14,
            SUM(hour_15) as hour_15,
            SUM(hour_16) as hour_16,
            SUM(hour_17) as hour_17,
            SUM(hour_18) as hour_18,
            SUM(hour_19) as hour_19,
            SUM(hour_20) as hour_20,
            SUM(hour_21) as hour_21,
            SUM(hour_22) as hour_22,
            SUM(hour_23) as hour_23
            ")
            ->where([
                'floor_id' => $floor_id,
                'purchase_order_id' => $purchase_order_id,
            ])
            ->when($line_id != '', function ($query) use ($line_id) {
                return $query->where('line_id', $line_id);
            })->groupBy('line_id')
            ->get();

        $sewing_line_targets = SewingLineTarget::where([
            'target_date' => $date,
            'floor_id' => $floor_id
        ])->when($line_id != '', function ($query) use ($line_id) {
            return $query->where('line_id', $line_id);
        })->orderBy('created_at', 'asc')->get();

        $line_wise_working_hours = [];

        foreach ($getHourlySewingProductions as $getHourlySewingProduction) {
            $working_hour = self::getWorkingHour($getHourlySewingProduction);
            $first_last_hour_array = self::getFirstAndLastWorkingHour($getHourlySewingProduction);
            $line_wise_working_hours[] = [
                'line_id' => $getHourlySewingProduction->line_id,
                'working_hour' => $working_hour,
                'first_hour' => $first_last_hour_array['first_hour'],
                'last_hour' => $first_last_hour_array['last_hour'],
            ];
        }
        $line_wise_working_hours_collection = collect($line_wise_working_hours);

        $available_minutes = 0;

        foreach ($line_wise_working_hours_collection as $line_wise_working_hour) {
            $sewing_line_targets_exist = $sewing_line_targets->where('line_id', $line_wise_working_hour['line_id']);
            if ($sewing_line_targets_exist->count()) {
                $sewing_target_working_hour = $sewing_line_targets_exist->sum('wh');
                $final_working_hour = $line_wise_working_hour['working_hour'] <= $sewing_target_working_hour ? $line_wise_working_hour['working_hour'] : $sewing_target_working_hour;
                $sewing_line_target_manpower = 0;
                $start_wh = 0;
                foreach ($sewing_line_targets_exist as $sewing_line_target) {
                    $start_wh += $sewing_line_target->wh;
                    if ($line_wise_working_hour['first_hour'] <= $start_wh) {
                        $sewing_line_target_manpower += $sewing_line_target->operator + $sewing_line_target->helper;
                        break;
                    }

                }
                if ($sewing_line_target_manpower <= 0) {
                    $sewing_line_target_manpower = $sewing_line_targets_exist->last()->operator + $sewing_line_targets_exist->last()->helper;
                    $final_working_hour = 1;
                }
                $available_minutes += $sewing_line_target_manpower * $final_working_hour * 60;
            }
        }

        return $available_minutes;

    }

    private static function getFirstAndLastWorkingHour($getHourlySewingProduction)
    {
        $first_hour = 0;
        $last_hour = 0;
        if ($getHourlySewingProduction->hour_0 > 0 || $getHourlySewingProduction->hour_1 > 0 || $getHourlySewingProduction->hour_2 > 0 || $getHourlySewingProduction->hour_3 > 0 || $getHourlySewingProduction->hour_4 > 0 || $getHourlySewingProduction->hour_5 > 0 || $getHourlySewingProduction->hour_6 > 0 || $getHourlySewingProduction->hour_7 > 0 || $getHourlySewingProduction->hour_8 > 0) {
            $first_hour = 1;
        } elseif ($getHourlySewingProduction->hour_9 > 0) {
            $first_hour = 2;
        } elseif ($getHourlySewingProduction->hour_10 > 0) {
            $first_hour = 3;
        } elseif ($getHourlySewingProduction->hour_11 > 0) {
            $first_hour = 4;
        } elseif ($getHourlySewingProduction->hour_12 > 0 || $getHourlySewingProduction->hour_13 > 0) {
            $first_hour = 5;
        } elseif ($getHourlySewingProduction->hour_14 > 0) {
            $first_hour = 6;
        } elseif ($getHourlySewingProduction->hour_15 > 0) {
            $first_hour = 7;
        } elseif ($getHourlySewingProduction->hour_16 > 0) {
            $first_hour = 8;
        } elseif ($getHourlySewingProduction->hour_17 > 0) {
            $first_hour = 9;
        } elseif ($getHourlySewingProduction->hour_18 > 0) {
            $first_hour = 10;
        } elseif ($getHourlySewingProduction->hour_19 > 0) {
            $first_hour = 11;
        } elseif ($getHourlySewingProduction->hour_20 > 0) {
            $first_hour = 12;
        } elseif ($getHourlySewingProduction->hour_21 > 0 || $getHourlySewingProduction->hour_22 > 0 || $getHourlySewingProduction->hour_23 > 0) {
            $first_hour = 13;
        }

        if ($getHourlySewingProduction->hour_21 > 0 || $getHourlySewingProduction->hour_22 > 0 || $getHourlySewingProduction->hour_23 > 0) {
            $last_hour = 13;
        } elseif ($getHourlySewingProduction->hour_20 > 0) {
            $last_hour = 12;
        } elseif ($getHourlySewingProduction->hour_19 > 0) {
            $last_hour = 11;
        } elseif ($getHourlySewingProduction->hour_18 > 0) {
            $last_hour = 10;
        } elseif ($getHourlySewingProduction->hour_17 > 0) {
            $last_hour = 9;
        } elseif ($getHourlySewingProduction->hour_16 > 0) {
            $last_hour = 8;
        } elseif ($getHourlySewingProduction->hour_15 > 0) {
            $last_hour = 7;
        } elseif ($getHourlySewingProduction->hour_14 > 0) {
            $last_hour = 6;
        } elseif ($getHourlySewingProduction->hour_12 > 0 || $getHourlySewingProduction->hour_13 > 0) {
            $last_hour = 5;
        } elseif ($getHourlySewingProduction->hour_11 > 0) {
            $last_hour = 4;
        } elseif ($getHourlySewingProduction->hour_10 > 0) {
            $last_hour = 3;
        } elseif ($getHourlySewingProduction->hour_9 > 0) {
            $last_hour = 2;
        } elseif ($getHourlySewingProduction->hour_0 > 0 || $getHourlySewingProduction->hour_1 > 0 || $getHourlySewingProduction->hour_2 > 0 || $getHourlySewingProduction->hour_3 > 0 || $getHourlySewingProduction->hour_4 > 0 || $getHourlySewingProduction->hour_5 > 0 || $getHourlySewingProduction->hour_6 > 0 || $getHourlySewingProduction->hour_7 > 0 || $getHourlySewingProduction->hour_8 > 0) {
            $last_hour = 1;
        }

        $data = ['first_hour' => $first_hour, 'last_hour' => $last_hour];
        return $data;
    }

    private static function getWorkingHour($getHourlySewingProduction)
    {
        $wh = 0;
        if ($getHourlySewingProduction->hour_0 > 0 || $getHourlySewingProduction->hour_1 > 0 || $getHourlySewingProduction->hour_2 > 0 || $getHourlySewingProduction->hour_3 > 0 || $getHourlySewingProduction->hour_4 > 0 || $getHourlySewingProduction->hour_5 > 0 || $getHourlySewingProduction->hour_6 > 0 || $getHourlySewingProduction->hour_7 > 0 || $getHourlySewingProduction->hour_8 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_9 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_10 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_11 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_12 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_14 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_15 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_16 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_17 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_18 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_19 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_20 > 0) {
            $wh++;
        }
        if ($getHourlySewingProduction->hour_21 > 0 || $getHourlySewingProduction->hour_22 > 0 || $getHourlySewingProduction->hour_23 > 0) {
            $wh++;
        }
        return $wh;
    }
}
