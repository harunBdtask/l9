<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Merchandising\Actions\BudgetFilterFormat;
use SkylarkSoft\GoRMG\Merchandising\Exports\BudgetAllExcel;
use SkylarkSoft\GoRMG\Merchandising\Exports\BudgetExcel;
use SkylarkSoft\GoRMG\Merchandising\Exports\CostSheetExcel;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\CostingSheetService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class BudgetDownloadAbleController extends Controller
{

    public function view($id)
    {
        $data = BudgetReportService::getBudgetById($id)->budgetData();
        $data['image'] = ($data['image'] && file_exists(storage_path('app/public/' . $data['image'])))
            ? asset('storage/' . $data['image'])
            : null;
        $signature = ReportSignatureService::getApprovalSignature(Budget::class, $id);
        $createdAt = Budget::find($id, ['created_at'])->created_at;
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        return view('merchandising::budget.reports.view', $data, [
            'signature' => $signature,
            'date_time' => $dateTime,
        ]);
    }

    public function budgetPrint($id)
    {
        $data = BudgetReportService::getBudgetById($id)->budgetData();
        $data['image'] = ($data['image'] && file_exists(storage_path('app/public/' . $data['image'])))
            ? asset('storage/' . $data['image'])
            : null;
        $signature = ReportSignatureService::getApprovalSignature(Budget::class, $id);
        $createdAt = Budget::find($id, ['created_at'])->created_at;
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        return view('merchandising::budget.reports.print', $data, [
            'signature' => $signature,
            'date_time' => $dateTime,
        ]);
    }

    public function budgetPdf($id): Response
    {
        $data = BudgetReportService::getBudgetById($id)->budgetData();
        $data['image'] = ($data['image'] && file_exists(storage_path('app/public/' . $data['image'])))
            ? asset('storage/' . $data['image'])
            : null;
        $signature = ReportSignatureService::getApprovalSignature(Budget::class, $id);
        $createdAt = Budget::find($id, ['created_at'])->created_at;
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::budget.reports.pdf', $data, [
                'signature' => $signature,
                'date_time' => $dateTime,
            ])
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->stream("{$id}_budget.pdf");
    }

    public function budgetReportView($id)
    {
        $budget = BudgetReportService::budgetReportView($id);

        $budgetDetails = $this->budgetCollectionData($budget['budgetFabricDetails'], $budget['budgetTrimDetails']);

        return view('merchandising::budget.budget_view', [
            'budget' => $budget['budget'] ?? null,
            'budgetTrimDetails' => $budgetDetails['trimsDetails'] ?? null,
            'budgetFabricDetails' => $budgetDetails['fabricDetails'] ?? null,
        ]);
    }

    public function costingSheetView($id, $type)
    {
        $data = CostingSheetService::budgetData($id, $type);
        return view('merchandising::budget.reports.cost_breakdown_view', $data);
    }

    /**
     * @param $id
     * @param $type
     * @return \Illuminate\Http\Response
     */
    public function costingSheetPdf($id, $type): \Illuminate\Http\Response
    {
        $data = CostingSheetService::budgetData($id, $type);
        $style = $data['mainPartData']['style_name'] ?? null;
        $signature = ReportSignatureService::getApprovalSignature(Budget::class, $id);
        $createdAt = Budget::find($id, ['created_at'])->created_at;
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::budget.reports.cost_breakdown_pdf', $data, [
                'signature' => $signature,
                'date_time' => $dateTime,
            ])
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);


        return $pdf->download("{$style}_budget_costing.pdf");
    }

    /**
     * @param $id
     * @param $type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function costingSheetPrint($id, $type)
    {
        $data = CostingSheetService::budgetData($id, $type);
        $signature = ReportSignatureService::getApprovalSignature(Budget::class, $id);
        $createdAt = Budget::find($id, ['created_at'])->created_at;
        $dateTime = Carbon::make($createdAt)->toDateTimeString();

        return view('merchandising::budget.reports.cost_breakdown_print', $data, [
            'signature' => $signature,
            'date_time' => $dateTime,
        ]);
    }

    /**
     * @param $id
     * @param $type
     * @return BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function costingSheetExcel($id, $type): BinaryFileResponse
    {
        $data = CostingSheetService::budgetData($id, $type);
        return Excel::download(new CostSheetExcel($data), 'costing-sheet.xlsx');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function BudgetFilterExcelAll(Request $request): BinaryFileResponse
    {
        $search = $request->get('search');
        return Excel::download(new BudgetAllExcel($search), 'budget-list-all.xlsx');
    }

    public function BudgetFilterExcelByPage(Request $request, BudgetFilterFormat $budgetFilterFormat): BinaryFileResponse
    {
        $search = $request->get('search');
        $page = (int)$request->get('page');
        $paginateNumber = request('paginateNumber') ?? 15;
        $budgets = $budgetFilterFormat->handle($search, $paginateNumber, 'desc', $page);

        return Excel::download(new BudgetExcel($budgets), "budget-list-of-page-no-" . $page . ".xlsx");
    }

    public function budgetCollectionData($budgetFabricDetails, $budgetTrimDetails): array
    {
        $fabricDetails = collect($budgetFabricDetails)->transform(function ($value) {
            $value['process_loss'] = collect($value['greyConsForm']['details'])->avg('process_loss');

            return $value;
        });

        $trimsDetails = collect($budgetTrimDetails)->transform(function ($value) {
            $value['ext_cons_percent'] = collect($value['breakdown']['details'] ?? null)->avg('ext_cons_percent');

            return $value;
        });

        return [
            'fabricDetails' => $fabricDetails,
            'trimsDetails' => $trimsDetails,
        ];
    }
}
