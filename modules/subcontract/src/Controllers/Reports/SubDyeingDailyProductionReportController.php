<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Subcontract\Exports\DailyDyeingProductionExcel;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProduction;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SubDyeingDailyProductionReportController extends Controller
{
    public function index()
    {
        return view(PackageConst::VIEW_PATH . 'report.dyeing-production.daily.index');
    }

    public function getReport(Request $request)
    {
        $reportData = $this->generateReport($request);

        return view(PackageConst::VIEW_PATH . 'report.dyeing-production.daily.table', $reportData);
    }

    /**
     * @return mixed
     */
    public function generatePdf(Request $request)
    {
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(PackageConst::VIEW_PATH . 'report.dyeing-production.daily.pdf', $this->generateReport($request))
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('daily_dyeing_production.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function generateExcel(Request $request): BinaryFileResponse
    {
        return Excel::download(new DailyDyeingProductionExcel($this->generateReport($request)), 'daily_dyeing_production.xlsx');
    }

    /**
     * @param Request $request
     * @return array
     */
    private function generateReport(Request $request): array
    {
        $fromDate = $request->get('from_date', Carbon::now()->format('Y-m-d'));
        $toDate = $request->get('to_date', Carbon::now()->format('Y-m-d'));
        $fromMonth = Carbon::make($request->get('from_date'))->month;
        $toMonth = Carbon::make($request->get('to_date'))->month;
        $fromYear = Carbon::make($request->get('from_date'))->year;
        $toYear = Carbon::make($request->get('to_date'))->year;

        $productions = SubDyeingProduction::query()
            ->with([
                'subDyeingProductionDetails.color',
                'subDyeingBatch.machineAllocations',
                'supplier',
            ])
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                $query->whereBetween('production_date', [$fromDate, $toDate]);
            })
            ->orderBy('production_date', 'desc')
            ->get();

        $totalProductions = SubDyeingProduction::query()
            ->withSum('subDyeingProductionDetails as total_production_qty', 'dyeing_production_qty')
            ->when($fromMonth && $toMonth, function (Builder $query) use ($fromMonth, $toMonth) {
                $query->whereMonth('production_date', '>=', $fromMonth)
                    ->whereMonth('production_date', '<=', $toMonth);
            })
            ->when($fromYear && $toYear, function (Builder $query) use ($fromYear, $toYear) {
                $query->whereYear('production_date', '>=', $fromYear)
                    ->whereYear('production_date', '<=', $toYear);
            })
            ->get();

        return [
            'productions' => $productions,
            'totalProductions' => $totalProductions,
            'toDate' => $toDate,
        ];
    }
}
