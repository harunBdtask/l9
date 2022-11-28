<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use SkylarkSoft\GoRMG\Iedroplets\Export\SewingLineTargetExcel;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use Symfony\Component\HttpFoundation\Response;

class SewingLineTargetController extends Controller
{

    public function index()
    {
        $floors = Floor::orderBy('sort')
            ->pluck('floor_no', 'id')
            ->all();

        return view(PackageConst::PACKAGE_NAME . '::forms.sewing_line_wise_target')
            ->with('floors', $floors);
    }

    public function getLineTargetForm($floor_id, $target_date): \Illuminate\Http\JsonResponse
    {
        if ($floor_id && $target_date) {
            $attempt = 0;
            $maxAttempt = 15;
            $targetDate = Carbon::today();
            $linesWiseTargets = 0;

            while ($attempt < $maxAttempt) {
                $linesWiseTargets = SewingLineTarget::withoutGlobalScope('factoryId')
                    ->join('lines', 'sewing_line_targets.line_id', 'lines.id')
                    ->where('sewing_line_targets.floor_id', $floor_id)
                    ->where('sewing_line_targets.target_date', $targetDate->toDateString())
                    ->where('sewing_line_targets.factory_id', factoryId())
                    ->orderBy('lines.sort', 'asc')
                    ->orderBy('sewing_line_targets.id', 'asc')
                    ->select(['sewing_line_targets.*', 'lines.*'])
                    ->get();

                if ($linesWiseTargets->count() > 0) {
                    break;
                }

                $targetDate = $targetDate->subDay();
                $attempt++;
            }

            if (!$linesWiseTargets->count()) {
                $lines = Line::where('floor_id', $floor_id)
                    ->orderBy('sort')
                    ->get();
            }
        }

        $buyers = Buyer::pluck('name', 'id')->all();
        $lines = $lines ?? null;
        $linesWiseTargets = $linesWiseTargets ?? null;

        $view = view(PackageConst::PACKAGE_NAME . '::forms.line_target_form',
            compact(['linesWiseTargets', 'lines', 'buyers'])
        )->render();

        return response()->json(['view' => $view]);
    }

    public function lineTargetAction(Request $request): \Illuminate\Http\RedirectResponse
    {
        $target_date = $request->get('target_date');
        $line_ids = $request->get('line_id');
        $floor_ids = $request->get('floor_id');
        $operators = $request->get('operator');
        $helpers = $request->get('helper');
        $targets = $request->get('target');
        $whs = $request->get('wh');
        $input_plans = $request->get('input_plan');
        $remarks = $request->get('remarks');

        if ($target_date != date('Y-m-d')) {
            Session::flash('error', "Only today's target allowed to update");
            return redirect()->back();
        }

        $lineWiseTargetInput = [];
        $no_of_rows = count($line_ids);

        if ($no_of_rows > 0) {
            $dateTime = Carbon::now();
            for ($i = 0; $i < $no_of_rows; $i++) {
                $lineWiseTargetInput[] = [
                    'floor_id' => $floor_ids,
                    'line_id' => $line_ids[$i],
                    'target_date' => $target_date,
                    'operator' => $operators[$i] ?? 0,
                    'helper' => $helpers[$i] ?? 0,
                    'target' => $targets[$i] ?? 0,
                    'wh' => $whs[$i] ?? 0,
                    'input_plan' => $input_plans[$i] ?? 0,
                    'remarks' => $remarks[$i] ?? '',
                    'factory_id' => factoryId(),
                    'created_at' => $dateTime,
                    'updated_at' => $dateTime
                ];
            }
        }

        try {
            DB::transaction(function () use ($lineWiseTargetInput, $floor_ids, $target_date) {
                // delete previous target
                SewingLineTarget::where([
                    'floor_id' => $floor_ids,
                    'target_date' => $target_date
                ])->forceDelete();
                // insert new data
                SewingLineTarget::insert($lineWiseTargetInput);
                Session::flash('success', S_SAVE_MSG);
            });
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function addlineToTodaysSewingTarget(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'id' => 'required'
        ], [
            'required' => 'Line is required'
        ]);

        try {
            DB::beginTransaction();

            $line_id = $request->get('id');

            $line = Line::query()->findOrFail($line_id);
            $date = now()->toDateString();

            $targetQuery = SewingLineTarget::query()->whereDate('target_date', $date);
            $targetQueryClone = clone $targetQuery;

            if ($targetQuery->count() > 0 && $targetQueryClone->where('line_id', $line_id)->count() <= 0) {
                $sewingLineTarget = new SewingLineTarget();
                $sewingLineTarget->fill([
                    'floor_id' => $line->floor_id,
                    'line_id' => $line_id,
                    'target_date' => $date,
                ]);
                $sewingLineTarget->save();
            }

            DB::commit();

            $response = [
                'status' => Response::HTTP_OK,
                'message' => SUCCESS_MSG,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => SOMETHING_WENT_WRONG,
                'error' => $e->getMessage(),
            ];
        } finally {
            return response()->json($response, $response['status']);
        }
    }

    public function getFloorWiseNpt()
    {
        $floors = Floor::pluck('floor_no', 'id')->all();
        return view(PackageConst::PACKAGE_NAME . '::forms.line-wise-npt')->with('floors', $floors);
    }

    public function getLineWiseNptUpdateForm($floor_id): \Illuminate\Http\JsonResponse
    {
        $lines = Line::where('floor_id', $floor_id)->with('sewingLineTarget')->get();

        $view = view(PackageConst::PACKAGE_NAME . '::forms.line-wise-npt-update-form',
            compact('lines')
        )->render();

        return response()->json(['view' => $view]);
    }

    public function lineWiseNptUpdateAction(Request $request): \Illuminate\Http\RedirectResponse
    {
        $ids = $request->get('id');
        $add_man_mins = $request->get('add_man_min');
        $sub_man_mins = $request->get('sub_man_min');
        $mbs = $request->get('mb');
        $shading_problems = $request->get('shading_problem');
        $late_decisions = $request->get('late_decision');
        $cutting_problems = $request->get('cutting_problem');
        $input_problems = $request->get('input_problem');
        $late_to_get_mcs = $request->get('late_to_get_mc');
        $print_mistakes = $request->get('print_mistake');

        $no_of_rows = count($ids);


        try {
            DB::beginTransaction();

            if ($no_of_rows > 0) {

                for ($i = 0; $i <= $no_of_rows - 1; $i++) {
                    $input = [
                        'add_man_min' => $add_man_mins[$i] ?? 0,
                        'sub_man_min' => $sub_man_mins[$i] ?? 0,
                        'mb' => $mbs[$i] ?? 0,
                        'shading_problem' => $shading_problems[$i] ?? 0,
                        'late_decision' => $late_decisions[$i] ?? 0,
                        'cutting_problem' => $cutting_problems[$i] ?? 0,
                        'input_problem' => $input_problems[$i] ?? 0,
                        'late_to_get_mc' => $late_to_get_mcs[$i] ?? 0,
                        'print_mistake' => $print_mistakes[$i] ?? 0,
                        'target_date' => date('Y-m-d'),
                        'line_id' => $ids[$i],
                    ];

                    SewingLineTarget::updateOrCreate([
                        'target_date' => $input['target_date'],
                        'line_id' => $input['line_id']
                    ], $input);
                }
            }

            DB::commit();
            Session::flash('success', S_UPDATE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function sewingLineTargetDownload($type, $target_date)
    {
        $previous_day = Carbon::parse($target_date)->subDays(1)->toDateString();

        $report = Line::with(array('sewingLineTarget' => function ($query) use ($target_date) {
            $query->select()->where('target_date', $target_date);
        }))->orderBy('floor_id', 'desc')->get();

        $sewing_line_targets = SewingLineTarget::where('target_date', $target_date)
            ->get();

        $sewing_line_target_previous_day = SewingLineTarget::where('target_date', $previous_day)
            ->get();

        $buyer_line_reports = $sewing_line_targets
            ->whereNotNull('buyer_id');

        $notes['plan_line'] = $sewing_line_targets
            ->whereNotNull('order_id')
            ->unique('line_id')
            ->count();

        $notes['running_line'] = Line::withoutGlobalScope('factoryId')
            ->where('factory_id', factoryId())
            ->count('id');

        $todays_working_hour_sewing['8 Hours'] = $sewing_line_targets->where('wh', 8)->unique('line_id')->count();
        $todays_working_hour_sewing['9 Hours'] = $sewing_line_targets->where('wh', 9)->unique('line_id')->count();
        $todays_working_hour_sewing['10 Hours'] = $sewing_line_targets->where('wh', 10)->unique('line_id')->count();
        $todays_working_hour_sewing['11 Hours'] = $sewing_line_targets->where('wh', 11)->unique('line_id')->count();
        $todays_working_hour_sewing['13 Hours'] = $sewing_line_targets->where('wh', 13)->unique('line_id')->count();

        $yesterdays_working_hour_sewing['8 Hours'] = $sewing_line_target_previous_day->where('wh', 8)->unique('line_id')->count();
        $yesterdays_working_hour_sewing['9 Hours'] = $sewing_line_target_previous_day->where('wh', 9)->unique('line_id')->count();
        $yesterdays_working_hour_sewing['10 Hours'] = $sewing_line_target_previous_day->where('wh', 10)->unique('line_id')->count();
        $yesterdays_working_hour_sewing['11 Hours'] = $sewing_line_target_previous_day->where('wh', 11)->unique('line_id')->count();
        $yesterdays_working_hour_sewing['13 Hours'] = $sewing_line_target_previous_day->where('wh', 13)->unique('line_id')->count();

        $previous_days_production['sewing_target'] = $sewing_line_target_previous_day->map(function ($item) {
            return $item['target'] * $item['wh'];
        })->sum();

        $previous_days_production['sewing_achieved'] = 0;

        $floors = Floor::all();

        foreach ($floors as $floor) {
            $lastDaySummary = $floor->sewingOutputForLastProductiveDay($previous_day);
            $previous_days_production['sewing_achieved'] += $lastDaySummary['output'];
        }

        if ($type == 'pdf') {
            $pdf = \PDF::loadView(PackageConst::PACKAGE_NAME . '::reports.downloads.pdf.sewing-line-target-download',
                compact([
                    'report', 'buyer_line_reports', 'target_date',
                    'notes', 'todays_working_hour_sewing',
                    'yesterdays_working_hour_sewing', 'previous_days_production'
                ]))->setPaper('a4', 'landscape');

            return $pdf->download('sewing-line-target.pdf');
        }

        $todays_summary = [];
        $previous_summaries = [];
        $buyer_line_run_key = [];
        $buyer_line_run_value = [];

        $todays_sewing_working_hour_key = [];
        $todays_sewing_working_hour_value = [];

        $total_in_notes_key = [
            'Total Plan Line',
            'Total Running Line',
            'Total Close Line',
            'Total New Input',
            'Total Style Close',
            'Total Fabric',
            'Total Cutting',
            'Total Print/Embr.'
        ];
        $total_in_notes_value = [$notes['plan_line'], $notes['running_line']];

        foreach ($buyer_line_reports->groupBy('buyer_id') as $groupByBuyer) {
            $buyer_line_run_key[] = $groupByBuyer->first()->buyer->name;
            $buyer_line_run_value[] = $groupByBuyer->groupBy('line_id')->count() ?? 0;
        }

        foreach ($todays_working_hour_sewing as $key => $value) {
            $todays_sewing_working_hour_key[] = $key;
            $todays_sewing_working_hour_value[] = $value;
        }
        $todays_sewing_working_hour_key[] = 'Total';
        $todays_sewing_working_hour_value[] = array_sum($todays_sewing_working_hour_value);

        $todays_production_summary_array_count = [
            'total_in_notes_key' => count($total_in_notes_key),
            'buyer_line_run_key' => count($buyer_line_run_key),
            'todays_sewing_working_hour_key' => count($todays_sewing_working_hour_key),
        ];

        $todays_production_summary_array_to_loop = array_search(
            max($todays_production_summary_array_count),
            $todays_production_summary_array_count
        );

        foreach ($$todays_production_summary_array_to_loop as $loop_key => $loop_item) {
            $data = [];
            $data['notes_key'] = $total_in_notes_key[$loop_key] ?? '';
            $data['notes_value'] = $total_in_notes_value[$loop_key] ?? '';
            $data['buyer_line_run_key'] = $buyer_line_run_key[$loop_key] ?? '';
            $data['buyer_line_run_value'] = $buyer_line_run_value[$loop_key] ?? '';
            $data['todays_sewing_working_hour_key'] = $todays_sewing_working_hour_key[$loop_key] ?? '';
            $data['todays_sewing_working_hour_value'] = $todays_sewing_working_hour_value[$loop_key] ?? '';

            $todays_summary[] = $data;
        }

        $previous_production_status_key = ['Sewing', 'QC Pass', 'Finishing'];
        $previous_production_status_value = [
            [
                $previous_days_production['sewing_target'],
                $previous_days_production['sewing_achieved'],
                $previous_days_production['sewing_target'] > 0
                    ? number_format(($previous_days_production['sewing_achieved'] * 100)
                        / $previous_days_production['sewing_target'], 2) . '%'
                    : 0 . '%'
            ]
        ];

        $previous_days_sewing_working_hour_key = [];
        $previous_days_sewing_working_hour_value = [];

        foreach ($yesterdays_working_hour_sewing as $key => $value) {
            $previous_days_sewing_working_hour_key[] = $key;
            $previous_days_sewing_working_hour_value[] = $value;
        }
        $previous_days_sewing_working_hour_key[] = 'Total';
        $previous_days_sewing_working_hour_value[] = array_sum($previous_days_sewing_working_hour_value);

        foreach ($previous_days_sewing_working_hour_key as $key => $value) {
            $data = [];

            $data['previous_production_status_key'] = $previous_production_status_key[$key] ?? '';
            $data['previous_production_target'] = $previous_production_status_value[$key][0] ?? '';
            $data['previous_production_achieved'] = $previous_production_status_value[$key][1] ?? '';
            $data['previous_production_achieved_percent'] = $previous_production_status_value[$key][2] ?? '';
            $data['time_key'] = $value ?? '';
            $data['time_value'] = $previous_days_sewing_working_hour_value[$key] ?? '';

            $previous_summaries[] = $data;
        }
        $number_of_rows = count($report->groupBy('floor_id'));

        foreach ($report as $report_single) {
            $number_of_rows += $report_single->sewingLineTarget->count();
        }
        $number_of_rows_total = ($number_of_rows == count($report->groupBy('floor_id'))) ? (count($report) + $number_of_rows) : $number_of_rows;
        $total_number_of_rows['upper_section'] = $number_of_rows_total + 2;
        $total_number_of_rows['middle_section'] = max($todays_production_summary_array_count);

        return \Excel::download(new SewingLineTargetExcel(compact([
            'report', 'buyer_line_reports', 'target_date', 'notes', 'todays_working_hour_sewing',
            'yesterdays_working_hour_sewing', 'previous_days_production', 'todays_summary',
            'previous_summaries', 'total_number_of_rows'
        ])), 'sewing-line-target.xlsx');
    }
}
