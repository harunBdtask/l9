<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Reports;

use App\Http\Controllers\Controller;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\ManualProduction\Exports\DailyInputUnitWiseReportExport;
use SkylarkSoft\GoRMG\ManualProduction\Exports\DateFloorWiseHourlySewingOutputExport;
use SkylarkSoft\GoRMG\ManualProduction\Exports\FloorSizeWiseStyleInoutExport;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualHourlySewingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualSewingInputProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport;
use SkylarkSoft\GoRMG\ManualProduction\Services\SewingReportService;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SewingReportController extends Controller
{
    public function dailyInputUnitWiseReport(Request $request)
    {
        $factory_id = $request->get('factory_id') ?? null;
        $from_date = $request->get('from_date') ?? Carbon::now()->startOfMonth()->toDateString();
        $to_date = $request->get('to_date') ?? Carbon::now()->toDateString();
        $factories = Factory::query()->pluck('factory_name as text', 'id');
        $reports = [];
        $floors = [];
        if ($factory_id) {
            $diff_in_days = Carbon::parse($to_date)->diffInDays(Carbon::parse($from_date));
            if ($diff_in_days > 30) {
                Session::flash('error', 'Please Give one month range!');
                return redirect()->back();
            }
            $reports = SewingReportService::dailyInputUnitWise($factory_id, $from_date, $to_date);
            $floors = $reports->pluck('floor.floor_no', 'floor_id');
        }

        return view('manual-production::reports.sewing.daily_input_unit_wise_report',
            compact('factories', 'reports', 'floors', 'factory_id', 'from_date', 'to_date'));
    }

    public function dailyInputUnitWiseReportPdf(Request $request)
    {
        $factory_id = $request->get('factory_id') ?? null;
        $from_date = $request->get('from_date') ?? Carbon::now()->startOfMonth()->toDateString();
        $to_date = $request->get('to_date') ?? Carbon::now()->toDateString();
        $factories = Factory::query()->pluck('factory_name as text', 'id');
        $reports = [];
        $floors = [];
        if ($factory_id) {
            $diff_in_days = Carbon::parse($to_date)->diffInDays(Carbon::parse($from_date));
            if ($diff_in_days > 30) {
                Session::flash('error', 'Please Give one month range!');
                return redirect()->back();
            }
            $reports = SewingReportService::dailyInputUnitWise($factory_id, $from_date, $to_date);
            $floors = $reports->pluck('floor.floor_no', 'floor_id');
        }
        $pdf = PDF::loadView('manual-production::reports.sewing.daily_input_unit_wise_report_pdf',
            compact('factories', 'reports', 'floors', 'factory_id'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("daily_input_unit_wise_report.pdf");
    }

    public function dailyInputUnitWiseReportExcel(Request $request): BinaryFileResponse
    {
        $factory_id = $request->get('factory_id') ?? null;
        $from_date = $request->get('from_date') ?? Carbon::now()->startOfMonth()->toDateString();
        $to_date = $request->get('to_date') ?? Carbon::now()->toDateString();
        $factories = Factory::query()->pluck('factory_name as text', 'id');
        $reports = SewingReportService::dailyInputUnitWise($factory_id, $from_date, $to_date);
        $floors = $reports->pluck('floor.floor_no', 'floor_id');

        return Excel::download(new DailyInputUnitWiseReportExport($reports, $floors),
            'daily_input_unit_wise_report.xlsx');
    }

    public function floorSizeWiseStyleInOutSummary(Request $request)
    {
        $buyers = Buyer::query()->where('factory_id', factoryId())->pluck('name as text', 'id');
        $buyer_id = $request->get('buyer_id') ?? null;
        $order_id = $request->get('order_id') ?? null;
        $orders = [];
        $reports = [];
        $sizes = [];
        if ($order_id) {
            $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
            list($reports, $sizes) = SewingReportService::floorSizeWiseStyleInOut($buyer_id, $order_id);
        }
        return view('manual-production::reports.sewing.floor_size_wise_style_in_out_summary',
            compact('buyers', 'orders', 'reports', 'order_id', 'buyer_id', 'sizes'));
    }

    public function floorSizeWiseStyleInOutSummaryPdf(Request $request)
    {
        $buyer_id = $request->get('buyer_id') ?? null;
        $order_id = $request->get('order_id') ?? null;
        $reports = [];
        $sizes = [];
        if ($order_id) {
            list($reports, $sizes) = SewingReportService::floorSizeWiseStyleInOut($buyer_id, $order_id);
        }
        $pdf = PDF::loadView('manual-production::reports.sewing.floor_size_wise_style_in_out_summary_pdf',
            compact('reports', 'order_id', 'buyer_id', 'sizes'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("floor_size_wise_style_in_out_summary.pdf");
    }

    public function floorSizeWiseStyleInOutSummaryExcel(Request $request): BinaryFileResponse
    {
        $buyer_id = $request->get('buyer_id') ?? null;
        $order_id = $request->get('order_id') ?? null;
        $reports = [];
        $sizes = [];
        if ($order_id) {
            list($reports, $sizes) = SewingReportService::floorSizeWiseStyleInOut($buyer_id, $order_id);
        }
        return Excel::download(new FloorSizeWiseStyleInoutExport($reports, $sizes),
            'floor_size_wise_style_inout_summary.xlsx');
    }

    public function dateFloorWiseHourlySewingOutput(Request $request)
    {
        $floors = Floor::query()->orderBy('floor_no')->pluck('floor_no', 'id');
        $floor_id = $request->get('floor_id');
        $date = $request->get('date') ?? date('Y-m-d');
        $prev_date = Carbon::parse($date)->subDay()->toDateString();
        $prev_day = null;
        $perv_day_is_friday = Carbon::parse($date)->subDay()->isFriday();
        if ($perv_day_is_friday) {
            $prev_day = Carbon::parse($date)->subDays(2)->toDateString();
        } else {
            $prev_day = Carbon::parse($date)->subDay()->toDateString();
        }
        $lines = [];
        $reports = [];
        $floorwise_manual_productions = [];
        if ($floor_id) {
            list($lines, $floorwise_manual_productions, $reports) = SewingReportService::dateFloorWiseHourlySewingOutput($floor_id, $date);
        }
        return view('manual-production::reports.sewing.date_floor_wise_hourly_sewing_output',
            compact('floor_id', 'floors', 'date', 'lines', 'reports', 'floorwise_manual_productions', 'prev_date', 'prev_day'));
    }

    public function dateFloorWiseHourlySewingOutputPdf(Request $request)
    {
        $floors = Floor::query()->orderBy('floor_no')->pluck('floor_no', 'id');
        $floor_id = $request->get('floor_id');
        $date = $request->get('date') ?? date('Y-m-d');
        $prev_date = Carbon::parse($date)->subDay()->toDateString();
        $prev_day = null;
        $perv_day_is_friday = Carbon::parse($date)->subDay()->isFriday();
        if ($perv_day_is_friday) {
            $prev_day = Carbon::parse($date)->subDays(2)->toDateString();
        } else {
            $prev_day = Carbon::parse($date)->subDay()->toDateString();
        }
        $lines = [];
        $reports = [];
        $floorwise_manual_productions = [];
        if ($floor_id) {
            list($lines, $floorwise_manual_productions, $reports) = SewingReportService::dateFloorWiseHourlySewingOutput($floor_id, $date);
        }
        $pdf = PDF::loadView('manual-production::reports.sewing.date_floor_wise_hourly_sewing_output_pdf',
            compact('reports', 'floor_id', 'floors', 'date', 'lines', 'floorwise_manual_productions', 'prev_date', 'prev_day'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("date_floor_wise_hourly_sewing_output.pdf");
    }

    public function dateFloorWiseHourlySewingOutputExcel(Request $request): BinaryFileResponse
    {
        $floors = Floor::query()->orderBy('floor_no')->pluck('floor_no', 'id');
        $floor_id = $request->get('floor_id');
        $date = $request->get('date') ?? date('Y-m-d');
        $prev_date = Carbon::parse($date)->subDay()->toDateString();
        $prev_day = null;
        $perv_day_is_friday = Carbon::parse($date)->subDay()->isFriday();
        if ($perv_day_is_friday) {
            $prev_day = Carbon::parse($date)->subDays(2)->toDateString();
        } else {
            $prev_day = Carbon::parse($date)->subDay()->toDateString();
        }
        $lines = [];
        $reports = [];
        $floorwise_manual_productions = [];
        if ($floor_id) {
            list($lines, $floorwise_manual_productions, $reports) = SewingReportService::dateFloorWiseHourlySewingOutput($floor_id, $date);
        }
        $pdf = PDF::loadView('manual-production::reports.sewing.date_floor_wise_hourly_sewing_output_pdf',
            compact('reports', 'floor_id', 'floors', 'date', 'lines', 'floorwise_manual_productions', 'prev_date', 'prev_day'));
        $pdf->setPaper('A4', 'landscape');
        return Excel::download(new DateFloorWiseHourlySewingOutputExport(
            $reports, $floor_id, $floors, $date, $lines, $floorwise_manual_productions, $prev_date, $prev_day
        ), 'date_floor_wise_hourly_sewing_output.xlsx');
    }
}
