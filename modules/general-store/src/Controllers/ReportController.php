<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\GeneralStore\Exports\InventoryExcelReport;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;
use SkylarkSoft\GoRMG\GeneralStore\Services\Reports\StockReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController
{
    public $downloadExcelPage = 'general-store::report.excel';
    public $title = 'Stock Summery Report';

    /**
     * @param Request $request
     * @param $store
     * @return Application|Factory|View|BinaryFileResponse
     */
    public function reportView(Request $request, $store)
    {
        $firstDate = $request->first_date ?? Carbon::now()->startOfMonth()->toDateString();
        $lastDate = $request->last_date ?? Carbon::now()->endOfMonth()->toDateString();
        $responseData = StockReportService::data($store, $firstDate, $lastDate, $request->input('type'));
        $store_name = strtolower(str_replace(" ", "_", get_store_name($store)));
        if (!$request->has('type')) {
            return view('general-store::report.report', $responseData);
        }
        if ($request->input('type') == 'pdf') {

            $fileName = $store_name . '_stock_summery_report.pdf';
//            return view('inventory::report.pdf', $responseData);

            $pdf = PDF::loadView('general-store::report.pdf', $responseData);
            return $pdf->stream($fileName);
        }
        if ($request->input('type') == 'excel') {
            $stockSummeryReportExport = new InventoryExcelReport(
                $responseData, $this->downloadExcelPage,
                $this->title,
                range('A', 'M'), [1, 2, 3]
            );
            $fileName = $store_name . '_stock_summery_report.xlsx';
            return Excel::download(
                $stockSummeryReportExport,
                $fileName
            );
        }
    }

    /**
     * @param Request $request
     * @param $store
     * @return Application|Factory|View|BinaryFileResponse
     */
    public function categoryWiseSummary(Request $request, $store)
    {
        $firstDate = $request->first_date ?? Carbon::now()->startOfMonth()->toDateString();
        $lastDate = $request->last_date ?? Carbon::now()->endOfMonth()->toDateString();
        $category = $request->category ?? null;
        $responseData = StockReportService::categoryWiseData(
            $store,
            $firstDate,
            $lastDate,
            $request->input('type'), $category
        );
        $store_name = strtolower(str_replace(" ", "_", get_store_name($store)));
        if (!$request->has('type')) {
            return view('general-store::report.category_wise_report', $responseData);
        }
        if ($request->input('type') == 'pdf') {
            $fileName = $store_name . '_stock_summery_report.pdf';
            $pdf = PDF::loadView('general-store::report.category_wise_report_pdf', $responseData);
            return $pdf->stream($fileName);
        }
        if ($request->input('type') == 'excel') {
            $stockSummeryReportExport = new InventoryExcelReport(
                $responseData,
                $this->downloadExcelPage,
                $this->title, range('A', 'M'), [1, 2, 3]
            );
            $fileName = $store_name . '_category_wise_stock_summery_report.xlsx';
            return Excel::download(
                $stockSummeryReportExport,
                $fileName
            );
        }
    }

    /**
     * @param Request $request
     * @param $store
     * @return Application|Factory|View
     */
    public function itemWiseSummery(Request $request, $store)
    {
        $firstDate = $request->first_date ?? Carbon::now()->startOfMonth()->toDateString();
        $lastDate = $request->last_date ?? Carbon::now()->endOfMonth()->toDateString();
        $item = $request->item ?? '';
        $items = GsItem::query()->orderBy('name', 'asc')->get()->pluck('name', 'id');
        $responseData = StockReportService::itemWiseReport(
            $store,
            $firstDate,
            $lastDate,
            $request->input('type'), $item
        );
        $responseData['all_items'] = $items;
        return view('general-store::report.item_wise_report', $responseData);
    }
}
