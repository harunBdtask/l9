<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use Carbon\Carbon;
use DB;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;

class DashboardDataController extends Controller
{
    private $todayDate;
    private $yesterdayDate;
    private $thisWeekStartDate;
    private $thisWeekEndDate;
    private $lastWeekStartDate;
    private $lastWeekEndDate;
    private $thisMonthStartDate;
    private $thisMonthEndDate;
    private $lastMonthStartDate;
    private $lastMonthEndDate;
 
    public function __construct() {   
        $this->todayDate = Carbon::today()->format('Y-m-d');
        $this->yesterdayDate = Carbon::yesterday()->format('Y-m-d');
        $this->thisWeekStartDate = Carbon::today()->startOfWeek()->format('Y-m-d');
        $this->thisWeekEndDate = Carbon::today()->endOfWeek()->format('Y-m-d');
        $this->lastWeekStartDate = Carbon::today()->startOfWeek()->subDay()->startOfWeek()->format('Y-m-d');
        $this->lastWeekEndDate = Carbon::today()->startOfWeek()->subDay()->endOfWeek()->format('Y-m-d');
        $this->thisMonthStartDate = Carbon::today()->startOfMonth()->format('Y-m-d');
        $this->thisMonthEndDate = Carbon::today()->endOfMonth()->format('Y-m-d');
        $this->lastMonthStartDate = Carbon::today()->startOfMonth()->subDay()->startOfMonth()->format('Y-m-d');
        $this->lastMonthEndDate = Carbon::today()->startOfMonth()->subDay()->endOfMonth()->format('Y-m-d');
    } 

    public function getDashboardRelatedData()
    {
        $dashboardRelatedQuery = DateAndColorWiseProduction::where('production_date', '>=', $this->lastMonthStartDate)
            ->where('production_date', '<=', $this->todayDate);           

        $todayQuery = clone $dashboardRelatedQuery;
        $todayData = $todayQuery->whereDate('production_date', $this->todayDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output
            ')
            ->first();

        $yesterdayQuery = clone $dashboardRelatedQuery;
        $yesterdayData = $yesterdayQuery->whereDate('production_date', $this->yesterdayDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output
            ')
            ->first();

        $thisWeekQuery = clone $dashboardRelatedQuery;
        $thisWeekData = $thisWeekQuery->whereDate('production_date', '>=', $this->thisWeekStartDate)
            ->whereDate('production_date', '<=', $this->thisWeekEndDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output
            ')
            ->first();

        $lastWeekQuery = clone $dashboardRelatedQuery;
        $lastWeekData = $lastWeekQuery->whereDate('production_date', '>=', $this->lastWeekStartDate)
            ->whereDate('production_date', '<=', $this->lastWeekEndDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output
            ')
            ->first();

        $thisMonthQuery = clone $dashboardRelatedQuery;
        $thisMonthData = $thisMonthQuery->whereDate('production_date', '>=', $this->thisMonthStartDate)
            ->whereDate('production_date', '<=', $this->thisMonthEndDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output
            ')
            ->first();
    
        $lastMonthQuery = clone $dashboardRelatedQuery;
        $lastMonthData = $lastMonthQuery->whereDate('production_date', '>=', $this->lastMonthStartDate)
            ->whereDate('production_date', '<=', $this->lastMonthEndDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output
            ')
            ->first();

        $dashboard_related_print_embr_data = $this->getDashboardRelatedPrintEmbrData();

        return [
           // print sent & received data
           'today_print_sent' => $dashboard_related_print_embr_data['today_print_sent'] ?? 0,
           'today_print_seceived' => $dashboard_related_print_embr_data['today_print_received'] ?? 0,

           'last_day_print_sent' => $dashboard_related_print_embr_data['last_day_print_sent'] ?? 0,
           'last_day_print_seceived' => $dashboard_related_print_embr_data['last_day_print_received'] ?? 0,

           'this_week_print_sent' => $dashboard_related_print_embr_data['this_week_print_sent'] ?? 0,
           'this_week_print_seceived' => $dashboard_related_print_embr_data['this_week_print_received'] ?? 0,

           'last_week_print_sent' => $dashboard_related_print_embr_data['last_week_print_sent'] ?? 0,
           'last_week_print_seceived' => $dashboard_related_print_embr_data['last_week_print_received'] ?? 0,

           'this_month_print_sent' => $dashboard_related_print_embr_data['this_month_print_sent'] ?? 0,
           'this_month_print_seceived' => $dashboard_related_print_embr_data['this_month_print_received'] ?? 0,

           'last_month_print_sent' => $dashboard_related_print_embr_data['last_month_print_sent'] ?? 0,
           'last_month_print_seceived' => $dashboard_related_print_embr_data['last_month_print_received'] ?? 0,

           // embroidery sent & received data
           'today_embr_sent' => $dashboard_related_print_embr_data['today_embr_sent'] ?? 0,
           'today_embr_seceived' => $dashboard_related_print_embr_data['today_embr_received'] ?? 0,

           'last_day_embr_sent' => $dashboard_related_print_embr_data['last_day_embr_sent'] ?? 0,
           'last_day_embr_seceived' => $dashboard_related_print_embr_data['last_day_embr_received'] ?? 0,

           'this_week_embr_sent' => $dashboard_related_print_embr_data['this_week_embr_sent'] ?? 0,
           'this_week_embr_seceived' => $dashboard_related_print_embr_data['this_week_embr_received'] ?? 0,

           'last_week_embr_sent' => $dashboard_related_print_embr_data['last_week_embr_sent'] ?? 0,
           'last_week_embr_seceived' => $dashboard_related_print_embr_data['last_week_embr_received'] ?? 0,

           'this_month_embr_sent' => $dashboard_related_print_embr_data['this_month_embr_sent']?? 0,
           'this_month_embr_seceived' => $dashboard_related_print_embr_data['this_month_embr_received'] ?? 0,

           'last_month_embr_sent' => $dashboard_related_print_embr_data['last_month_embr_sent'] ?? 0,
           'last_month_embr_seceived' => $dashboard_related_print_embr_data['last_month_embr_received'] ?? 0,

           // input data
           'today_input' => $todayData->input ?? 0,
           'last_day_input' => $yesterdayData->input ?? 0,
           'this_week_input' => $thisWeekData->input ?? 0,
           'last_week_input' => $lastWeekData->input ?? 0,
           'this_month_input' => $thisMonthData->input ?? 0,
           'last_month_input' => $lastMonthData->input ?? 0,

           // output data
           'today_output' => $todayData->output ?? 0,
           'last_day_output' => $yesterdayData->output ?? 0,
           'this_week_output' => $thisWeekData->output ?? 0,
           'last_week_output' => $lastWeekData->output ?? 0,
           'this_month_output' => $thisMonthData->output ?? 0,
           'last_month_output' => $lastMonthData->input ?? 0,          
       ];
    }

    public function getDashboardRelatedPrintEmbrData()
    {
        $dashboardRelatedQuery = DateWisePrintEmbrProductionReport::where('production_date', '>=', $this->lastMonthStartDate)
            ->where('production_date', '<=', $this->todayDate);

        $todayQuery = clone $dashboardRelatedQuery;
        $todayData = $todayQuery->whereDate('production_date', $this->todayDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidery_rejection_qty) as embrRejection,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived
            ')
            ->first();

        $yesterdayQuery = clone $dashboardRelatedQuery;
        $yesterdayData = $yesterdayQuery->whereDate('production_date', $this->yesterdayDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidery_rejection_qty) as embrRejection,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived
            ')
            ->first();

        $thisWeekQuery = clone $dashboardRelatedQuery;
        $thisWeekData = $thisWeekQuery->whereDate('production_date', '>=', $this->thisWeekStartDate)
            ->whereDate('production_date', '<=', $this->thisWeekEndDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidery_rejection_qty) as embrRejection,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived
            ')
            ->first();

        $lastWeekQuery = clone $dashboardRelatedQuery;
        $lastWeekData = $lastWeekQuery->whereDate('production_date', '>=', $this->lastWeekStartDate)
            ->whereDate('production_date', '<=', $this->lastWeekEndDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidery_rejection_qty) as embrRejection,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived
            ')
            ->first();

        $thisMonthQuery = clone $dashboardRelatedQuery;
        $thisMonthData = $thisMonthQuery->whereDate('production_date', '>=', $this->thisMonthStartDate)
            ->whereDate('production_date', '<=', $this->thisMonthEndDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidery_rejection_qty) as embrRejection,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived
            ')
            ->first();

        $lastMonthQuery = clone $dashboardRelatedQuery;
        $lastMonthData = $lastMonthQuery->whereDate('production_date', '>=', $this->lastMonthStartDate)
            ->whereDate('production_date', '<=', $this->lastMonthEndDate)
            ->selectRaw('
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidery_rejection_qty) as embrRejection,
                SUM(embroidery_sent_qty) as embrSent,
                SUM(embroidery_received_qty) as embrReceived
            ')
            ->first();

        return [
            // print sent & received data
            'today_print_sent' => $todayData->printSent ?? 0,
            'today_print_received' => $todayData->printReceived ?? 0,
            'today_print_rejection' => $todayData->printRejection ?? 0,

            'last_day_print_sent' => $yesterdayData->printSent ?? 0,
            'last_day_print_received' => $yesterdayData->printReceived ?? 0,
            'last_day_print_rejection' => $yesterdayData->printRejection ?? 0,

            'this_week_print_sent' => $thisWeekData->printSent ?? 0,
            'this_week_print_received' => $thisWeekData->printReceived ?? 0,
            'this_week_print_rejection' => $thisWeekData->printRejection ?? 0,

            'last_week_print_sent' => $lastWeekData->printSent ?? 0,
            'last_week_print_received' => $lastWeekData->printReceived ?? 0,
            'last_week_print_rejection' => $lastWeekData->printRejection ?? 0,

            'this_month_print_sent' => $thisMonthData->printSent ?? 0,
            'this_month_print_received' => $thisMonthData->printReceived ?? 0,
            'this_month_print_rejection' => $thisMonthData->printRejection ?? 0,

            'last_month_print_sent' => $lastMonthData->printSent ?? 0,
            'last_month_print_received' => $lastMonthData->printReceived ?? 0,
            'last_month_print_rejection' => $lastMonthData->printRejection ?? 0,

            // embroidery sent & received data
            'today_embr_sent' => $todayData->embrSent ?? 0,
            'today_embr_received' => $todayData->embrReceived ?? 0,
            'today_embr_rejection' => $todayData->embrRejection ?? 0,

            'last_day_embr_sent' => $yesterdayData->embrSent ?? 0,
            'last_day_embr_received' => $yesterdayData->embrReceived ?? 0,
            'last_day_embr_rejection' => $yesterdayData->embrRejection ?? 0,

            'this_week_embr_sent' => $thisWeekData->embrSent ?? 0,
            'this_week_embr_received' => $thisWeekData->embrReceived ?? 0,
            'this_week_embr_rejection' => $thisWeekData->embrRejection ?? 0,

            'last_week_embr_sent' => $lastWeekData->embrSent ?? 0,
            'last_week_embr_received' => $lastWeekData->embrReceived ?? 0,
            'last_week_embr_rejection' => $lastWeekData->embrRejection ?? 0,

            'this_month_embr_sent' => $thisMonthData->embrSent ?? 0,
            'this_month_embr_received' => $thisMonthData->embrReceived ?? 0,
            'this_month_embr_rejection' => $thisMonthData->embrRejection ?? 0,

            'last_month_embr_sent' => $lastMonthData->embrSent ?? 0,
            'last_month_embr_received' => $lastMonthData->embrReceived ?? 0,
            'last_month_embr_rejection' => $lastMonthData->embrRejection ?? 0,
        ];
    }

    /*public function getDashboardRelatedData()
    {
        $dashboardRelatedQuery = DateAndColorWiseProduction::where('production_date', '>=', $this->lastMonthStartDate)
            ->where('production_date', '<=', $this->todayDate);           

        $todayQuery = clone $dashboardRelatedQuery;
        $todayData = $todayQuery->whereDate('production_date', $this->todayDate)
            ->selectRaw('
                SUM(cutting_rejection_qty) as fabricRejection,
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidary_sent_qty) as embrSent,
                SUM(embroidary_received_qty) as embrReceived,
                SUM(embroidary_rejection_qty) as embrRejection,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output,
                SUM(sewing_rejection_qty) as sewingRejection
            ')->first();

        $yesterdayQuery = clone $dashboardRelatedQuery;
        $yesterdayData = $yesterdayQuery->whereDate('production_date', $this->yesterdayDate)
            ->selectRaw('
                SUM(cutting_rejection_qty) as fabricRejection,
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidary_sent_qty) as embrSent,
                SUM(embroidary_received_qty) as embrReceived,
                SUM(embroidary_rejection_qty) as embrRejection,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output,
                SUM(sewing_rejection_qty) as sewingRejection
            ')->first();

        $thisWeekQuery = clone $dashboardRelatedQuery;
        $thisWeekData = $thisWeekQuery->whereDate('production_date', '>=', $this->thisWeekStartDate)
            ->whereDate('production_date', '<=', $this->thisWeekEndDate)
            ->selectRaw('
                SUM(cutting_rejection_qty) as fabricRejection,
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidary_sent_qty) as embrSent,
                SUM(embroidary_received_qty) as embrReceived,
                SUM(embroidary_rejection_qty) as embrRejection,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output,
                SUM(sewing_rejection_qty) as sewingRejection
            ')->first();

        $lastWeekQuery = clone $dashboardRelatedQuery;
        $lastWeekData = $lastWeekQuery->whereDate('production_date', '>=', $this->lastWeekStartDate)
            ->whereDate('production_date', '<=', $this->lastWeekEndDate)
            ->selectRaw('
                SUM(cutting_rejection_qty) as fabricRejection,
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidary_sent_qty) as embrSent,
                SUM(embroidary_received_qty) as embrReceived,
                SUM(embroidary_rejection_qty) as embrRejection,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output,
                SUM(sewing_rejection_qty) as sewingRejection
            ')->first();

        $thisMonthQuery = clone $dashboardRelatedQuery;
        $thisMonthData = $thisMonthQuery->whereDate('production_date', '>=', $this->thisMonthStartDate)
            ->whereDate('production_date', '<=', $this->thisMonthEndDate)
            ->selectRaw('
                SUM(cutting_rejection_qty) as fabricRejection,
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidary_sent_qty) as embrSent,
                SUM(embroidary_received_qty) as embrReceived,
                SUM(embroidary_rejection_qty) as embrRejection,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output,
                SUM(sewing_rejection_qty) as sewingRejection
            ')->first();
    
        $lastMonthQuery = clone $dashboardRelatedQuery;
        $lastMonthData = $lastMonthQuery->whereDate('production_date', '>=', $this->lastMonthStartDate)
            ->whereDate('production_date', '<=', $this->lastMonthEndDate)
            ->selectRaw('
                SUM(cutting_rejection_qty) as fabricRejection,
                SUM(print_sent_qty) as printSent,
                SUM(print_received_qty) as printReceived,
                SUM(print_rejection_qty) as printRejection,
                SUM(embroidary_sent_qty) as embrSent,
                SUM(embroidary_received_qty) as embrReceived,
                SUM(embroidary_rejection_qty) as embrRejection,
                SUM(input_qty) as input,
                SUM(sewing_output_qty) as output,
                SUM(sewing_rejection_qty) as sewingRejection
            ')->first();

        return [
           // print sent & received data
           'today_print_sent' => $todayData->printSent ?? 0,
           'today_print_seceived' => $todayData->printReceived ?? 0,

           'last_day_print_sent' => $yesterdayData->printSent ?? 0,
           'last_day_print_seceived' => $yesterdayData->printReceived ?? 0,

           'this_week_print_sent' => $thisWeekData->printSent ?? 0,
           'this_week_print_seceived' => $thisWeekData->printReceived ?? 0,

           'last_week_print_sent' => $lastWeekData->printSent ?? 0,
           'last_week_print_seceived' => $lastWeekData->printReceived ?? 0,

           'this_month_print_sent' => $thisMonthData->printSent ?? 0,
           'this_month_print_seceived' => $thisMonthData->printReceived ?? 0,

           'last_month_print_sent' => $lastMonthData->printSent ?? 0,
           'last_month_print_seceived' => $lastMonthData->printReceived ?? 0,

           // embroidary sent & received data
           'today_embr_sent' => $todayData->embrSent ?? 0,
           'today_embr_seceived' => $todayData->embrReceived ?? 0,

           'last_day_embr_sent' => $yesterdayData->embrSent ?? 0,
           'last_day_embr_seceived' => $yesterdayData->embrReceived ?? 0,

           'this_week_embr_sent' => $thisWeekData->embrSent ?? 0,
           'this_week_embr_seceived' => $thisWeekData->embrReceived ?? 0,

           'last_week_embr_sent' => $lastWeekData->embrSent ?? 0,
           'last_week_embr_seceived' => $lastWeekData->embrReceived ?? 0,

           'this_month_embr_sent' => $thisMonthData->embrSent ?? 0,
           'this_month_embr_seceived' => $thisMonthData->embrReceived ?? 0,

           'last_month_embr_sent' => $lastMonthData->embrSent ?? 0,
           'last_month_embr_seceived' => $lastMonthData->embrReceived ?? 0,

           // input data
           'today_input' => $todayData->input ?? 0,
           'last_day_input' => $yesterdayData->input ?? 0,
           'this_week_input' => $thisWeekData->input ?? 0,
           'last_week_input' => $lastWeekData->input ?? 0,
           'this_month_input' => $thisMonthData->input ?? 0,
           'last_month_input' => $lastMonthData->input ?? 0,

           // output data
           'today_output' => $todayData->output ?? 0,
           'last_day_output' => $yesterdayData->output ?? 0,
           'this_week_output' => $thisWeekData->output ?? 0,
           'last_week_output' => $lastWeekData->output ?? 0,
           'this_month_output' => $thisMonthData->output ?? 0,
           'last_month_output' => $lastMonthData->input ?? 0,          
       ];
    }*/

    public function getAllRejectionData()
    {
        $result = [];
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        $rejections_data = BundleCard::withoutGlobalScope('factoryId')
            ->where('status', 1)
            ->where('created_at', '>=', Carbon::today()->startOfMonth()->subDay()->startOfMonth())
            ->where('created_at', '<=', Carbon::today()->endOfMonth())
            ->where('factory_id', factoryId());

        $todayRrejection = clone $rejections_data;
        $today = $todayRrejection->whereDate('updated_at', $today)
            ->first([DB::raw("SUM(total_rejection) as tfab_rejection, SUM(print_rejection) as tprint_rejection, SUM(sewing_rejection) as tsewing_rejection, SUM(washing_rejection) as twashing_rejection")]);
        $result[] = [
            'title' => 'Today',
            'tfab_rejection' => $today->tfab_rejection ?? 0,
            'tprint_rejection' => $today->tprint_rejection ?? 0,
            'tsewing_rejection' => $today->tsewing_rejection ?? 0,
            'twashing_rejection' => $today->twashing_rejection ?? 0
        ];

        $lastDayRejection = clone $rejections_data;
        $lastDay = $lastDayRejection->whereDate('updated_at', $yesterday)
            ->first([DB::raw("SUM(total_rejection) as tfab_rejection, SUM(print_rejection) as tprint_rejection, SUM(sewing_rejection) as tsewing_rejection, SUM(washing_rejection) as twashing_rejection")]);
        $result[] = [
            'title' => 'Last Day',
            'tfab_rejection' => $lastDay->tfab_rejection ?? 0,
            'tprint_rejection' => $lastDay->tprint_rejection ?? 0,
            'tsewing_rejection' => $lastDay->tsewing_rejection ?? 0,
            'twashing_rejection' => $today->twashing_rejection ?? 0
        ];

        $thisWeekRejection = clone $rejections_data;
        $thisWeek = $thisWeekRejection->whereDay('created_at', '>=', Carbon::today()->startOfWeek()->day)
            ->whereDay('created_at', '<=', Carbon::today()->endOfWeek()->day)
            ->first([DB::raw("SUM(total_rejection) as tfab_rejection, SUM(print_rejection) as tprint_rejection, SUM(sewing_rejection) as tsewing_rejection, SUM(washing_rejection) as twashing_rejection")]);
        $result[] = [
            'title' => 'This Week',
            'tfab_rejection' => $thisWeek->tfab_rejection ?? 0,
            'tprint_rejection' => $thisWeek->tprint_rejection ?? 0,
            'tsewing_rejection' => $thisWeek->tsewing_rejection ?? 0,
            'twashing_rejection' => $thisWeek->twashing_rejection ?? 0
        ];

        $lastWeekRejection = clone $rejections_data;
        $lastWeek = $lastWeekRejection->where('created_at', '>=', Carbon::today()->startOfWeek()->subDay()->startOfWeek())
            ->where('created_at', '<=', Carbon::today()->startOfWeek()->subDay()->endOfWeek())
            ->first([DB::raw("SUM(total_rejection) as tfab_rejection, SUM(print_rejection) as tprint_rejection, SUM(sewing_rejection) as tsewing_rejection, SUM(washing_rejection) as twashing_rejection")]);
        $result[] = [
            'title' => 'Last Week',
            'tfab_rejection' => $lastWeek->tfab_rejection ?? 0,
            'tprint_rejection' => $lastWeek->tprint_rejection ?? 0,
            'tsewing_rejection' => $lastWeek->tsewing_rejection ?? 0,
            'twashing_rejection' => $lastWeek->twashing_rejection ?? 0
        ];

        $thisMonthRejection = clone $rejections_data;
        $thisMonth = $thisMonthRejection->where('created_at', '>=', Carbon::today()->startOfMonth())
            ->where('created_at', '<=', Carbon::today()->endOfMonth())
            ->first([DB::raw("SUM(total_rejection) as tfab_rejection, SUM(print_rejection) as tprint_rejection, SUM(sewing_rejection) as tsewing_rejection, SUM(washing_rejection) as twashing_rejection")]);
        $result[] = [
            'title' => 'This Month',
            'tfab_rejection' => $thisMonth->tfab_rejection ?? 0,
            'tprint_rejection' => $thisMonth->tprint_rejection ?? 0,
            'tsewing_rejection' => $thisMonth->tsewing_rejection ?? 0,
            'twashing_rejection' => $thisMonth->twashing_rejection ?? 0
        ];


        $lastMonthRejection = clone $rejections_data;
        $lastMonth = $lastMonthRejection->where('created_at', '>=', Carbon::today()->startOfMonth()->subDay()->startOfMonth())
            ->where('created_at', '<=', Carbon::today()->startOfMonth()->subDay()->endOfMonth())
            ->first([DB::raw("SUM(total_rejection) as tfab_rejection, SUM(print_rejection) as tprint_rejection, SUM(sewing_rejection) as tsewing_rejection, SUM(washing_rejection) as twashing_rejection")]);
        $result[] = [
            'title' => 'Last Month',
            'tfab_rejection' => $lastMonth->tfab_rejection ?? 0,
            'tprint_rejection' => $lastMonth->tprint_rejection ?? 0,
            'tsewing_rejection' => $lastMonth->tsewing_rejection ?? 0,
            'twashing_rejection' => $lastMonth->twashing_rejection ?? 0
        ];

        return $result;
    }

}
