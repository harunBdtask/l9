<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Actions\FabricBookingVirtualStockAction;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;
use SkylarkSoft\GoRMG\Merchandising\Exports\FabricBookingExcelViewFour;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Requests\Bookings\FabricBookingRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\FabricBookingReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\FabricBookingReportsForGearsService;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\StyleWiseFabricBookingReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\PurchaseOrder\POBookingStatusService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FabricBookingDetailsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function fabricBookingDetailsData(Request $request): JsonResponse
    {
        $request->validate([
            'booking_id' => 'required',
        ]);

        try {
            $bookingId = $request->get('booking_id');

            $bookingPercent = FabricBooking::query()->find($bookingId)->booking_percent ?? null;

            $data = collect($request->get('selectedItems'))
                ->values();

            $uniqueIds = collect($request->get('selectedItems'))
                ->pluck('unique_id')
                ->unique()
                ->values();

            $oldData = FabricBookingDetailsBreakdown::query()
                ->whereIn('job_no', $uniqueIds)
                ->get();

            $formattedData = [];
            foreach ($data as $row) {
                foreach ($row['breakdown'] as $detail) {
                    $formattedData[] = [
                        'booking_id' => $bookingId,
                        'garments_item_id' => $row['item_id'] ?? null,
                        'garments_item_name' => $row['item_name'] ?? null,
                        'fabric_composition_id' => $row['fabric_composition_id'] ?? null,
                        'fabric_composition_value' => $row['fabric_composition_value'] ?? null,
                        'style_name' => $row['style_name'] ?? null,
                        'job_no' => $row['unique_id'] ?? null,
                        'po_no' => $row['po_no'] ?? null,
                        'body_part_value' => $row['body_part_value'] ?? null,
                        'body_part_id' => $row['body_part_id'] ?? null,
                        'color_type_id' => $row['color_type_id'] ?? null,
                        'color_type_value' => $row['color_type_value'] ?? null,
                        'dia_type' => $row['dia_type'] ?? null,
                        'dia_fin_type' => $detail['dia_fin_type'] ?? '',
                        'dia_type_value' => $row['dia_type_value'] ?? null,
                        'construction' => $row['construction'] ?? null,
                        'composition' => $row['composition'] ?? null,
                        'gsm' => $row['gsm'] ?? null,
                        'item_color' => $detail['contrast_colors'] ?: $detail['color'],
                        'gmt_color' => $detail['color'] ?? null,
                        'color' => $detail['color'] ?? null,
                        'color_id' => $detail['color_id'] ?? null,
                        'size' => $detail['size'] ?? null,
                        'size_id' => $detail['size_id'] ?? null,
                        'dia' => $detail['dia'] ?? null,
                        'process_loss' => $detail['process_loss'] ?? 0,
//                        'balance_qty' =>
                        'total_qty' => $detail['total_qty'] ?? 0,
                        'wo_qty' => null,
                        'adj_qty' => null,
                        'moq_qty' => null,
                        'actual_wo_qty' => null,
                        'uom_value' => $row['uom_value'] ?? null,
                        'uom' => (int)$row['uom'] ?? null,
                        'rate' => $detail['rate'] ?? 0,
                        'amount' => $detail['amount'] ?? null,
                        'remarks' => $detail['remarks'] ?? null,
                        'code' => $row['code'],
                    ];
                }
            }

            $groupByData = [];

            foreach (collect($formattedData)->groupBy('job_no') as $uniqueId => $jobWiseData) {
                foreach ($jobWiseData->groupBy('po_no') as $poNo => $poWiseData) {
                    foreach ($poWiseData->groupBy('body_part_id') as $bodyPartId => $bodyPartWiseData) {
                        foreach ($bodyPartWiseData->groupBy('construction') as $constructionId => $constructionWiseData) {
                            foreach ($constructionWiseData->groupBy('garments_item_id') as $gmtsItemId => $gmtsItemWiseData) {
                                foreach ($gmtsItemWiseData->groupBy('fabric_composition_id') as $compositionId => $compositionWiseData) {
                                    foreach ($compositionWiseData->groupBy('color_type_id') as $colorTypeId => $colorTypeWiseData) {
                                        foreach ($colorTypeWiseData->groupBy('dia_type') as $diaTypeId => $diaTypeWiseData) {
                                            foreach ($diaTypeWiseData->groupBy('color_id') as $colorId => $colorWiseData) {
                                                $colorWiseFirst = collect($colorWiseData)->whereNotNull('dia')->first();
                                                $totalQty = sprintf("%01.4f", $colorWiseData->sum('total_qty') ?? 0);
                                                $balanceQtyCalculated = $this->calculateBalanceQty($uniqueId, $poNo, $bodyPartId, $colorId, $totalQty, $oldData, $bookingPercent);

                                                if ($balanceQtyCalculated == 0) {
                                                    continue;
                                                }

                                                $balanceQty = sprintf('%01.4f', $balanceQtyCalculated);
                                                $adjQty = ceil($balanceQty) - $balanceQty;
                                                $adjWorkOrderQty = format($adjQty);
//                            $actualWorkOrderQty = format($balanceQtyCalculated + $adjQty);
                                                $actualWorkOrderQty = format($balanceQtyCalculated);

                                                $uom = isset($colorWiseFirst['uom_value']) ? strtolower($colorWiseFirst['uom_value']) : null;
                                                $yards = $uom == 'kg' ? FabricBookingReportService::convertKgToYards($colorWiseFirst, $actualWorkOrderQty) : 0;

                                                $rate = $colorWiseFirst['rate'] ?? 0;
                                                $groupByData[] = [
                                                    'booking_id' => $bookingId,
                                                    'job_no' => $uniqueId,
                                                    'po_no' => $poNo,
                                                    'garments_item_id' => $colorWiseFirst['garments_item_id'] ?? '',
                                                    'garments_item_name' => $colorWiseFirst['garments_item_name'] ?? '',
                                                    'fabric_composition_id' => $colorWiseFirst['fabric_composition_id'] ?? '',
                                                    'fabric_composition_value' => $colorWiseFirst['fabric_composition_value'] ?? '',
                                                    'style_name' => $colorWiseFirst['style_name'] ?? '',
                                                    'body_part_value' => $colorWiseFirst['body_part_value'] ?? '',
                                                    'body_part_id' => $bodyPartId,
                                                    'color_type_id' => $colorWiseFirst['color_type_id'] ?? '',
                                                    'color_type_value' => $colorWiseFirst['color_type_value'] ?? '',
                                                    'dia_type' => $colorWiseFirst['dia_type'] ?? '',
                                                    'dia_fin_type' => collect($colorWiseData)->whereNotIn('dia', [0, null])->first()['dia_type_value'] ?? '',
//                                                    'dia_type_value' => $colorWiseFirst['dia_type_value'] ?? '',
                                                    'dia_type_value' => $colorWiseFirst['dia_type_value'] ?? '',
                                                    'construction' => $colorWiseFirst['construction'] ?? '',
                                                    'composition' => $colorWiseFirst['composition'] ?? '',
                                                    'gsm' => $colorWiseFirst['gsm'] ?? '',
                                                    'item_color' => $colorWiseFirst['item_color'] ?? '',
                                                    'gmt_color' => $colorWiseFirst['gmt_color'] ?? '',
                                                    'color' => $colorWiseFirst['color'] ?? '',
                                                    'color_id' => $colorWiseFirst['color_id'] ?? '',
                                                    'size' => $colorWiseFirst['size'] ?? '',
                                                    'size_id' => $colorWiseFirst['size_id'] ?? '',
                                                    'dia' => collect($colorWiseData)->whereNotIn('dia', [0, null])->first()['dia'] ?? '',
                                                    // 'process_loss' => $colorWiseFirst['process_loss'] ?? '',
                                                    'process_loss' => collect($colorWiseData)->whereNotIn('dia', [0, null])->first()['process_loss'] ?? 0,
                                                    'balance_qty' => $balanceQty,
                                                    'total_qty' => $totalQty,
                                                    'wo_qty' => $balanceQty,
                                                    'description' => $bodyPartId . ($colorWiseFirst['color_type_id'] ?? null) .
                                                        ($colorWiseFirst['dia_type_value'] ?? null) . ($colorWiseFirst['construction'] ?? null) . ($colorWiseFirst['gsm'] ?? null) . ($colorWiseFirst['dia'] ?? null),
//                                'adj_qty' => $adjWorkOrderQty,
                                                    'adj_qty' => 0,
                                                    'moq_qty' => null,
                                                    'actual_wo_qty' => round($actualWorkOrderQty),
                                                    'uom_value' => $colorWiseFirst['uom_value'] ?? '',
                                                    'uom' => isset($colorWiseFirst['uom']) ? (int)$colorWiseFirst['uom'] : '',
                                                    'rate' => sprintf('%01.4f', $colorWiseFirst['rate'] ?? 0),
                                                    'amount' => sprintf('%01.4f', $balanceQty * (double)$rate),
                                                    'remarks' => $colorWiseFirst['remarks'] ?? '',
                                                    'yards' => $yards,
                                                    'actual_amount' => format($balanceQty * (double)$rate),
                                                    'code' => $colorWiseFirst['code'] ?? null,
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }


                    }
                }
            }
            return response()->json($groupByData, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    private function calculateBalanceQty($uniqueId, $poNo, $bodyPartId, $colorId, $totalQty, $oldData, $bookingPercent): float
    {
        $qty = collect($oldData)->where('job_no', $uniqueId)
            ->where('po_no', $poNo)
            ->where('body_part_id', $bodyPartId)
            ->where('color_id', $colorId)
            ->sum('wo_qty');

        if ($bookingPercent != null || $bookingPercent != 0) {
            return ($bookingPercent / 100) * ($totalQty - $qty);
        }

        return $totalQty - $qty;
    }

    /**
     * @param FabricBookingRequest $request
     * @param FabricBookingVirtualStockAction $stockAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(FabricBookingRequest $request, FabricBookingVirtualStockAction $stockAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $data) {
                if (isset($data['id'])) {
                    $target = FabricBookingDetailsBreakdown::query()->findOrFail($data['id']);
                } else {
                    $target = null;
                }
                if ($target) {
                    $target->fill(collect($data)->all())->save();
                } else {
                    POBookingStatusService::statusUpdate($data['po_no'], $data['color_id'], $data['size_id'], 'fabric_booking');
                    $target = FabricBookingDetailsBreakdown::query()->create($data);
                }
                $stockAction->handle($data);

                // Style Audit Report Action Fabric Booking Qty and values Update
                (new StyleAuditReportAction())
                    ->init($target->order->id)
                    ->handleOrder()
                    ->handleBudget()
                    ->handleFabricBooking()
                    ->saveOrUpdate();

            }
            FabricBookingDetails::where('booking_id', $request->all()[0]['booking_id'])->delete();
            DB::commit();

            return response()->json($request->all(), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        return view('merchandising::fabric-bookings.view', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'poDetails' => $fabricBookings['poDetails'],
            'collarDetails' => $fabricBookings['collarDetails'],
            'cuffDetails' => $fabricBookings['cuffDetails'],
            'yarnDetails' => $fabricBookings['yarnDetails'],
            'collarStripDetails' => $fabricBookings['collarStripDetails'],
            'cuffStripDetails' => $fabricBookings['cuffStripDetails'],
        ]);
    }

    public function printView($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        $signature = ReportSignatureService::getApprovalSignature(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings['fabricBookings']->created_at)->toFormattedDateString();
        return view('merchandising::fabric-bookings.print', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'poDetails' => $fabricBookings['poDetails'],
            'collarDetails' => $fabricBookings['collarDetails'],
            'cuffDetails' => $fabricBookings['cuffDetails'],
            'yarnDetails' => $fabricBookings['yarnDetails'],
            'signature' => $signature,
            'date_time' => $dateTime,
        ]);
    }

    public function pdf($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        $signature = ReportSignatureService::getApprovalSignature(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings['fabricBookings']->created_at)->toFormattedDateString();

        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::fabric-bookings.pdf', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'poDetails' => $fabricBookings['poDetails'],
            'collarDetails' => $fabricBookings['collarDetails'],
            'cuffDetails' => $fabricBookings['cuffDetails'],
            'yarnDetails' => $fabricBookings['yarnDetails'],
            'signature' => $signature,
            'date_time' => $dateTime,
            'collarStripDetails' => $fabricBookings['collarStripDetails'],
            'cuffStripDetails' => $fabricBookings['cuffStripDetails'],
        ])->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer', compact('signature')),
        ]);

        return $pdf->stream("{$id}_fabric_bookings.pdf");
    }

    public function gearsView($id)
    {
        $fabricBookings = FabricBookingReportsForGearsService::mainFabricBookingData($id);
        return view('merchandising::fabric-bookings.gears-view', compact('fabricBookings'));
    }

    public function gearsPrint($id)
    {
        $fabricBookings = FabricBookingReportsForGearsService::mainFabricBookingData($id);
        $signature = ReportSignatureService::getApprovalSignature(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings->created_at)->toFormattedDateString();
        return view('merchandising::fabric-bookings.gears-print', [
            'fabricBookings' => $fabricBookings,
            'signature' => $signature,
            'date_time' => $dateTime,
        ]);
    }

    public function gearsPdf($id)
    {
        $fabricBookings = FabricBookingReportsForGearsService::mainFabricBookingData($id);
        $signature = ReportSignatureService::getApprovalSignature(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings->created_at)->toFormattedDateString();
        $pdf = PDF::loadView('merchandising::fabric-bookings.gears-pdf', [
            'fabricBookings' => $fabricBookings,
            'signature' => $signature,
            'date_time' => $dateTime,
        ])
            ->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->stream("{$id}_fabric_bookings.pdf");
    }

    public function styleWiseView($id)
    {
        $fabricBookings = StyleWiseFabricBookingReportService::mainFabricData($id);

        return view('merchandising::fabric-bookings.style-wise-view', compact('fabricBookings'));
    }

    public function styleWisePrint($id)
    {
        $fabricBookings = StyleWiseFabricBookingReportService::mainFabricData($id);
        $signature = ReportSignatureService::getApprovalSignature(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings->created_at)->toFormattedDateString();
        return view('merchandising::fabric-bookings.style-wise-print', [
            'fabricBookings' => $fabricBookings,
            'signature' => $signature,
            'date_time' => $dateTime
        ]);
    }

    public function styleWisePdf($id): Response
    {
        $fabricBookings = StyleWiseFabricBookingReportService::mainFabricData($id);
        $signature = ReportSignatureService::getApprovalSignature(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings->created_at)->toFormattedDateString();
        $pdf = PDF::loadView('merchandising::fabric-bookings.style-wise-pdf',
            [
                'fabricBookings' => $fabricBookings,
                'signature' => $signature,
                'date_time' => $dateTime
            ])
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->stream("{$id}_fabric_bookings.pdf");
    }

    public function summaryView($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);

        return view('merchandising::fabric-bookings.fabric-summary-view', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
        ]);
    }

    public function summaryPrint($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        $signature = ReportSignatureService::getSignatures(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings['fabricBookings']->created_at)->toFormattedDateString();
        return view('merchandising::fabric-bookings.fabric-summary-print', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'signature' => $signature,
            'date_time' => $dateTime,
        ]);
    }

    public function summaryPdf($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        $signature = ReportSignatureService::getApprovalSignature(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings['fabricBookings']->created_at)->toFormattedDateString();
        $pdf = PDF::loadView('merchandising::fabric-bookings.fabric-summary-pdf', [
            'header-html' => view('skeleton::pdf.header'),
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'signature' => $signature,
            'date_time' => $dateTime,
        ])->setPaper('a4', 'landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer', compact('signature')),
        ]);

        return $pdf->stream("{$id}_fabric_summary_report.pdf");
    }

    public function viewMondol($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        $poNoDetails = PurchaseOrder::query()->whereIn('po_no', explode(',', $fabricBookings['fabricBookings']->po_no))->get();
        $sortedShipmentDate = $poNoDetails->sortBy('ex_factory_date')->first()->ex_factory_date;

        return view('merchandising::fabric-bookings.view-for-mondol.view', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'collarDetails' => $fabricBookings['collarDetails'],
            'cuffDetails' => $fabricBookings['cuffDetails'],
            'yarnDetails' => $fabricBookings['yarnDetails'],
            'collarStripDetails' => $fabricBookings['collarStripDetails'],
            'cuffStripDetails' => $fabricBookings['cuffStripDetails'],
            'sortedShipmentDate' => $sortedShipmentDate
        ]);
    }

    public function pdfMondol($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        $poNoDetails = PurchaseOrder::query()->whereIn('po_no', explode(',', $fabricBookings['fabricBookings']->po_no))->get();
        $sortedShipmentDate = $poNoDetails->sortBy('ex_factory_date')->first()->ex_factory_date;
        $signature = ReportSignatureService::getApprovalSignature(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings['fabricBookings']->created_at)->toFormattedDateString();

        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::fabric-bookings.view-for-mondol.pdf', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'collarDetails' => $fabricBookings['collarDetails'],
            'cuffDetails' => $fabricBookings['cuffDetails'],
            'yarnDetails' => $fabricBookings['yarnDetails'],
            'collarStripDetails' => $fabricBookings['collarStripDetails'],
            'cuffStripDetails' => $fabricBookings['cuffStripDetails'],
            'signature' => $signature,
            'date_time' => $dateTime,
            'sortedShipmentDate' => $sortedShipmentDate
        ])->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer', compact('signature')),
        ]);

        return $pdf->stream("{$id}_fabric_bookings_sheet.pdf");

    }

    public function viewFour($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        $poNoDetails = PurchaseOrder::query()
            ->whereIn('po_no', explode(',', $fabricBookings['fabricBookings']->po_no))
            ->get();
        $sortedShipmentDate = $poNoDetails->sortBy('ex_factory_date')->first()->ex_factory_date;

        return view('merchandising::fabric-bookings.view-4.view', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'collarDetails' => $fabricBookings['collarDetails'],
            'cuffDetails' => $fabricBookings['cuffDetails'],
            'yarnDetails' => $fabricBookings['yarnDetails'],
            'collarStripDetails' => $fabricBookings['collarStripDetails'],
            'cuffStripDetails' => $fabricBookings['cuffStripDetails'],
            'sortedShipmentDate' => $sortedShipmentDate
        ]);
    }

    public function pdfViewFour($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        $signature = ReportSignatureService::getSignatures(FabricBooking::class, $id);
        $dateTime = Carbon::make($fabricBookings['fabricBookings']->created_at)->toFormattedDateString();

        $poNoDetails = PurchaseOrder::query()->whereIn('po_no', explode(',', $fabricBookings['fabricBookings']->po_no))->get();
        $sortedShipmentDate = $poNoDetails->sortByDesc('ex_factory_date')->first()->ex_factory_date;
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::fabric-bookings.view-4.pdf', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'collarDetails' => $fabricBookings['collarDetails'],
            'cuffDetails' => $fabricBookings['cuffDetails'],
            'yarnDetails' => $fabricBookings['yarnDetails'],
            'collarStripDetails' => $fabricBookings['collarStripDetails'],
            'cuffStripDetails' => $fabricBookings['cuffStripDetails'],
            'signature' => $signature,
            'date_time' => $dateTime,
            'sortedShipmentDate' => $sortedShipmentDate
        ])->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer', compact('signature')),
        ]);

        return $pdf->stream("{$id}_fabric_bookings_sheet.pdf");
    }

    public function excelViewFour($id)
    {
        $fabricBookings = FabricBookingReportService::mainFabricData($id);
        $poNoDetails = PurchaseOrder::query()->whereIn('po_no', explode(',', $fabricBookings['fabricBookings']->po_no))->get();
        $sortedShipmentDate = $poNoDetails->sortByDesc('ex_factory_date')->first()->ex_factory_date;
        $data['fabricBookings'] = $fabricBookings['fabricBookings'];
        $data['collarDetails'] = $fabricBookings['collarDetails'];
        $data['cuffDetails'] = $fabricBookings['cuffDetails'];
        $data['yarnDetails'] = $fabricBookings['yarnDetails'];
        $data['collarStripDetails'] = $fabricBookings['collarStripDetails'];
        $data['cuffStripDetails'] = $fabricBookings['cuffStripDetails'];
        $data['sortedShipmentDate'] = $sortedShipmentDate;
        return Excel::download(new FabricBookingExcelViewFour($data), 'fabric_booking_view_four.xlsx');
    }
}
