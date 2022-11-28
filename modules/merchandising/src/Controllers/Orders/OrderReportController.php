<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use App\Http\Controllers\Controller;
use PDF;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\DTO\OrderReportDTO;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderRecapReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\Report\ReportViewService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;

class OrderReportController extends Controller
{
    const ORDER_PDF = '_order.pdf';

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function view(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $jobNo = $request->get('job_no') ?? null;
        $poNo = $request->get('po_no') ?? [];
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $searchType = $request->get('search_type') ?? null;
        $dealingMerchantId = $request->get('dealing_merchant_id') ?? null;
        $type = $request->get('type');

        $factories = Factory::all();
        $buyers = $factoryId ? Buyer::query()->where('factory_id', $factoryId)
            ->get() : [];
        $jobs = $buyerId ? Order::query()->where("factory_id", $factoryId)->where("buyer_id", $buyerId)
            ->pluck("job_no") : [];
        $team = [];
        $pos = [];
        $poNos = [];
        if ($factoryId && $buyerId && $jobNo) {
            $order = Order::query()->where([
                "factory_id" => $factoryId,
                "buyer_id" => $buyerId,
                "job_no" => $jobNo,
            ])->first();
            $poNos = PurchaseOrder::query()->where("order_id", $order->id)->pluck("po_no");
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
            return view('merchandising::order.view', compact('summery','factories', 'pos', 'buyers', 'jobs', 'poNos', 'fromDate', 'toDate', 'factoryId', 'buyerId', 'jobNo', 'request', 'type'));
        }

        if ($factoryId) {
            $orderData = OrderReportService::reportData($orderReportDTO);
            $pos = $orderData['pos'];
            $team = $orderData['team'];
        }

        return view('merchandising::order.view', compact('summery','factories', 'pos', 'buyers', 'jobs', 'poNos', 'fromDate', 'toDate', 'factoryId', 'buyerId', 'jobNo', 'request', 'team', 'type'));

    }

    //Order Details Report
    public function orderDetails(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $jobNo = $request->get('job_no') ?? null;
        $poNo = $request->get('po_no') ?? [];
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
//        $searchType = $request->get('search_type') ?? null;
        $searchType = $request->get('type') ?? null;
        $dealingMerchantId = $request->get('dealing_merchant_id') ?? null;
        $type = $request->get('type');

        $buyerName = Buyer::query()->where('id', $buyerId)->first();
        $factories = Factory::all();
        $buyers = $factoryId ? Buyer::query()->where('factory_id', $factoryId)
            ->get() : [];
        $jobs = $buyerId ? Order::query()->where("factory_id", $factoryId)->where("buyer_id", $buyerId)
            ->pluck("job_no") : [];
        $team = [];
        $pos = [];
        $poNos = [];
        if ($factoryId && $buyerId && $jobNo) {
            $order = Order::query()->where([
                "factory_id" => $factoryId,
                "buyer_id" => $buyerId,
                "job_no" => $jobNo,
            ])->first();
            $poNos = PurchaseOrder::query()->where("order_id", $order->id)->pluck("po_no");
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
            ->setUniqueId($jobNo)
            ->setPoNo($poNo)
            ->setRemarks($order->remarks ?? null)
            ->setReferenceNo($order->reference_no ?? null)
            ->render();

        if ($factoryId) {
            $orderData = OrderReportService::reportData($orderReportDTO);
            $pos = $orderData['pos'];
            $team = $orderData['team'];
        }

        return view('merchandising::order.report.details.view', compact('summery','buyerName', 'styleName', 'factories', 'pos', 'buyers', 'jobs', 'poNos', 'fromDate', 'toDate', 'factoryId', 'buyerId', 'jobNo', 'request', 'team', 'type'));

    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function viewDealingMerchantWise(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $jobNo = $request->get('job_no') ?? null;
        $poNo = $request->get('po_no') ?? [];
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $searchType = $request->get('search_type') ?? null;
        $dealingMerchantId = $request->get('dealing_merchant_id') ?? null;

        $pos = [];
        $team = [];

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

        if ($factoryId) {
            $orderData = OrderReportService::reportData($orderReportDTO);
            $pos = $orderData['pos'];
            $team = $orderData['team'];
        }
        $factories = Factory::all();
        $dealingMerchants = $factoryId ? Team::query()
            ->where('factory_id', $factoryId)
            ->with('member')
            ->get() : [];
        $jobs = $dealingMerchantId ? Order::query()
            ->where("factory_id", $factoryId)
            ->where("dealing_merchant_id", $dealingMerchantId)
            ->pluck("job_no") : [];
        $poNos = [];
        if ($factoryId && $dealingMerchantId && $jobNo) {
            $order = Order::query()
                ->where([
                    "factory_id" => $factoryId,
                    "dealing_merchant_id" => $dealingMerchantId,
                    "job_no" => $jobNo,
                ])->first();
            $poNos = PurchaseOrder::query()
                ->where("order_id", $order->id)
                ->pluck("po_no");
        }

        $summery = ReportViewService::for('search_info')
            ->setFactoryId($factoryId)
            ->setBuyerId($buyerId)
            ->setStyleName($styleName)
            ->setDealingMerchantId($dealingMerchantId)
            ->setFromDate($fromDate)
            ->setToDate($toDate)
            ->setType($searchType)
            ->setJobNo($jobNo)
            ->setPoNo($poNo)
            ->render();


        $signature = ReportSignatureService::getSignatures("COLOR SIZE MERCHANDISE WISE REPORT", $buyerId);
        return view('merchandising::order.merchandise-wise-view',
            compact(['summery','factories', 'pos', 'dealingMerchants',
                'jobs', 'poNos', 'fromDate', 'toDate', 'factoryId',
                'dealingMerchantId', 'jobNo', 'request', 'team', 'signature']));
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function recapView(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $seasonId = $request->get('season_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;


        $buyers = $factoryId ? Buyer::query()->where('factory_id', $factoryId)->get() : [];
        $seasons = ($buyerId && $factoryId) ? Season::query()->where("factory_id", $factoryId)->where("buyer_id", $buyerId)->get() : [];
        $factories = Factory::all();

        $orderData = null;

        if ($factoryId) {
            $orderData = OrderRecapReportService::reportData($factoryId, $buyerId, $seasonId, $fromDate, $toDate, $styleName);
        }

        return view('merchandising::order.recap_report.view', compact('factories', 'factoryId', 'buyerId', 'buyers', 'seasons', 'seasonId', 'orderData'));
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function orderRecapPrint(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $seasonId = $request->get('season_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $orderData = OrderRecapReportService::reportData($factoryId, $buyerId, $seasonId, $fromDate, $toDate, $styleName);

        return view('merchandising::order.recap_report.print', compact('factoryId', 'buyerId', 'seasonId', 'orderData'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function orderRecapPdf(Request $request): Response
    {
        $factoryId = $request->get('factory_id') ?? null;
        $buyerId = $request->get('buyer_id') ?? null;
        $seasonId = $request->get('season_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $styleName = $request->get('style_name') ?? null;
        $orderData = OrderRecapReportService::reportData($factoryId, $buyerId, $seasonId, $fromDate, $toDate, $styleName);

        $pdf = PDF::loadView('merchandising::order.recap_report.pdf',
            compact('factoryId', 'buyerId', 'fromDate', 'toDate', 'request', 'orderData')
        )->setPaper('a4')->setOrientation('landscape')->setOptions([
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->download($factoryId . self::ORDER_PDF);
    }

}
