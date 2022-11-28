<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Excel;
use PDF;
use SkylarkSoft\GoRMG\Knitting\Exports\BuyerStyleReportExcel;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class BuyerStyleReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);
        return view('knitting::reports.buyer-style-report.buyer-style-wise-report', [
            'buyers' => $buyers
        ]);
    }

    public function getStyle(Request $request)
    {
        $buyer_id = $request->buyer;
        $style = PlanningInfo::query()
            ->where('buyer_id', $buyer_id)
            ->groupBy('style_name')
            ->get();
        return response()->json($style);
    }

    public function getReport(Request $request)
    {
        $style = $request->style_id;

        $plannings = $this->format($style);

        return view('knitting::reports.buyer-style-report.view-body', [
            'plannings' => $plannings
        ]);
    }

    public function pdf(Request $request)
    {
        $style = $request->style_id;

        $plannings = $this->format($style);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('knitting::reports.buyer-style-report.buyer-style-pdf', [
                'plannings' => $plannings
            ])
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('buyer-style-report' . '.pdf');
    }

    public function excel(Request $request)
    {
        $style = $request->style_id;

        $plannings = $this->format($style);

        return Excel::download(
            new BuyerStyleReportExcel($plannings),
            'buyer-style-report.xlsx');
    }

    private function format($style)
    {
        $plannings = KnittingProgram::query()
            ->with([
                'planInfo',
                'knitting_program_colors_qtys.yarnAllocationDetail',
            ])
            ->whereHas('planInfo', function (Builder $builder) use ($style) {
                $builder->where('style_name', $style);
            })
            ->get()
            ->flatmap(function ($value, $key) {
                return $value->knitting_program_colors_qtys->flatmap(function ($collection) use ($value) {
                    return $collection->yarnAllocationDetail->map(function ($allocationDetails) use ($value, $collection) {
                        return [
                            'program_no' => $value->program_no,
                            'program_qty' => $value->program_qty,
                            'pi_number' => null,
                            'start_date' => $value->start_date,
                            'end_date' => $value->end_date,
                            'fabric_type' => null,
                            'stitch_length' => $value->stitch_length,
                            'booking_type' => $value->planInfo->booking_type,
                            'fabric_gsm' => $value->planInfo->fabric_gsm,
                            'machine_dia' => $value->machine_dia,
                            'machine_gg' => $value->machine_gg,
                            'machine_feeder' => $value->feeder_text,
                            'finish_dia' => $value->finish_fabric_dia,
                            'color' => $collection->item_color ?? null,
                            'program_color_qty' => $collection->program_qty ?? null,
                            'yarn_description' => $allocationDetails->yarn_description ?? null,
                            'yarn_lot' => $allocationDetails->yarn_lot ?? null,
                            'yarn_allocated_qty' => $allocationDetails->allocated_qty ?? null,
                            'req_qty' => $allocationDetails->previous_total_yarn_requisition_qty ?? null,
                            'remarks' => $value->remarks,
                        ];
                    });
                });
            });
        return $plannings;
    }
}
