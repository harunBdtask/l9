<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\LineWiseHourlySewingReportExport;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\LineWiseSewingReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Iedroplets\Models\InspectionSchedule;
use SkylarkSoft\GoRMG\Iedroplets\Models\NextSchedule;
use SkylarkSoft\GoRMG\Sewingdroplets\Services\LineWiseOutputService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use Carbon\Carbon, Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\DailySewingForecastReportExport;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;

class LineWiseOutputController extends Controller
{
    private $outputService;

    public function __construct()
    {
        $this->outputService = new LineWiseOutputService();
    }

    public function floorLineWiseSewingReport(Request $request)
    {
        $floorId = $request->floor_id ?? 'all';
        $fromDate = $request->from_date ?? Carbon::now()->subDays(45)->toDateString();
        $toDate = $request->to_date ?? Carbon::now()->toDateString();
        $floor_line_wise_report = $this->floorLineWiseSewingReportData($floorId, $fromDate, $toDate);

        $floors = Floor::pluck('floor_no', 'id')->prepend('All Floor', 'all'); // $floors = sewing floor

        return view('sewingdroplets::reports.floor_line_wise_report', [
            'floor_line_wise_report' => $floor_line_wise_report ?? null,
            'floors' => $floors,
            'floor_id' => $floorId,
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ]);
    }

    public function floorLineWiseSewingReportData($floorId, $fromDate, $toDate)
    {
        return FinishingProductionReport::with([
            'buyer:id,name',
            'order:id,style_name',
            'color:id,name'
        ])->select(
            'buyer_id',
            'order_id',
            'color_id',
            'production_date',
            'sewing_input',
            'sewing_output',
            'floor_id',
            'line_id'
        )->when($floorId != 'all', function ($query) use ($floorId) {
            $query->where('floor_id', $floorId);
        })
            ->where(function ($query) {
                return $query->where('sewing_output', '>', 0)
                    ->orWhere('sewing_input', '>', 0);
            })
            ->whereDate('production_date', '>=', $fromDate)
            ->whereDate('production_date', '<=', $toDate)
            ->orderBy('line_id')
            ->get();
    }

    public function floorLineWiseSewingReportDownload(Request $request)
    {
        if (request('floor_id') && request('from_date') && request('to_date')) {
            $data['floor_line_wise_report'] = $this->floorLineWiseSewingReportData(request('floor_id'), request('from_date'), request('to_date'));
            $data['type'] = request('type');
            $data['from_date'] = request('from_date');
            $data['to_date'] = request('to_date');
            if (request('type') == 'pdf') {

                /*$pdf = \PDF::loadView('sewingdroplets::reports.downloads.pdf.floor_line_wise_sewing_report_download', $data, [], [
                    'format' => 'A4-L'
                ]);*/

                $pdf = \PDF::loadView('sewingdroplets::reports.downloads.pdf.floor_line_wise_sewing_report_download', $data, [
                    'mode' => 'utf-8', 'format' => [233, 500]
                ]);
                return $pdf->download('line-wise-sewing-output-report.pdf');

                //return $pdf->download('line-wise-sewing-output-report.pdf');

            } else {
                return \Excel::download(new LineWiseSewingReportExport($data), 'floor-line-wise-sewing-output-report.xlsx');
            }
        } else {
            return redirect()->back();
        }
    }

    /*public function getLineWiseOutput(Request $request)
    {
        $lines = [];
        $lineReport = [];
        if ($request->isMethod('POST')) {
            $request->validate([
                'floor_id' => 'required',
                'line_id' => 'required',
            ]);
            $lineReport = $this->lineWiseIO($request->line_id);
        } elseif ($request->line_id) {
            $lineReport = $this->lineWiseIO($request->line_id);
        }
        $floors = Floor::pluck('floor_no', 'id')->all();
        if ($request->floor_id != NULL) {
            $lines = Line::where('floor_id', $request->floor_id)->pluck('line_no', 'id')->all();
        }

        return view('sewingdroplets::reports.line_wise_report', [
            'lineReport' => $lineReport,
            'floors' => $floors,
            'lines' => $lines,
            'floor_id' => $request->floor_id,
            'line_id' => $request->line_id
        ]);
    }*/

    /*public function lineWiseIO($line_id)
    {
        $query = CuttingInventoryChallan::withoutGlobalScope('factoryId')
            ->with(['buyer', 'order', 'purchaseOrder', 'line.floor'])
            ->whereNotNull('cutting_inventory_challans.line_id')
            ->whereNull('cutting_inventory_challans.deleted_at')
            ->whereRaw('cutting_inventory_challans.line_id  = '.$line_id)
            ->join('lines', 'lines.id', 'cutting_inventory_challans.line_id')
            ->join('cutting_inventories', 'cutting_inventories.challan_no', 'cutting_inventory_challans.challan_no')
            ->join('bundle_cards', 'bundle_cards.id', 'cutting_inventories.bundle_card_id')
            ->leftJoin('sewingoutputs', 'sewingoutputs.bundle_card_id', 'cutting_inventories.bundle_card_id')
            //->orderBy('lines.floor_id')
            ->orderBy('lines.sort', 'ASC')
            ->select(
                'lines.sort as sort',
                'lines.floor_id as floor_id',
                'cutting_inventory_challans.line_id as line_id',
                'bundle_cards.buyer_id as buyer_id',
                'bundle_cards.purchase_order_id as purchase_order_id',
                \DB::raw('SUM(if(date(cutting_inventory_challans.input_date) = curdate(), bundle_cards.quantity - bundle_cards.total_rejection - bundle_cards.print_rejection, 0)) as todays_input'),
                \DB::raw('SUM(bundle_cards.quantity - bundle_cards.total_rejection - bundle_cards.print_rejection) as total_input'),
                \DB::raw('SUM(if(DATE_FORMAT(sewingoutputs.created_at, "%Y-%m-%d") = curdate(), bundle_cards.quantity - bundle_cards.total_rejection - bundle_cards.print_rejection - bundle_cards.sewing_rejection, 0)) as todays_output'),
                \DB::raw('SUM(bundle_cards.quantity - bundle_cards.total_rejection - bundle_cards.print_rejection - bundle_cards.sewing_rejection) as total_output'),
                \DB::raw('SUM(bundle_cards.total_rejection + bundle_cards.print_rejection + bundle_cards.sewing_rejection) as rejection')
            )
            ->groupBy('line_id')
            ->groupBy('buyer_id')
            ->groupBy('purchase_order_id');

         //   dd($query->toSql());

        $count = \DB::select('select count(*) as total_records from ('.$query->toSql().') as t')[0]->total_records;

        $page = request()->get('page') ?: 1;
        $collection = $query->offset(($page * PAGINATION) - PAGINATION + 1)->limit(PAGINATION)->get();

        return $this->paginateCollection($collection, $count, PAGINATION);
    }

    public function paginateCollection($collection, $count, $perPage, $pageName = 'page', $fragment = null)
    {
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage($pageName);
        parse_str(request()->getQueryString(), $query);
        unset($query[$pageName]);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $collection,
            $count,
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                'query' => $query,
                'fragment' => $fragment
            ]
        );

        return $paginator;
    }*/

    /*public function getLineWiseOutputData()
    {
        $line_wise_report = CuttingInventoryChallan::withoutGlobalScope('factoryId')
            ->join('lines', 'cutting_inventory_challans.line_id', 'lines.id')
            ->join('floors', 'lines.floor_id', 'floors.id')
            ->with(
                'buyer','order','line','cutting_inventory',
                'cutting_inventory.bundlecard')
            ->orderBy('floors.floor_no', 'asc')
            ->orderBy('lines.line_no', 'asc')
            ->limit(180)
            ->get()
            ->groupBy('order_id')
            ->map(function($items){
                $unique_order = $items->first();
                $order_output = $items->map(function($input_challan) {

                    $inputDate = $input_challan->updated_at->toDateString();
                    $today = Carbon::today()->toDateString();

                    $today_output = $input_challan->sewing_ouput->reject(function($sw) use ($today){
                        return ($today != $sw->updated_at->toDateString());
                    })->sum('bundlecard.quantity');

                    $today_input_rejection = $input_challan->sewing_ouput->reject(function($sw) use ($today){
                        return ($today != $sw->updated_at->toDateString());
                    })->sum('bundlecard.total_rejection');

                    $today_sewing_rejection = $input_challan->sewing_ouput->reject(function($sw) use ($today){
                        return ($today != $sw->updated_at->toDateString());
                    })->sum('bundlecard.sewing_rejection');

                    return [
                        'today_input' => ($inputDate == $today) ? $input_challan->cutting_inventory->sum('bundlecard.quantity') : 0,

                        'total_input' => $input_challan->cutting_inventory->sum('bundlecard.quantity'),
                        'today_output' => $today_output,
                        'today_input_rejection' => $today_input_rejection,
                        'today_sewing_rejection' => $today_sewing_rejection,
                        'total_output' => $input_challan->sewing_ouput->sum('bundlecard.quantity'),
                        'total_input_rejection' => $input_challan->cutting_inventory->sum('bundlecard.total_rejection') + $input_challan->cutting_inventory->sum('bundlecard.print_rejection'),
                        'total_rejection' => $input_challan->cutting_inventory->sum('bundlecard.total_rejection') + $input_challan->cutting_inventory->sum('bundlecard.print_rejection') + $input_challan->cutting_inventory->sum('bundlecard.sewing_rejection'),
                    ];
                });

                $reject_ratio = 0;
                if($order_output->sum('total_rejection') > 0 && $order_output->sum('total_output') > 0){
                    $reject_ratio = ($order_output->sum('total_rejection') * 100) / $order_output->sum('total_output');
                }

                $line_wip = $order_output->sum('total_input') - $order_output->sum('total_output') - $order_output->sum('total_rejection');

                $wip = 0;
                if ($line_wip > 0 && $order_output->sum('total_input') > 0){
                    $wip = number_format(($line_wip/$order_output->sum('total_input'))*100,2);
                }

                return [
                    'floor' => $unique_order->line->floor->floor_no ?? 0,
                    'line' => $unique_order->line->line_no ?? '',
                    'buyer' => $unique_order->order->buyer->name ?? '',
                    'style' => $unique_order->order->style->name ?? '',
                    'order' => $unique_order->order->order_no ?? '',
                    'today_input' => $order_output->sum('today_input') - $order_output->sum('today_input_rejection'),
                    'total_input' => $order_output->sum('total_input') - $order_output->sum('total_input_rejection'),
                    'today_output' => $order_output->sum('today_output') - $order_output->sum('today_sewing_rejection'),
                    'total_output' => $order_output->sum('total_output') - $order_output->sum('total_rejection'),
                    'rejection' => $order_output->sum('total_rejection'),
                    'reject_ratio' => number_format($reject_ratio, 2),
                    'line_wip' => $line_wip,
                    'wip' => $wip,
                ];
            });

        return $line_wise_report;
    }*/

    public function getLineHourlyWiseOutput()
    {
        $floors = $this->getLineHourlyWiseOutputData();
        return view('sewingdroplets::reports.line_wise_hourly_report')
            ->with('floors', $floors);
    }

    // calculate efficiency
    public function getEfficiency($total_output, $smv, $man_power, $effc_hour)
    {
        $total_output_manpower = $total_output * $smv;
        $hourly_output = $man_power * 60 * $effc_hour;
        $le3 = 0;
        if ($total_output_manpower > 0 && $hourly_output > 0) {
            $le3 = number_format($total_output_manpower / $hourly_output, 2);
        }

        return $le3 * 100;
    }

    public function dailySewingForecastReport(Request $request)
    {
        $requestDate = $request->date ?? now()->toDateString();
        $carbonDate = Carbon::parse($requestDate);

        $date = $carbonDate->subDay()->isFriday() ? $carbonDate->subDays(2)->toDateString() : $carbonDate->subDay()->toDateString();

        $floors = Floor::with('lines')->get()->sortBy('sort');
        $sewingOutputs = $this->outputService->sewingOutputsByDate($floors, $date);
        $this->outputService->forecastSewingData($sewingOutputs, $requestDate);

        return view('sewingdroplets::reports.daily_sewing_forecast_report', [
            'sewing_outputs' => $sewingOutputs['sewing_outputs'],
            'floor_total' => $sewingOutputs['floor_total'],
            'date' => $requestDate,
        ]);
    }

    public function dailySewingForecastReportDownload(Request $request)
    {
        if ($request->date && $request->type) {
            $requestDate = $request->date ?? now()->toDateString();
            $carbonDate = Carbon::parse($requestDate);

            $date = $carbonDate->subDay()->isFriday() ? $carbonDate->subDays(2)->toDateString() : $carbonDate->subDay()->toDateString();

            $floors = Floor::with('lines')->get()->sortBy('sort');
            $sewingOutputs = $this->outputService->sewingOutputsByDate($floors, $date);
            $this->outputService->forecastSewingData($sewingOutputs, $requestDate);
            $data = [
                'sewing_outputs' => $sewingOutputs['sewing_outputs'],
                'floor_total' => $sewingOutputs['floor_total'],
                'date' => $requestDate,
            ];
            return \Excel::download(new DailySewingForecastReportExport($data), 'daily_sewing_forecast_report_'. date('d_m_Y', strtotime($requestDate)) .'.xlsx');

        } else {
            return redirect()->back();
        }
    }

    public function getDateWiseLineHourlyWiseOutput(Request $request)
    {
        $date = request()->get('date');
        $date = $date ? $date : Carbon::today()->toDateString();

        $floors = Floor::with('lines')->get()->sortBy('sort');
        $sewingOutputs = $this->outputService->sewingOutputsByDate($floors, $date);
        return view('sewingdroplets::reports.date_wise_hourly_report', [
            'sewing_outputs' => $sewingOutputs['sewing_outputs'],
            'floor_total' => $sewingOutputs['floor_total'],
            'date' => $date,
        ]);
    }

    public function getDateWiseLineHourlyWiseOutputReportDownload()
    {
        $floors = Floor::with('lines')->get()->sortBy('sort');
        $sewingOutputs = $this->outputService->sewingOutputsByDate($floors, request('date'));

        $data['sewing_outputs'] = $sewingOutputs['sewing_outputs'];
        $data['floor_total'] = $sewingOutputs['floor_total'];
        $data['date'] = request('date');
        $data['type'] = request('type');

        if (request('type') == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('sewingdroplets::reports.downloads.pdf.line_wise_hourly_sewing_output_report_download', $data)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('line-wise-hourly-sewing-output-report.pdf');
        } else {
            return \Excel::download(new LineWiseHourlySewingReportExport($data), 'line-wise-hourly-sewing-output-report.xlsx');
        }
    }

    public function productionDashboard()
    {
        $date = Carbon::today()->toDateString();
        //$floors = Floor::with('lines')->get()->sortBy('sort');
        $query = Floor::query();
        $query->when(request('factory_id') != null, function ($q) {
            return $q->where('factory_id', request('factory_id'))->orderBy('sort', 'asc');
        });
        $query->when(factoryId() != null, function ($q) {
            return $q->where('factory_id', factoryId());
        });
        $floors = $query->with('lines:id,line_no,floor_id')->get()->sortBy('sort');

        $sewingOutputs = $this->outputService->sewingOutputsByDate($floors, $date);

        $total_sewing_target = 0;
        $total_sewing_production = 0;
        $total_available_minutes = 0;
        $total_earned_minutes = 0;

        foreach ($sewingOutputs['floor_total'] as $floorNo => $floor) {
            $data['achievment'][$floorNo] = $floor['achievment'];
            $data['floorEfficiency'][$floorNo] = $floor['floor_efficiency'];
            $data['output'][$floorNo] = [
                'output' => $floor['total_output'],
                'target' => $floor['targeted_output'],
            ];
            //$data['productionEfficiency'][$floorNo] = $floor['floor_production_efficiency'];
            $total_sewing_target += $floor['targeted_output'];
            $total_sewing_production += $floor['total_output'];
            $total_available_minutes += $floor['total_production_minutes'];
            $total_earned_minutes += $floor['total_used_minutes'];
        }

        // only for auto email email
        $total_production_data = [
            'total_sewing_target' => (int)$total_sewing_target,
            'total_sewing_production' => $total_sewing_production,
            'total_available_minutes' => $total_available_minutes,
            'total_earned_minutes' => $total_earned_minutes,
            'total_efficiency' => ($total_earned_minutes > 0) ? number_format($total_available_minutes * 100 / $total_earned_minutes, 2) : 0
        ];
        Session::put('total_production_data', $total_production_data);
        // end auto email area

        $floorNo = request()->get('floor_no');
        if (array_key_exists($floorNo, $sewingOutputs['sewing_outputs'])) {
            $sewingOutputsByLine = $sewingOutputs['sewing_outputs'][$floorNo];

            foreach ($sewingOutputsByLine as $lineNo => $sewingOutput) {
                $data['lineTarget'][$floorNo][$lineNo] = [
                    'output' => $sewingOutput['total_output'],
                    'target' => $sewingOutput['targeted_output'],
                    'efficiency' => $sewingOutput['line_efficiency'] ?? 0
                ];
            }
            $data['floorLinesHourlyData'][$floorNo] = $sewingOutputs['sewing_outputs'][$floorNo];
        } else {
            foreach ($sewingOutputs['sewing_outputs'] as $floorNo => $sewingOutputsByLine) {
                foreach ($sewingOutputsByLine as $lineNo => $sewingOutput) {
                    $data['lineTarget'][$floorNo][$lineNo] = [
                        'output' => $sewingOutput['total_output'],
                        'target' => $sewingOutput['targeted_output'],
                        'efficiency' => $sewingOutput['line_efficiency'] ?? 0
                    ];
                }
            }
            $data['floorLinesHourlyData'] = $sewingOutputs['sewing_outputs'];
        }

        foreach ($floors as $floor) {
            $lastDaySummary = $floor->sewingSummaryForLastProductiveDay();
            $floor->last_day_ouptut = $lastDaySummary['output'];
            $floor->last_day_target = $lastDaySummary['target'];
        }

        return view('sewingdroplets::pages.production_dashboard', [
            'data' => $data,
            'floors' => $floors
        ]);
    }

    public function productionBoard()
    {
        $date = request()->get('date');
        $date = $date ? $date : Carbon::today()->toDateString();

        $floors = Floor::with('lines')->get()->sortBy('sort');
        $sewingOutputs = $this->outputService->sewingOutputsProductionBoardByDate($floors, $date);

        $weeklyInspectionData = $this->weeklyInspectionSchedule();

        return view('sewingdroplets::reports.production_board_report_new', [
            'sewing_outputs' => $sewingOutputs['sewing_outputs'],
            'floor_total' => $sewingOutputs['floor_total'],
            'weeklyInspectionData' => $weeklyInspectionData,
            'date' => $date,
        ]);
    }

    public function weeklyInspectionSchedule()
    {
        return InspectionSchedule::with('style')
            //->whereBetween('inspection_date', [date("Y-m-d"), date("Y-m-d", strtotime("+1 week"))])
            ->where('status', 0) // 0 status for running
            ->orderBy('inspection_date', 'asc')
            ->get();
    }
}
