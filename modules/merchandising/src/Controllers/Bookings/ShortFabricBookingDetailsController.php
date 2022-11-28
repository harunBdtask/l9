<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrder;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\FabricBookingReportService;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetailsBreakdown;

class ShortFabricBookingDetailsController extends Controller
{
    public function fabricBookingDetailsData(Request $request): JsonResponse
    {
        $request->validate([
            'short_booking_id' => 'required',
        ]);

        try {
            $bookingId = $request->get('short_booking_id');

            $data = collect($request->get('selectedItems'))
                ->sortBy('construction')
                ->sortBy('composition')
                ->sortBy('body_part_id')
                ->sortByDesc('id')
                ->values();

            $uniqueIds = collect($request->get('selectedItems'))
                ->pluck('unique_id')
                ->unique()
                ->values();

            $oldData = ShortFabricBookingDetailsBreakdown::query()
                ->whereIn('job_no', $uniqueIds)
                ->get();

            $formattedData = [];

            foreach ($data as $row) {
                foreach ($row['breakdown'] as $detail) {
                    $formattedData[] = [
                        'short_booking_id' => $bookingId,
                        'job_no' => $row['unique_id'],
                        'po_no' => $row['po_no'],
                        'body_part_value' => $row['body_part_value'],
                        'body_part_id' => $row['body_part_id'],
                        'color_type_id' => $row['color_type_id'],
                        'color_type_value' => $row['color_type_value'],
                        'dia_type' => $row['dia_type'],
                        'dia_type_value' => $row['dia_type_value'],
                        'construction' => $row['construction'],
                        'composition' => $row['composition'],
                        'gsm' => $row['gsm'],
                        'item_color' => $detail['color'],
                        'gmt_color' => $detail['contrast_colors'] ?: $detail['color'],
                        'color' => $detail['color'],
                        'color_id' => $detail['color_id'],
                        'size' => $detail['size'],
                        'size_id' => $detail['size_id'],
                        'dia' => $detail['dia'],
                        'process_loss' => $detail['process_loss'],
//                        'balance_qty' =>
                        'total_qty' => $detail['total_qty'],
                        'wo_qty' => null,
                        'adj_qty' => null,
                        'first_adj_qty' => null,
                        'second_adj_qty' => null,
                        'third_adj_qty' => null,
                        'adj_qty_status' => 0,
                        'actual_wo_qty' => null,
                        'uom_value' => $row['uom_value'],
                        'uom' => (int)$row['uom'],
                        'rate' => $detail['rate'],
                        'amount' => $detail['amount'],
                        'remarks' => $detail['remarks'],
                    ];
                }
            }

            $groupByData = [];

            foreach (collect($formattedData)->groupBy('job_no') as $uniqueId => $jobWiseData) {
                foreach ($jobWiseData->groupBy('po_no') as $poNo => $poWiseData) {
                    foreach ($poWiseData->groupBy('body_part_id') as $bodyPartId => $bodyPartWiseData) {
                        foreach ($bodyPartWiseData->groupBy('color_id') as $colorId => $colorWiseData) {
                            $colorWiseFirst = $colorWiseData->first();
                            $totalQty = sprintf('%01.4f', $colorWiseData->sum('total_qty'));
                            $balanceQty = sprintf('%01.4f', $this->calculateBalanceQty($uniqueId, $poNo, $bodyPartId, $colorId, $totalQty, $oldData));

                            if ($balanceQty == 0) {
                                continue;
                            }

                            $groupByData[] = [
                                'short_booking_id' => $bookingId,
                                'job_no' => $uniqueId,
                                'po_no' => $poNo,
                                'body_part_value' => $colorWiseFirst['body_part_value'],
                                'body_part_id' => $bodyPartId,
                                'color_type_id' => $colorWiseFirst['color_type_id'],
                                'color_type_value' => $colorWiseFirst['color_type_value'],
                                'dia_type' => $colorWiseFirst['dia_type'],
                                'dia_type_value' => $colorWiseFirst['dia_type_value'],
                                'construction' => $colorWiseFirst['construction'],
                                'composition' => $colorWiseFirst['composition'],
                                'gsm' => $colorWiseFirst['gsm'],
                                'item_color' => $colorWiseFirst['item_color'],
                                'gmt_color' => $colorWiseFirst['gmt_color'],
                                'color' => $colorWiseFirst['color'],
                                'color_id' => $colorWiseFirst['color_id'],
                                'size' => $colorWiseFirst['size'],
                                'size_id' => $colorWiseFirst['size_id'],
                                'dia' => $colorWiseFirst['dia'],
                                'process_loss' => $colorWiseFirst['process_loss'],
                                'balance_qty' => $balanceQty,
                                'total_qty' => $totalQty,
                                'wo_qty' => $balanceQty,
                                'adj_qty' => null,
                                'first_adj_qty' => null,
                                'second_adj_qty' => null,
                                'third_adj_qty' => null,
                                'adj_qty_status' => 0,
                                'actual_wo_qty' => $balanceQty,
                                'uom_value' => $colorWiseFirst['uom_value'],
                                'uom' => (int)$colorWiseFirst['uom'],
                                'rate' => $colorWiseFirst['rate'],
                                'amount' => sprintf('%01.4f', $balanceQty * (double)$colorWiseFirst['rate']),
                                'remarks' => $colorWiseFirst['remarks'],
                            ];
                        }
                    }
                }
            }

            return response()->json($groupByData, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function calculateBalanceQty($uniqueId, $poNo, $bodyPartId, $colorId, $totalQty, $oldData): float
    {
        $qty = collect($oldData)->where('job_no', $uniqueId)
            ->where('po_no', $poNo)
            ->where('body_part_id', $bodyPartId)
            ->where('color_id', $colorId)
            ->sum('wo_qty');

        return $totalQty - $qty;
    }

    private function hasBreakdown($row)
    {
        return $row->breakdown && count($row->breakdown);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $data) {

                $target = ShortFabricBookingDetailsBreakdown::query()->firstOrNew(['id' => $data['id'] ?? null]);

                if ($data['adj_qty_status'] == 0 && $target->first_adj_qty != $data['first_adj_qty']) {
                    $data['adj_qty_status'] = 1;
                }
                else if($data['adj_qty_status'] == 1 && $target->second_adj_qty != $data['second_adj_qty']) {
                    $data['adj_qty_status'] = 2;
                }
                else if ($data['adj_qty_status'] == 2 && $target->third_adj_qty != $data['third_adj_qty']) {
                    $data['adj_qty_status'] = 3;
                }

                $target->fill($data);
                $target->save();

                // Style Audit Report Action Fabric Booking Qty and values Update
                (new StyleAuditReportAction())
                    ->init($target->order->id)
                    ->handleOrder()
                    ->handleBudget()
                    ->handleFabricBooking()
                    ->saveOrUpdate();
            }
            ShortFabricBookingDetails::where('short_booking_id', $request->all()[0]['short_booking_id'])->delete();
            DB::commit();

            return response()->json($request->all(), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function summaryView($id)
    {
        $fabricBookings = FabricBookingReportService::shortFabricData($id);

        return view('merchandising::fabric-bookings.short-fabric.fabric-summary-view', [
            'fabricBookings' => $fabricBookings['fabricBookings'],
        ]);
    }

    public function summaryPdf($id)
    {
        $fabricBookings = FabricBookingReportService::shortFabricData($id);
        $signature = ReportSignatureService::getApprovalSignature(ShortFabricBooking::class, $id);
        $pdf = PDF::loadView('merchandising::fabric-bookings.fabric-summary-pdf', [
            'header-html' => view('skeleton::pdf.header'),
            'fabricBookings' => $fabricBookings['fabricBookings'],
            'signature' => $signature
        ])->setPaper('a4', 'landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer', compact('signature')),
        ]);

        return $pdf->stream("{$id}_short_fabric_summary_report.pdf");
    }
}
