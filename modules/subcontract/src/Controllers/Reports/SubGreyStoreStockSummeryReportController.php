<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Exports\SubGreyStoreStockExport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreDailyStockSummaryReport;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class SubGreyStoreStockSummeryReportController extends Controller
{
    public function view(Request $request)
    {
        $stockSummery = SubGreyStoreDailyStockSummaryReport::query()
            ->with([
                'supplier',
                'subGreyStore',
                'fabricComposition',
                'fabricType',
                'color',
                'colorType',
                'unitOfMeasurement',
                'subTextileOperation',
            ])
            ->where('production_date', '>', Carbon::now()->subMonths(6))
            ->orderBy('id', 'desc')
            ->get();

        return view(PackageConst::VIEW_PATH . 'report.Sub_grey_store_stock_summery', [
            'stockSummery' => $stockSummery,
        ]);
    }

    public function getReport(Request $request)
    {
        $formDate = $request->form_date ? Carbon::make($request->form_date)->format('Y-m-d') : date('Y-m-d');
        $toDate = $request->to_date ? Carbon::make($request->to_date)->format('Y-m-d') : date('Y-m-d');

        $stockSummery = SubGreyStoreDailyStockSummaryReport::query()
            ->with([
                'supplier',
                'subGreyStore',
                'fabricComposition',
                'fabricType',
                'color',
                'colorType',
                'unitOfMeasurement',
                'subTextileOperation',
            ])
            ->whereBetween('production_date', [$formDate, $toDate])
            ->get();

        return view(PackageConst::VIEW_PATH . 'report.sub_grey_store_stock_summery_table', [
            'stockSummery' => $stockSummery,
        ]);
    }

    public function pdf(Request $request)
    {
        $formDate = $request->form_date ? Carbon::make($request->form_date)->format('Y-m-d') : date('Y-m-d');
        $toDate = $request->to_date ? Carbon::make($request->to_date)->format('Y-m-d') : date('Y-m-d');

        $stockSummery = SubGreyStoreDailyStockSummaryReport::query()
            ->with([
                'supplier',
                'subGreyStore',
                'fabricComposition',
                'fabricType',
                'color',
                'colorType',
                'unitOfMeasurement',
                'subTextileOperation',
            ]);
        if ($formDate && $toDate) {
            $stockSummery = $stockSummery->whereBetween('production_date', [$formDate, $toDate])->get();
        } else {
            $stockSummery = $stockSummery->where('production_date', '>', Carbon::now()->subMonths(6))->get();
        }
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::report.pdf.sub_grey_store_stock_summery_pdf', [
                'stockSummery' => $stockSummery,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('sub_grey_store_stock_summery_report.pdf');
    }

    public function excel(Request $request)
    {
        $formDate = $request->form_date ? Carbon::make($request->form_date)->format('Y-m-d') : date('Y-m-d');
        $toDate = $request->to_date ? Carbon::make($request->to_date)->format('Y-m-d') : date('Y-m-d');

        $stockSummery = SubGreyStoreDailyStockSummaryReport::query()
            ->with([
                'supplier',
                'subGreyStore',
                'fabricComposition',
                'fabricType',
                'color',
                'colorType',
                'unitOfMeasurement',
                'subTextileOperation',
            ]);
        if ($formDate && $toDate) {
            $stockSummery = $stockSummery->whereBetween('production_date', [$formDate, $toDate])->get();
        } else {
            $stockSummery = $stockSummery->where('production_date', '>', Carbon::now()->subMonths(6))->get();
        }

        return Excel::download(new SubGreyStoreStockExport($stockSummery), 'sub_grey_store_stock_summery.xlsx');
    }
}
