<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use App\Http\Controllers\Controller;
use PDF;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Actions\OrderFilterFormat;
use SkylarkSoft\GoRMG\Merchandising\DTO\OrderReportDTO;
use SkylarkSoft\GoRMG\Merchandising\Exports\BuyerWiseWOReportExport;
use SkylarkSoft\GoRMG\Merchandising\Exports\ColorSizeBreakdownReportExcel;
use SkylarkSoft\GoRMG\Merchandising\Exports\CurrentOrderStatusExport;
use SkylarkSoft\GoRMG\Merchandising\Exports\OrderExcel;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Services\BudgetWiseWOReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\Order\CurrentOrderStatusService;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\Report\ReportViewService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class OrderDownloadAbleController extends Controller
{
    const ORDER_PDF = '_order.pdf';

    /**
     * @param Request $request
     * @return Response
     */
    public function orderPDF(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = (int)$request->get('buyer_id') ?? null;
        $jobNo = $request->get('job_no') ?? null;
        $poNo = $request->get('po_no') != 'null' ? explode(',', $request->get('po_no')) : null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $searchType = $request->get('search_type') ?? null;
        $dealingMerchantId = $request->get('dealing_merchant_id') ?? null;
        $type = $request->get('type') ?? null;

        if ($factoryId && $buyerId && $jobNo) {
            $order = Order::query()->where([
                "factory_id" => $factoryId,
                "buyer_id" => $buyerId,
                "job_no" => $jobNo,
            ])->first();
        }

        $orderReportDTO = new OrderReportDTO();
        $orderReportDTO->setFactoryId($factoryId);
        $orderReportDTO->setBuyerId($buyerId);
        $orderReportDTO->setJobNo($jobNo);
        $orderReportDTO->setPoNo($poNo);
        $orderReportDTO->setStyleName($styleName);
        $orderReportDTO->setFromDate($fromDate);
        $orderReportDTO->setToDate($toDate);
        $orderReportDTO->setSearchType($searchType);
        $orderReportDTO->setDealingMerchantId($dealingMerchantId);

        $summery = ReportViewService::for('search_info')
            ->setFactoryId($factoryId)
            ->setBuyerId($buyerId)
            ->setStyleName($styleName)
            ->setFromDate($fromDate)
            ->setToDate($toDate)
            ->setType($searchType)
            ->setJobNo($jobNo)
            ->setPoNo($poNo)
            ->setRemarks($order->remarks ?? null)
            ->setReferenceNo($order->reference_no ?? null)
            ->render();

        if ($type === 'color_size_breakdown') {
            $pos = OrderReportService::colorWiseReport($orderReportDTO);
            $signature = ReportSignatureService::getSignatures("COLOR SIZE BREAKDOWN REPORT", $buyerId);
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::order.color_size_breakdown_pdf',
                compact('pos', 'fromDate', 'toDate', 'factoryId', 'buyerId', 'jobNo', 'request', 'type', 'signature')
            )->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);
        } else if($type === 'order_details') {
            $orderData = OrderReportService::reportData($orderReportDTO);
            $pos = $orderData['pos'];
            $team = $orderData['team'];

            $signature = ReportSignatureService::getSignatures("ORDER VIEW", $buyerId);
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::order.report.details.pdf',
                compact('factoryId', 'summery', 'buyerId', 'jobNo', 'poNo', 'fromDate', 'toDate', 'pos', 'request', 'team', 'type', 'signature')
            )->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);
        } else {
            $orderData = OrderReportService::reportData($orderReportDTO);
            $pos = $orderData['pos'];
            $team = $orderData['team'];
            $signature = ReportSignatureService::getApprovalSignature(Order::class, $order->id);
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::order.pdf',
                compact('factoryId', 'summery', 'buyerId', 'jobNo', 'poNo', 'fromDate', 'toDate', 'pos', 'request', 'team', 'type', 'signature')
            )->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);
        }
        return $pdf->stream($factoryId . self::ORDER_PDF);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function orderPrint(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $jobNo = $request->get('job_no') ?? null;
        $poNo = $request->get('po_no') != 'null' ? explode(',', $request->get('po_no')) : null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $searchType = $request->get('search_type') ?? null;
        $dealingMerchantId = $request->get('dealing_merchant_id') ?? null;
        $type = $request->get('type') ?? null;

        $orderReportDTO = new OrderReportDTO();
        $orderReportDTO->setFactoryId($factoryId);
        $orderReportDTO->setBuyerId($buyerId);
        $orderReportDTO->setJobNo($jobNo);
        $orderReportDTO->setPoNo($poNo);
        $orderReportDTO->setStyleName($styleName);
        $orderReportDTO->setFromDate($fromDate);
        $orderReportDTO->setToDate($toDate);
        $orderReportDTO->setSearchType($searchType);
        $orderReportDTO->setDealingMerchantId($dealingMerchantId);

        if ($type === 'color_size_breakdown') {
            $pos = OrderReportService::colorWiseReport($orderReportDTO);
            $signature = ReportSignatureService::getSignatures("COLOR SIZE BREAKDOWN REPORT", $buyerId);
            return view('merchandising::order.color_size_breakdown_print', compact('pos', 'fromDate', 'toDate', 'factoryId', 'buyerId', 'jobNo', 'request', 'type', 'signature'));
        } else {
            $orderData = OrderReportService::reportData($orderReportDTO);
            $pos = $orderData['pos'];
            $team = $orderData['team'];
            $signature = ReportSignatureService::getSignatures("ORDER VIEW", $buyerId);
            return view('merchandising::order.print', compact('factoryId', 'buyerId', 'jobNo', 'poNo', 'fromDate', 'toDate', 'pos', 'request', 'team', 'type', 'signature'));
        }
    }


    public function orderExcel(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $jobNo = $request->get('job_no') ?? null;
        $poNo = $request->get('po_no') != 'null' ? explode(',', $request->get('po_no')) : null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $type = $request->get('type') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $searchType = $request->get('search_type') ?? null;

        $orderReportDTO = new OrderReportDTO();
        $orderReportDTO->setStyleName($styleName);
        $orderReportDTO->setFactoryId($factoryId);
        $orderReportDTO->setBuyerId($buyerId);
        $orderReportDTO->setJobNo($jobNo);
        $orderReportDTO->setPoNo($poNo);
        $orderReportDTO->setFromDate($fromDate);
        $orderReportDTO->setToDate($toDate);
        $orderReportDTO->setSearchType($searchType);


        if ($type === 'color_size_breakdown') {
            $pos = OrderReportService::colorWiseReport($orderReportDTO);
            return Excel::download(new ColorSizeBreakdownReportExcel($pos, $fromDate, $toDate, $factoryId, $buyerId, $jobNo, $request, $type), 'color-size-breakdown.xlsx');
        }
    }

    public function orderListExcelAll(Request $request, OrderFilterFormat $orderFilterFormat)
    {
        $search = $request->get('search');
        $sort = $request->get('sort') ?? 'DESC';
        $orders = $orderFilterFormat->handleAll($search, $sort);
        return Excel::download(new OrderExcel($orders), 'order-list-all.xlsx');
    }

    public function orderListExcelByPage(Request $request, OrderFilterFormat $orderFilterFormat)
    {
        $search = $request->get('search');
        $page = (int) $request->get('page');
        $sort = $request->get('sort') ?? 'DESC';
        $paginateNumber = request('paginateNumber') ?? 15;
        $orders = $orderFilterFormat->handle($search, $sort, $page,$paginateNumber);
        return Excel::download(new OrderExcel($orders), "order-list-of-page-no-".$page.".xlsx");
    }

    public function orderCurrentStatusView(Request $request, $reportID = null)
    {
        $companies = \SkylarkSoft\GoRMG\SystemSettings\Models\Factory::query()->get(['id', 'factory_name']);
        return view('merchandising::reports.order-current-status.view'.$reportID, compact('companies'));

    }

    public function orderCurrentStatusReportData(Request $request, $reportID = null)
    {
        $factoryId = $request->get('company_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $jobNo = $request->get('unique_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;

        $orderReportDTO = new OrderReportDTO();
        $orderReportDTO->setStyleName($styleName);
        $orderReportDTO->setFactoryId($factoryId);
        $orderReportDTO->setBuyerId($buyerId);
        $orderReportDTO->setUniqueId($jobNo);

        $orders = CurrentOrderStatusService::getReportData($request);

        $header = ReportViewService::for('search_info')->setFactoryId($factoryId)
            ->setBuyerId($buyerId)
            ->setStyleName($styleName)
            ->setUniqueId($jobNo)
            ->setFromDate($fromDate)
            ->setToDate($toDate)
            ->render();


        return view('merchandising::reports.order-current-status.table'.$reportID , compact('orders', 'header'));
    }

    public function orderCurrentStatusPdfData(Request $request, $reportID = null)
    {
        $factoryId = $request->get('company_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $jobNo = $request->get('unique_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;

        $header = ReportViewService::for('search_info')->setFactoryId($factoryId)
            ->setBuyerId($buyerId)
            ->setStyleName($styleName)
            ->setUniqueId($jobNo)
            ->setFromDate($fromDate)
            ->setToDate($toDate)
            ->render();

        $orders = CurrentOrderStatusService::getReportData($request);
        $pdf = PDF::loadView('merchandising::reports.order-current-status.pdf'.$reportID , compact('orders', 'header'))
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('current_order_status.pdf');
    }

    public function orderCurrentStatusExcelData(Request $request, $reportID = null)
    {
        $factoryId = $request->get('company_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $jobNo = $request->get('unique_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;

        $header = ReportViewService::for('search_info')->setFactoryId($factoryId)
            ->setBuyerId($buyerId)
            ->setStyleName($styleName)
            ->setUniqueId($jobNo)
            ->setFromDate($fromDate)
            ->setToDate($toDate)
            ->render();

        $orders = CurrentOrderStatusService::getReportData($request);
        $page_name = ($reportID==2?'order_status.xlsx':'current_order_status.xlsx');
        return \Maatwebsite\Excel\Facades\Excel::download(new CurrentOrderStatusExport($orders, $header, $reportID), $page_name);
    }
}
