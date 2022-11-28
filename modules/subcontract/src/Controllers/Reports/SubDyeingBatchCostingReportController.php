<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\DTO\SubDyeingBatchCostingDTO;
use SkylarkSoft\GoRMG\Subcontract\Exports\SubDyeingBatchCostingReportExport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SubDyeingBatchCostingReportController extends Controller
{
    public function index()
    {
        $batches = SubDyeingBatch::query()->orderByDesc('id')->get(['id', 'batch_no']);

        return view(PackageConst::VIEW_PATH . 'report.batch-costing.index', compact('batches'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function generateReport(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $batch = $request->get('batch');

        $batchCostingDTO = new SubDyeingBatchCostingDTO();
        $batchCostingDTO->setFromDate($fromDate)->setToDate($toDate)->setBatch($batch);

        return view(
            PackageConst::VIEW_PATH . 'report.batch-costing.body',
            [
                'reportData' => $batchCostingDTO->formatReport(),
                'batchDetails' => $batch ? $batchCostingDTO->formatBatchDetails() : [],
            ]
        );
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function generatePdf(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $batch = $request->get('batch');

        $batchCostingDTO = new SubDyeingBatchCostingDTO();
        $batchCostingDTO->setFromDate($fromDate)->setToDate($toDate)->setBatch($batch);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(
                PackageConst::VIEW_PATH . 'report.batch-costing.pdf',
                [
                    'reportData' => $batchCostingDTO->formatReport(),
                    'batchDetails' => $batch ? $batchCostingDTO->formatBatchDetails() : [],
                ]
            )
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('batch_costing_report.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws Exception
     */
    public function generateExcel(Request $request): BinaryFileResponse
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $batch = $request->get('batch');

        $batchCostingDTO = new SubDyeingBatchCostingDTO();
        $batchCostingDTO->setFromDate($fromDate)->setToDate($toDate)->setBatch($batch);

        $reportData = [
            'reportData' => $batchCostingDTO->formatReport(),
            'batchDetails' => $batch ? $batchCostingDTO->formatBatchDetails() : [],
        ];

        return Excel::download(new SubDyeingBatchCostingReportExport($reportData), 'batch_costing_report.xlsx');
    }
}
