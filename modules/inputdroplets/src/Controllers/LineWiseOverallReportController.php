<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\LineWiseInputReportExport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use Carbon\Carbon;

class LineWiseOverallReportController extends Controller
{


    public function floorLineInputReport(Request $request)
    {
        $floorId = $request->floor_id ?? 'all';
        $fromDate = $request->from_date ?? Carbon::now()->subDays(45)->toDateString();
        $toDate = $request->to_date ?? Carbon::now()->toDateString();
        $floor_line_wise_report = $this->floorLineWiseInputReportData($floorId, $fromDate, $toDate);
        $floors = Floor::pluck('floor_no', 'id')->prepend('All Floor', 'all'); // $floors = sewing floor

        return view('inputdroplets::reports.floor_line_wise_report', [
            'floor_line_wise_report' => $floor_line_wise_report ?? null,
            'floors' => $floors,
            'floor_id' => $floorId,
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ]);
    }

    public function floorLineWiseInputReportData($floorId, $fromDate, $toDate)
    {
        return FinishingProductionReport::when($floorId != 'all' ,
            function ($query) use($floorId) {
                $query->where('floor_id', $floorId);
            })
            ->whereDate('production_date', '>=', $fromDate)
            ->whereDate('production_date', '<=', $toDate)
            ->orderBy('line_id')
            ->get()
            ->filter(function ($item, $key) {
                return $item->sewing_output > 0 || $item->sewing_input > 0;
            });
    }

    public function floorLineWiseInputReportDownload(Request $request)
    {
        if (request('floor_id') && request('from_date') && request('to_date')) {
            $data['floor_line_wise_report'] = $this->floorLineWiseInputReportData(request('floor_id'), request('from_date'), request('to_date'));
            $data['type'] = request('type');
            $data['from_date'] = request('from_date');
            $data['to_date'] = request('to_date');
            if (request('type') == 'pdf') {

                $pdf = \PDF::loadView('inputdroplets::reports.downloads.pdf.floor_line_wise_input_report_download',$data,[
                    'mode' => 'utf-8', 'format' => [233,500]
                ]);
                return $pdf->download('line-wise-input-inhand-input-report.pdf');

            } else {
                return \Excel::download(new LineWiseInputReportExport($data), 'line-wise-input-inhand-input-report.xlsx');
            }
        } else {
            return redirect()->back();
        }
    }

}
