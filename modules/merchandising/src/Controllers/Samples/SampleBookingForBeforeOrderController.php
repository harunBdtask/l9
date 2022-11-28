<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Samples;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleBookingBeforeOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleBookingBeforeOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use SkylarkSoft\GoRMG\Merchandising\Requests\SampleBookingForBeforeOrderDetailRequest;
use SkylarkSoft\GoRMG\Merchandising\Requests\SampleBookingForConfirmOrderDetailRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\SampleBookingServiceBeforeOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricInfo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use Symfony\Component\HttpFoundation\Response;

class SampleBookingForBeforeOrderController extends SampleBookingController
{
    public function __construct()
    {
        $this->sampleBookingService = new SampleBookingServiceBeforeOrder();
        $this->types = ['before_order', 'rnd'];
    }

    public function index()
    {
        $bookings = $this->sampleBookingService->fetchList();
        $totalOrders = SampleBookingBeforeOrder::all()->count();
           
            $dashboardOverview = [
                "Total Orders" => $totalOrders
            ];
        return view('merchandising::sample-booking.before-order-index', compact('bookings','dashboardOverview'));
    }

    public function storeDetails(SampleBookingForBeforeOrderDetailRequest $request, SampleBookingBeforeOrder $sampleBooking): JsonResponse
    {
        try {
            \DB::beginTransaction();
            foreach ($request->all() as $item) {

                if ( $id = $item['id'] ?? null ) {
                    $sampleBooking->details()->find($id)->update($item);
                    continue;
                }

                $sampleBooking->details()->create($item);
            }
            \DB::commit();
            $this->response['message'] = 'Successfully Saved!';
            $this->response['details'] = $sampleBooking->details()->get();

            return response()->json($this->response);

        } catch (\Throwable $e) {
            $this->response['message'] = 'Something Went Wrong';
            $this->response['errMsg'] = $e->getMessage();
            $this->statusCode = Response::HTTP_BAD_REQUEST;

            return response()->json($this->response, $this->statusCode);
        }
    }

    public function show(SampleBookingBeforeOrder $sampleBooking): JsonResponse
    {
        return response()->json([
            'booking' => $sampleBooking->toArray()
        ]);
    }

    public function details(SampleBookingBeforeOrder $sampleBooking): JsonResponse
    {
        $purchaseOrders = [];

        $details = $sampleBooking->details()->get()->map(function ($detail) {
            return [
                'id'                    => $detail['id'],
                'requisition_id'        => $detail->requisition_id,
                'requisition_detail_id' => $detail->requisition_detail_id,
                'po_id'                 => $detail->po_id,
                'sample_id'             => $detail->sample_id,
                'sample_name'           => GarmentsSample::whereIn('id', $detail->sample_id)->pluck('name')->implode(', '),
                'gmts_item_id'          => $detail->gmts_item_id,
                'gmts_item'             => $detail->gmtsItem->name,
                'body_part_id'          => $detail->body_part_id,
                'body_part'             => $detail->bodyPart->name,
                'body_part_type'        => $detail->bodyPart->type,
                'fabric_nature_id'      => $detail->fabric_nature_id,
                'fabric_nature'         => $detail->fabricNature->name,
                'gmts_color_id'         => $detail->gmts_color_id,
                'gmts_color'            => Color::find($detail->gmts_color_id)->name,
                'color_type_id'         => $detail->color_type_id,
                'color_type'            => $detail->colorType->name,
                'fabric_description_id' => $detail->fabric_description_id,
                'fabric_description'    => '',
                'fabric_source_id'      => $detail->fabric_source_id,
                'fabric_source'         => $detail->fabric_source_value,
                'dia'                   => $detail['dia'],
                'gsm'                   => $detail->gsm,
                'uom_id'                => $detail->uom_id,
                'uom'                   => FabricInfo::getUnit($detail->uom_id),
                'required_qty'          => $detail['required_qty'],
                'process_loss'          => $detail['process_loss'],
                'rate'                  => $detail['rate'],
                'total_qty'             => $detail['total_qty'],
                'amount'                => $detail['amount'],
                'remarks'               => $detail->remarks
            ];
        });

        return response()->json(compact('details', 'purchaseOrders'));
    }

    public function searchRequisition(Request $request): JsonResponse
    {
        $request->validate(['factory_id' => 'required', 'buyer_id' => 'required']);

        $requisitions = $this->searchRequisitionData($request);

        return response()->json($requisitions);
    }


    public function sampleRequisitionDetails(Request $request): JsonResponse
    {
        $requisitionId = $request->id;
        $details = $this->getDetails(SampleRequisition::find($requisitionId));

        $formattedDetails = [];
        $purchaseOrders = [];

        foreach ($details as $detail) {

            foreach ($detail->details as $color) {
                $formattedDetails[] = [
                    'requisition_id'        => $detail->requisition_id,
                    'requisition_detail_id' => $detail->id,
                    'po_id'                 => [],
                    'sample_id'             => $color['sample_id'],
                    'sample_name'           => GarmentsSample::whereIn('id', $color['sample_id'])->pluck('name')->implode(', '),
                    'gmts_item_id'          => $detail->gmts_item_id,
                    'gmts_item'             => $detail->gmtsItem->name,
                    'body_part_id'          => $detail->body_part_id,
                    'body_part'             => $detail->bodyPart->name,
                    'body_part_type'        => $detail->bodyPart->type,
                    'fabric_nature_id'      => $detail->fabric_nature_id,
                    'fabric_nature'         => $detail->fabricNature->name,
                    'gmts_color_id'         => $color['color_id'],
                    'gmts_color'            => Color::find($color['color_id'])->name,
                    'color_type_id'         => $detail->color_type_id,
                    'color_type'            => $detail->colorType->name,
                    'fabric_description_id' => $detail->fabric_description_id,
                    'fabric_description'    => '',
                    'fabric_source_id'      => $detail->fabric_source_id,
                    'fabric_source'         => '',
                    'dia'                   => $color['dia'],
                    'gsm'                   => $detail->gsm,
                    'uom_id'                => $detail->uom_id,
                    'uom'                   => FabricInfo::getUnit($detail->uom_id),
                    'required_qty'          => $color['finish_qty'],
                    'process_loss'          => $color['process_loss'],
                    'rate'                  => $color['rate'],
                    'total_qty'             => $color['grey_qty'] * 1,
                    'amount'                => $color['grey_qty'] * $color['rate'],
                    'remarks'               => null
                ];
            }

        }

        return response()->json(['details' => $formattedDetails, 'purchaseOrders' => $purchaseOrders]);
    }

    /**
     * @throws \Throwable
     */
    public function delete(SampleBookingBeforeOrder $sampleBooking): JsonResponse
    {
        try {
            \DB::beginTransaction();
            $sampleBooking->details()->delete();
            $sampleBooking->delete();
            \DB::commit();
            return response()->json(['message' => 'Successfully Deleted!']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => 'Something Went Wrong!']);

        }
    }

    public function deleteDetail(SampleBookingBeforeOrderDetail $detail): JsonResponse
    {
        $detail->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}