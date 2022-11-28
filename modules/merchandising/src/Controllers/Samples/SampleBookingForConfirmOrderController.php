<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Samples;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleBookingConfirmOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleBookingConfirmOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use SkylarkSoft\GoRMG\Merchandising\Requests\SampleBookingForConfirmOrderDetailRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\SampleBookingServiceConfirmOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricInfo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use Symfony\Component\HttpFoundation\Response;
use PDF;

class SampleBookingForConfirmOrderController extends SampleBookingController
{

    public function __construct()
    {
        $this->sampleBookingService = new SampleBookingServiceConfirmOrder();
        $this->types = ['after_order'];
    }

    public function index()
    {
        $paginateNumber = request('paginateNumber') ?? 15;
        $searchedOrders = 15;
        $bookings = $this->sampleBookingService->fetchList();
        $totalConfirmedBookings  = $bookings->total();
           
        $dashboardOverview = [
            "Total Confirmed Booking" => $totalConfirmedBookings
        ];
        return view('merchandising::sample-booking.confirm-order-index', compact('bookings','dashboardOverview','paginateNumber','searchedOrders'));
    }


    public function storeDetails(SampleBookingForConfirmOrderDetailRequest $request, SampleBookingConfirmOrder $sampleBookingConfirmOrder): JsonResponse
    {
        try {
            \DB::beginTransaction();
            foreach ($request->all() as $item) {

                if ($id = $item['id'] ?? null) {
                    $sampleBookingConfirmOrder->details()->find($id)->update($item);
                    continue;
                }

                $sampleBookingConfirmOrder->details()->create($item);
            }
            \DB::commit();
            $this->response['message'] = 'Successfully Saved!';
            $this->response['details'] = $sampleBookingConfirmOrder->details()->get();

            return response()->json($this->response);

        } catch (\Throwable $e) {
            $this->response['message'] = 'Something Went Wrong';
            $this->response['errMsg'] = $e->getMessage();
            $this->statusCode = Response::HTTP_BAD_REQUEST;

            return response()->json($this->response, $this->statusCode);
        }
    }

    public function show(SampleBookingConfirmOrder $sampleBookingConfirmOrder): JsonResponse
    {
        return response()->json([
            'booking' => $sampleBookingConfirmOrder->toArray()
        ]);
    }

    public function details(SampleBookingConfirmOrder $sampleBookingConfirmOrder): JsonResponse
    {
        $order = Order::with('purchaseOrders')
            ->where('style_name', $sampleBookingConfirmOrder->style_name)
            ->first();

        if (!$order) {
            return response()->json(['details' => [], 'purchaseOrders' => []]);
        }

        $purchaseOrders = PurchaseOrder::where('order_id', $order->id)
            ->select('id', 'po_no as text')
            ->get();

        $details = $this->detailsFormat($sampleBookingConfirmOrder);

        return response()->json(compact('details', 'purchaseOrders'));
    }

    private function detailsFormat($sampleBookingConfirmOrder)
    {
        return $sampleBookingConfirmOrder->details()->get()->map(function ($detail) {
            return [
                'id' => $detail['id'],
                'requisition_id' => $detail->requisition_id,
                'requisition_detail_id' => $detail->requisition_detail_id,
                'po_id' => $detail->po_id,
                'po_value' => PurchaseOrder::query()->whereIn('id', $detail->po_id)->pluck('po_no')->implode(', '),
                'sample_id' => $detail->sample_id,
                'sample_name' => GarmentsSample::whereIn('id', $detail->sample_id)->pluck('name')->implode(', '),
                'gmts_item_id' => $detail->gmts_item_id,
                'gmts_item' => $detail->gmtsItem->name,
                'body_part_id' => $detail->body_part_id,
                'body_part' => $detail->bodyPart->name,
                'body_part_type' => $detail->bodyPart->type,
                'fabric_nature_id' => $detail->fabric_nature_id,
                'fabric_nature' => $detail->fabricNature->name,
                'gmts_color_id' => $detail->gmts_color_id,
                'gmts_color' => Color::find($detail->gmts_color_id)->name,
                'color_type_id' => $detail->color_type_id,
                'color_type' => $detail->colorType->name,
                'fabric_description_id' => $detail->fabric_description_id,
                'fabric_description' => '',
                'fabric_source_id' => $detail->fabric_source_id,
                'fabric_source' => $detail->fabric_source_value,
                'dia' => $detail['dia'],
                'gsm' => $detail->gsm,
                'uom_id' => $detail->uom_id,
                'uom' => FabricInfo::getUnit($detail->uom_id),
                'required_qty' => $detail['required_qty'],
                'process_loss' => $detail['process_loss'],
                'rate' => $detail['rate'],
                'total_qty' => $detail['total_qty'],
                'amount' => $detail['amount'],
                'remarks' => $detail->remarks
            ];
        });
    }

    public function searchRequisition(Request $request): JsonResponse
    {
        $request->validate(['factory_id' => 'required', 'buyer_id' => 'required']);
        $requisitions = $this->searchRequisitionData($request);
        return response()->json($requisitions);
    }


    public function sampleRequisitionDetails(Request $request)
    {
        $requisitionId = $request->id;
        $details = $this->getDetails(SampleRequisition::find($requisitionId));
        $formattedDetails = [];
        $styleName = $request->get('style_name');

        $order = Order::with('purchaseOrders')
            ->where('style_name', $styleName)
            ->first();


        $purchaseOrders = PurchaseOrder::where('order_id', $order->id ?? null)
            ->select('id', 'po_no as text')
            ->get();

        foreach ($details as $detail) {

            foreach ($detail->details as $color) {
                $formattedDetails[] = [
                    'requisition_id' => $detail->requisition_id,
                    'requisition_detail_id' => $detail->id,
                    'po_id' => [],
                    'sample_id' => $color['sample_id'],
                    'sample_name' => GarmentsSample::whereIn('id', $color['sample_id'])->pluck('name')->implode(', '),
                    'gmts_item_id' => $detail->gmts_item_id,
                    'gmts_item' => $detail->gmtsItem->name,
                    'body_part_id' => $detail->body_part_id,
                    'body_part' => $detail->bodyPart->name,
                    'body_part_type' => $detail->bodyPart->type,
                    'fabric_nature_id' => $detail->fabric_nature_id,
                    'fabric_nature' => $detail->fabricNature->name,
                    'gmts_color_id' => $color['color_id'],
                    'gmts_color' => Color::find($color['color_id'])->name,
                    'color_type_id' => $detail->color_type_id,
                    'color_type' => $detail->colorType->name,
                    'fabric_description_id' => $detail->fabric_description_id,
                    'fabric_description' => '',
                    'fabric_source_id' => $detail->fabric_source_id,
                    'fabric_source' => '',
                    'dia' => $color['dia'],
                    'gsm' => $detail->gsm,
                    'uom_id' => $detail->uom_id,
                    'uom' => FabricInfo::getUnit($detail->uom_id),
                    'required_qty' => $color['finish_qty'],
                    'process_loss' => $color['process_loss'],
                    'rate' => $color['rate'],
                    'total_qty' => $color['grey_qty'] * 1,
                    'amount' => $color['grey_qty'] * $color['rate'],
                    'remarks' => null
                ];
            }

        }

        return response()->json(['details' => $formattedDetails, 'purchaseOrders' => $purchaseOrders]);
    }

    /**
     * @throws \Throwable
     */
    public function delete(SampleBookingConfirmOrder $sampleBookingConfirmOrder): JsonResponse
    {
        try {
            \DB::beginTransaction();
            $sampleBookingConfirmOrder->details()->delete();
            $sampleBookingConfirmOrder->delete();
            \DB::commit();
            return response()->json(['message' => 'Successfully Deleted!']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => 'Something Went Wrong!']);

        }
    }

    public function deleteDetail(SampleBookingConfirmOrderDetail $detail): JsonResponse
    {
        $detail->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }

    public function view($id)
    {
        $sampleBookingConfirmOrder = $this->bookingData($id)['main'];
        $sampleBookingConfirmOrderDetails = $this->bookingData($id)['details'];
        return view('merchandising::sample-booking.confirm-order-view', compact('sampleBookingConfirmOrder', 'sampleBookingConfirmOrderDetails'));
    }

    private function bookingData($id): array
    {
        $sampleBookingConfirmOrder = SampleBookingConfirmOrder::query()
            ->where('id', $id)
            ->with([
                'supplier:id,name,address_1',
                'fabricNature',
                'dealingMerchant',
            ])
            ->firstOrFail();

        return ['main' => $sampleBookingConfirmOrder, 'details' => $this->detailsFormat($sampleBookingConfirmOrder)];
    }

    public function pdf($id)
    {
        $sampleBookingConfirmOrder = $this->bookingData($id)['main'];
        $sampleBookingConfirmOrderDetails = $this->bookingData($id)['details'];
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::sample-booking.pdf',
            compact('sampleBookingConfirmOrder', 'sampleBookingConfirmOrderDetails')
        )->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream("{$id}_sample-booking-confirm-order.pdf");
    }
}
