<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Subcontract\DTO\SubDyeingFinishingProductionDailyDTO;
use SkylarkSoft\GoRMG\Subcontract\Exports\DailyFinishingProductionReportExport;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Services\Reports\SubDyeingFinishingProductionDailyService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SubDyeingFinishingProductionDailyReportController extends Controller
{
    public function index()
    {
        return view(PackageConst::VIEW_PATH . 'report.finishing-production.daily.index');
    }

    public function generate(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $dto = (new SubDyeingFinishingProductionDailyDTO())->setDateRange($fromDate, $toDate);
        $finishingProductionReport = SubDyeingFinishingProductionDailyService::init($dto)->getReportData();

        return view(PackageConst::VIEW_PATH . 'report.finishing-production.daily.table', [
            'reportData' => $finishingProductionReport,
            'slittingsMachine' => $dto->pluckSlittingsMachine(),
            'stenteringsMachine' => $dto->pluckStenteringsMachine(),
            'subCompactorsMachine' => $dto->pluckSubCompactorsMachine(),
            'tubeCompactingsMachine' => $dto->pluckSubTubeCompactingsMachine(),
            'subDryersMachine' => $dto->pluckSubDryersMachine(),
            'subSqueezersMachine' => $dto->pluckSubSqueezersMachine(),
            'subPeachsMachine' => $dto->pluckSubPeachsMachine(),
            'subBrushesMachine' => $dto->pluckSubBrushesMachine(),
            'subHtSetsMachine' => $dto->pluckSubDyeingHtSetMachine(),
        ]);
    }

    public function generatePdf(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $dto = (new SubDyeingFinishingProductionDailyDTO())->setDateRange($fromDate, $toDate);
        $finishingProductionReport = SubDyeingFinishingProductionDailyService::init($dto)->getReportData();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(
                PackageConst::VIEW_PATH . 'report.finishing-production.daily.pdf',
                [
                    'reportData' => $finishingProductionReport,
                    'slittingsMachine' => $dto->pluckSlittingsMachine(),
                    'stenteringsMachine' => $dto->pluckStenteringsMachine(),
                    'subCompactorsMachine' => $dto->pluckSubCompactorsMachine(),
                    'tubeCompactingsMachine' => $dto->pluckSubTubeCompactingsMachine(),
                    'subDryersMachine' => $dto->pluckSubDryersMachine(),
                    'subSqueezersMachine' => $dto->pluckSubSqueezersMachine(),
                    'subPeachsMachine' => $dto->pluckSubPeachsMachine(),
                    'subBrushesMachine' => $dto->pluckSubBrushesMachine(),
                    'subHtSetsMachine' => $dto->pluckSubDyeingHtSetMachine(),
                ]
            )
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('daily_finishing_production.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function generateExcel(Request $request): BinaryFileResponse
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $dto = (new SubDyeingFinishingProductionDailyDTO())->setDateRange($fromDate, $toDate);
        $finishingProductionReport = SubDyeingFinishingProductionDailyService::init($dto)->getReportData();

        return Excel::download(new DailyFinishingProductionReportExport([
            'reportData' => $finishingProductionReport,
            'slittingsMachine' => $dto->pluckSlittingsMachine(),
            'stenteringsMachine' => $dto->pluckStenteringsMachine(),
            'subCompactorsMachine' => $dto->pluckSubCompactorsMachine(),
            'tubeCompactingsMachine' => $dto->pluckSubTubeCompactingsMachine(),
            'subDryersMachine' => $dto->pluckSubDryersMachine(),
            'subSqueezersMachine' => $dto->pluckSubSqueezersMachine(),
            'subPeachsMachine' => $dto->pluckSubPeachsMachine(),
            'subBrushesMachine' => $dto->pluckSubBrushesMachine(),
            'subHtSetsMachine' => $dto->pluckSubDyeingHtSetMachine(),
        ]), 'daily_finishing_production.xlsx');
    }
}
