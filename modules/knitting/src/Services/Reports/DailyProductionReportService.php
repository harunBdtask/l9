<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;

class DailyProductionReportService
{
    private $from, $to, $knittingFloor;

    public function __construct(Request $request)
    {
        $this->from = $request->get('from_date');
        $this->to = $request->get('to_date');
        $this->knittingFloor = $request->get('knitting_floor');
    }

    public function report()
    {
        $data = KnitProgramRoll::query()
            ->whereDate('production_datetime','>=' ,$this->from)
            ->whereDate('production_datetime','<=' ,$this->to)
            ->with(['knittingProgram.buyer:id,name', 'knittingProgram.planInfo', 'knitCard'])
            ->selectRaw(DB::raw('*, DATE_FORMAT(production_datetime, "%d-%m-%Y") as prod_date_time'))
            ->get();

        return $this->format($data);
    }

    private function format($data)
    {
        return $data->groupBy('knit_card_id')->map(function ($group) {
            $collection = collect($group);
            $item = $collection->first();
            $endDateTime = $collection->max('production_datetime');
            $startDateTime = $collection->min('production_datetime');
            $totalKnitQty = $collection->sum('roll_weight');
            return [
                'prod_date_time' => $item->prod_date_time ?? '',
                'endDateTime' => Carbon::parse($endDateTime)->format('d-m-y'),
                'buyer_name' => $item->knittingProgram->buyer->name ?? '',
                'booking_type' => $item->knittingProgram->planInfo->booking_type ?? '',
                'order_no' => $item->knittingProgram->planInfo->booking_no ?? '',
                'program_no' => $item->knittingProgram->program_no ?? '',
                'knit_card_no' => $item->knitCard->knit_card_no ?? '',
                'machine_no' => collect($item->knittingProgram->machine_nos)->implode(', ') ?? '',
                'machine_dia' => $item->knittingProgram->machine_dia ?? '',
                'machine_gg' => $item->knittingProgram->machine_gg ?? '',
                'fabric_type' => $item->knitCard->fabric_type ?? '',
                'color' => $item->knitCard->color ?? '',
                'gsm' => $item->knitCard->gsm ?? '',
                'order_qty' => $item->knittingProgram->planInfo->booking_qty ?? '',
                'today_knit_qty' => $collection->where('prod_date_time', date('d-m-Y'))->sum('roll_weight'),
                'total_knit_qty' => $totalKnitQty,
                'balance' => ((double)$item->knittingProgram->planInfo->booking_qty ?? 0) - (double)$totalKnitQty,
                'actual_knit_start_date' => Carbon::parse($startDateTime)->format('d-M-y'),
                'actual_knit_close_date' => Carbon::parse($endDateTime)->format('d-M-y'),
            ];
        });
    }
}
