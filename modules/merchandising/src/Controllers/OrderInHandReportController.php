<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Merchandising\DTO\OrderInHandReportDTO;
use SkylarkSoft\GoRMG\Merchandising\Exports\OrderInHandExport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderInHandReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->where('factory_id', factoryId())->where('status', 'Active')->get(['id','name']);
        $buyerId = null;
        return view('merchandising::order_in_hand_report.index', ['buyers' => $buyers, 'buyerId' => $buyerId]);
    }

    /**
     * @param Request $request
     * @param OrderInHandReportDTO $reportDTO
     * @return Application|Factory|View|JsonResponse
     */
    public function getReport(Request $request, OrderInHandReportDTO $reportDTO)
    {
        try {
            $reportData = $this->getReportData($request, $reportDTO);
            return view('merchandising::order_in_hand_report.table', ['reportData' => $reportData]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param OrderInHandReportDTO $reportDTO
     * @return mixed
     */
    public function getReportPdf(Request $request, OrderInHandReportDTO $reportDTO)
    {
        $reportData = $this->getReportData($request, $reportDTO);
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::order_in_hand_report.pdf', ['reportData' => $reportData])
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('order_in_hand_report.pdf');
    }

    /**
     * @param Request $request
     * @param OrderInHandReportDTO $reportDTO
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function getReportExcel(Request $request, OrderInHandReportDTO $reportDTO): BinaryFileResponse
    {
        $reportData = $this->getReportData($request, $reportDTO);
        return Excel::download(new OrderInHandExport($reportData), 'order_in_hand.xlsx');
    }

    private function getReportData($request, $reportDTO)
    {
        $fromDate = $request->date('from_date', 'Y-m-d');
        $toDate = $request->date('to_date', 'Y-m-d') ?? date('Y-m-d');
        $buyerId = (int)$request->get('buyer_id') ?? null;
        $status = $request->get('status') ?? null;
        return $reportDTO
            ->setFromDate($fromDate)
            ->setToDate($toDate)
            ->setBuyer($buyerId)
            ->setStatus($status)
            ->generateReport()
            ->format();
    }
}
