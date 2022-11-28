<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class SewingInputDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $factory_ids = Factory::query()->pluck('id');
        $date = $request->date ?? date('Y-m-d');
        $factory_id = $request->factory_id ?? null;
        $report_query = FinishingProductionReport::query()
            ->withoutGlobalScope('factoryId')
            ->when($factory_id, function ($query) use ($factory_id) {
                $query->where('factory_id', $factory_id);
            })
            ->whereDate('production_date', $date)
            ->orderBy('floor_id')
            ->get()
            ->filter(function ($item, $key) {
                return $item->sewing_input > 0;
            });
        Line::$targetDate = $date;
        $lines = Line::query()
            ->withoutGlobalScope('factoryId')
            ->with('floorWithoutGlobalScopes', 'sewingTargetsByDateWithoutGlobalScopes')
            ->select('id', 'line_no', 'factory_id', 'floor_id', 'sort')
            ->when($factory_id, function ($query) use ($factory_id) {
                $query->where('factory_id', $factory_id);
            })
            ->whereIn('factory_id', $factory_ids->toArray())
            ->get();
        $reports = [];
        foreach ($lines->groupBy('factory_id') as $lineByFactory) {
            foreach ($lineByFactory->sortBy('id') as $line) {
                $order_ids = '';
                $color_ids = '';
                $wip = '';
                $style_name = '';
                $input_line = 0;
                $output_line = 0;
                $sewing_line_target = null;
                if ($report_query->where('line_id', $line->id)->count()) {
                    $order_ids = $report_query->where('line_id', $line->id)->unique('order_id')->pluck('order_id')->toArray();
                    $color_ids = $report_query->where('line_id', $line->id)->unique('color_id')->pluck('color_id')->toArray();
                    $wip = FinishingProductionReport::getTotalLineWip($line->id, $order_ids, $color_ids);
                    $style_name = $report_query->where('line_id', $line->id)->unique('order_id')->pluck('order.style_name')->implode(',');
                    $input_line = $report_query->where('line_id', $line->id)->sum('sewing_input');
                    $output_line = $report_query->where('line_id', $line->id)->sum('sewing_output');
                }
                $sewing_line_targets = $line->sewingTargetsByDateWithoutGlobalScopes;
                $target = 0;
                $input_target = 0;
                if ($sewing_line_targets) {
                    foreach ($sewing_line_targets as $sewing_line_target) {
                        $target += $sewing_line_target->target * $sewing_line_target->wh;
                        $input_target += $sewing_line_target->input_plan;
                    }
                }

                $reports[] = [
                    'floor_id' => $line->floor_id,
                    'floor_no' => $line->floorWithoutGlobalScopes->floor_no,
                    'line_id' => $line->id,
                    'line_no' => $line->line_no,
                    'factory_id' => $line->factory_id,
                    'factory_name' => $line->factory->factory_name,
                    'order_ids' => $order_ids,
                    'color_ids' => $color_ids,
                    'style_name' => $style_name,
                    'input_target' => $input_target ?? 0,
                    'sewing_target' => $target ?? 0,
                    'today_input_qty' => $input_line,
                    'today_output_qty' => $output_line,
                    'wip' => $wip,
                ];
            }
        }
        return view('inputdroplets::reports.sewing_input_dashboard', [
                'reports' => collect($reports),
                'date' => $date,
                'factory_ids' => $factory_ids
            ]
        );
    }
}
