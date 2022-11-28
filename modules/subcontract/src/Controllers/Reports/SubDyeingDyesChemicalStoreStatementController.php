<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Subcontract\Exports\SubDyeingDyesChemicalCostingExport;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Services\Reports\SubDyeingDyesService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SubDyeingDyesChemicalStoreStatementController extends Controller
{
    public function index()
    {
        return view(PackageConst::VIEW_PATH . 'report.dyes-chemical.costing.index');
    }

    public function generateReport(Request $request)
    {
        $fromDate = $request->get('from_date', date('Y-m-d'));
        $toDate = $request->get('to_date', date('Y-m-d'));

        $dyeingProductionDetail = SubDyeingDyesService::setDateRange($fromDate, $toDate)->generateReport();

        return view(PackageConst::VIEW_PATH . 'report.dyes-chemical.costing.body', [
            'dyeingProductionDetails' => $dyeingProductionDetail,
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function generatePdf(Request $request)
    {
        $fromDate = $request->get('from_date', date('Y-m-d'));
        $toDate = $request->get('to_date', date('Y-m-d'));

        $dyeingProductionDetail = SubDyeingDyesService::setDateRange($fromDate, $toDate)->generateReport();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(PackageConst::VIEW_PATH . 'report.dyes-chemical.costing.pdf', [
                'dyeingProductionDetails' => $dyeingProductionDetail,
            ])
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('dyes_chemical_costing_statement.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function generateExcel(Request $request): BinaryFileResponse
    {
        $fromDate = $request->get('from_date', date('Y-m-d'));
        $toDate = $request->get('to_date', date('Y-m-d'));

        $dyeingProductionDetail = SubDyeingDyesService::setDateRange($fromDate, $toDate)->generateReport();

        return Excel::download(new SubDyeingDyesChemicalCostingExport($dyeingProductionDetail), 'dyes_chemical_costing_statement.xlsx');
    }
}
