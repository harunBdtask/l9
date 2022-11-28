<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateWiseCuttingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DateWiseSewingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;

class UpdateRecordsForDemo extends Controller
{
    protected $target_date;
    protected $last_date;

    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');
        $yesterday =  Carbon::today()->subDay()->format('Y-m-d');

        $this->target_date = SewingLineTarget::latest()->first()->target_date;
        $this->last_date = Carbon::parse($this->target_date)->subDay()->format('Y-m-d');

        $this->update_sewing_line_target_dates();
        $this->update_hourly_sewing_productions();

        $this->update_date_wise_sewing_productions($this->target_date, $today);
        $this->update_date_wise_sewing_productions($this->last_date, $yesterday);

        $this->update_date_wise_cutting_production($this->target_date, $today);
        $this->update_date_wise_cutting_production($this->last_date, $yesterday);

        return redirect('/');
    }

    public function update_sewing_line_target_dates()
    {
        $last_targets = SewingLineTarget::where('target_date', $this->target_date)->get();
        foreach($last_targets as $last_target){
            $sewing_line_target = SewingLineTarget::find($last_target->id);
            $sewing_line_target->target_date = date('Y-m-d', time());
            $sewing_line_target->save();
        }
    }

    public function update_hourly_sewing_productions()
    {
        $hourly_sewing_productions = HourlySewingProductionReport::where('production_date', $this->target_date)->get();
        foreach($hourly_sewing_productions as $hourly_sewing_production){
            $sewing_line_hourly_production = HourlySewingProductionReport::find($hourly_sewing_production->id);
            $sewing_line_hourly_production->production_date = date('Y-m-d', time());
            $sewing_line_hourly_production->save();
        }
    }

    public function update_date_wise_sewing_productions($target_date, $replace_date)
    {
        $date_wise_sewing_productions = DateWiseSewingProductionReport::where('sewing_date', $target_date)->get();
        foreach($date_wise_sewing_productions as $date_wise_sewing_production){
            $sewing_line_date_wise_production = DateWiseSewingProductionReport::find($date_wise_sewing_production->id);
            $sewing_line_date_wise_production->sewing_date = $replace_date;
            $sewing_line_date_wise_production->save();
        }
    }

    public function update_date_wise_cutting_production($target_date, $replace_date)
    {
        $last_cutting_productions = DateWiseCuttingProductionReport::where('cutting_date', $target_date)->get();
        foreach($last_cutting_productions as $last_cutting_production){
            $sewing_line_production = DateWiseCuttingProductionReport::find($last_cutting_production->id);
            $sewing_line_production->cutting_date = $replace_date;
            $sewing_line_production->save();
        }
    }
}
