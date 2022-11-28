<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers\V2;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class SewingLineTargetController extends Controller
{
    public function index()
    {
        $floors = Floor::orderBy('sort')
            ->pluck('floor_no', 'id')
            ->all();

        return view(PackageConst::PACKAGE_NAME . '::forms.v2.sewing_line_wise_target')
            ->with('floors', $floors);
    }

    public function getLineTargetForm($floor_id, $target_date): \Illuminate\Http\JsonResponse
    {
        if ($floor_id && $target_date) {
            $attempt = 0;
            $maxAttempt = 15;
            $targetDate = Carbon::today();
            $linesWiseTarget = [];

            while ($attempt < $maxAttempt) {
                $linesWiseTarget = SewingLineTarget::withoutGlobalScope('factoryId')
                    ->join('lines', 'sewing_line_targets.line_id', 'lines.id')
                    ->where('sewing_line_targets.floor_id', $floor_id)
                    ->where('sewing_line_targets.target_date', $targetDate->toDateString())
                    ->where('sewing_line_targets.factory_id', factoryId())
                    ->orderBy('lines.sort', 'asc')
                    ->orderBy('sewing_line_targets.id', 'asc')
                    ->select(['sewing_line_targets.*', 'lines.*'])
                    ->get();

                if ($linesWiseTarget->count() > 0) {
                    break;
                }
                $targetDate = $targetDate->subDay();
                $attempt++;
            }

            if (!$linesWiseTarget->count()) {
                $lines = Line::where('floor_id', $floor_id)
                    ->orderBy('sort')
                    ->get();
            }
        }

        $lines = $lines ?? null;
        $linesWiseTarget = $linesWiseTarget ?? null;

        $view = view(PackageConst::PACKAGE_NAME . '::forms.v2.line_target_form',
            compact(['linesWiseTarget', 'lines'])
        )->render();
        return response()->json(['view' => $view]);
    }

    public function lineTargetAction(Request $request): \Illuminate\Http\RedirectResponse
    {
        $target_date = $request->get('target_date');
        $floor_id = $request->get('floor_id');
        $line_ids = $request->get('line_id');
        $operators = $request->get('operator');
        $helpers = $request->get('helper');
        $smvs = $request->get('smv');
        $efficiencies = $request->get('efficiency');
        $targets = $request->get('target');
        $whs = $request->get('wh');
        $input_plans = $request->get('input_plan');
        $remarks = $request->get('remarks');

        if ($target_date != date('Y-m-d')) {
            Session::flash('error', 'Only todays target allowed to update');
            return redirect()->back();
        }

        $lineWiseTargetInput = [];
        $no_of_rows = count($line_ids);

        if ($no_of_rows > 0) {
            $dateTime = Carbon::now();
            for ($i = 0; $i < $no_of_rows; $i++) {
                $lineWiseTargetInput[] = [
                    'floor_id' => $floor_id,
                    'line_id' => $line_ids[$i],
                    'target_date' => $target_date,
                    'operator' => $operators[$i] ?? 0,
                    'helper' => $helpers[$i] ?? 0,
                    'smv' => $smvs[$i] ?? 0,
                    'efficiency' => $efficiencies[$i] ?? 0,
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
            DB::transaction(function () use ($lineWiseTargetInput, $floor_id, $target_date) {
                // delete previous target
                SewingLineTarget::where([
                    'floor_id' => $floor_id,
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
}
