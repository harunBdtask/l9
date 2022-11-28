<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssueDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrderDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\SubTextileOrderDetailRequest;
use Symfony\Component\HttpFoundation\Response;

class SubTextileOrderDetailController extends Controller
{
    public function index($subTextileOrderId): JsonResponse
    {
//        try {
        $data = SubTextileOrderDetail::query()
            ->where('sub_textile_order_id', $subTextileOrderId)
            ->get()
            ->map(function ($orderDetail) {
                return [
                    'id' => $orderDetail->id ?? null,
                    'uuid' => $orderDetail->uuid ?? null,
                    'factory_id' => $orderDetail->factory_id ?? null,
                    'supplier_id' => $orderDetail->supplier_id ?? null,
                    'party_name' => $orderDetail->supplier->name ?? null,
                    'order_no' => $orderDetail->order_no ?? null,
                    'sub_textile_order_id' => $orderDetail->sub_textile_order_id ?? null,
                    'sub_textile_operation_id' => $orderDetail->sub_textile_operation_id ?? null,
                    'sub_textile_operation_name' => $orderDetail->subTextileOperation->name ?? null,
                    'sub_textile_process_id' => $orderDetail->sub_textile_process_id ?? null,
                    'sub_textile_process_name' => $orderDetail->subTextileProcess->name ?? null,
                    'operation_description' => $orderDetail->operation_description ?? null,
                    'body_part_id' => $orderDetail->body_part_id ?? null,
                    'body_part_name' => $orderDetail->bodyPart->name ?? null,
                    'fabric_composition_id' => $orderDetail->fabric_composition_id ?? null,
                    'count_fabric_type' => null,
                    'fabric_type_id' => $orderDetail->fabric_type_id ?? null,
                    'fabric_type_name' => $orderDetail->fabricType->construction_name ?? null,
                    'color_id' => $orderDetail->color_id ?? null,
                    'color_name' => $orderDetail->color->name ?? null,
                    'ld_no' => $orderDetail->ld_no ?? null,
                    'color_type_id' => $orderDetail->color_type_id ?? null,
                    'color_type_name' => $orderDetail->colorType->color_types ?? null,
                    'finish_dia' => $orderDetail->finish_dia ?? null,
                    'dia_type_id' => $orderDetail->dia_type_id ?? null,
                    'dia_type' => $orderDetail->dia_type ?? null,
                    'gsm' => $orderDetail->gsm ?? null,
                    'fabric_description' => $orderDetail->fabric_description ?? null,
                    'yarn_details' => $orderDetail->yarn_details ?? null,
                    'yarn_description' => $orderDetail->yarn_details && is_array($orderDetail->yarn_details) && in_array('description', $orderDetail->yarn_details) ? $orderDetail->yarn_details['description'] : null,
                    'customer_buyer' => $orderDetail->customer_buyer ?? null,
                    'customer_style' => $orderDetail->customer_style ?? null,
                    'order_qty' => $orderDetail->order_qty ?? null,
                    'unit_of_measurement_id' => $orderDetail->unit_of_measurement_id ?? null,
                    'unit_of_measurement_name' => $orderDetail->unitOfMeasurement->unit_of_measurement ?? null,
                    'price_rate' => $orderDetail->price_rate ?? null,
                    'currency_id' => $orderDetail->currency_id ?? null,
                    'currency_name' => $orderDetail->currency->currency_name ?? null,
                    'total_value' => $orderDetail->total_value ?? null,
                    'conv_rate' => $orderDetail->conv_rate ?? null,
                    'total_amount_bdt' => $orderDetail->total_amount_bdt ?? null,
                    'delivery_date' => $orderDetail->delivery_date ?? null,
                    'remarks' => $orderDetail->remarks ?? null,
                    'created_by' => $orderDetail->created_by ?? null,
                    'updated_by' => $orderDetail->updated_by ?? null,
                    'deleted_by' => $orderDetail->deleted_by ?? null,
                    'created_at' => $orderDetail->created_at ?? null,
                    'updated_at' => $orderDetail->updated_at ?? null,
                ];
            });
        $status = Response::HTTP_OK;
        $message = "fetched successfully";
//        dd($data);
//        } catch (Exception $e) {
//            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
//            $message = \SOMETHING_WENT_WRONG;
//            $error = $e->getMessage();
//        }

        return response()->json([
            'data' => $data ?? null,
            'status' => $status,
            'message' => $message,
            'error' => $error ?? null,
        ], $status);
    }

    public function form()
    {
        return view('subcontract::textile_module.order_management_detail.form');
    }

    public function store(SubTextileOrderDetailRequest $request)
    {
        try {
            DB::beginTransaction();
            $subTextileOrderDetail = new SubTextileOrderDetail();
            $subTextileOrderDetail->fill($request->except('_token'));
            $subTextileOrderDetail->save();
            DB::commit();
            $data = $subTextileOrderDetail;
            $status = Response::HTTP_CREATED;
            $message = \S_SAVE_MSG;
        } catch (Exception $e) {
            DB::rollBack();
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }

        return response()->json([
            'data' => $data ?? null,
            'status' => $status,
            'message' => $message,
            'error' => $error ?? null,
        ], $status);
    }

    public function update(SubTextileOrderDetail $subTextileOrderDetail, SubTextileOrderDetailRequest $request)
    {
        try {
            DB::beginTransaction();
            $subTextileOrderDetail->fill($request->except('_token'));
            $subTextileOrderDetail->save();
            DB::commit();
            $data = $subTextileOrderDetail;
            $status = Response::HTTP_CREATED;
            $message = \S_SAVE_MSG;
        } catch (Exception $e) {
            DB::rollBack();
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }

        return response()->json([
            'data' => $data ?? null,
            'status' => $status,
            'message' => $message,
            'error' => $error ?? null,
        ], $status);
    }

    public function destroy(SubTextileOrderDetail $subTextileOrderDetail)
    {
        try {
            DB::beginTransaction();
            $subTextileOrderDetail->delete();
            DB::commit();
            $data = $subTextileOrderDetail;
            $status = Response::HTTP_OK;
            $message = \S_DEL_MSG;
        } catch (Exception $e) {
            DB::rollBack();
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }

        return response()->json([
            'data' => $data ?? null,
            'status' => $status,
            'message' => $message,
            'error' => $error ?? null,
        ], $status);
    }

    public function syncData(Request $request)
    {
        $orderDetails = $request->get('orderDetail');

        foreach ($orderDetails as $detail) {
            SubGreyStoreReceiveDetails::query()
                ->where('sub_textile_order_detail_id', $detail['id'])
                ->update([
                    'fabric_composition_id' => $detail['fabric_composition_id'],
                    'fabric_type_id' => $detail['fabric_type_id'],
                    'color_id' => $detail['color_id'],
                    'color_type_id' => $detail['color_type_id'],
                    'fabric_description' => $detail['fabric_description'],
                ]);

            SubGreyStoreIssueDetail::query()
                ->where('sub_textile_order_detail_id', $detail['id'])
                ->update([
                    'fabric_composition_id' => $detail['fabric_composition_id'],
                    'fabric_type_id' => $detail['fabric_type_id'],
                    'color_id' => $detail['color_id'],
                    'color_type_id' => $detail['color_type_id'],
                    'fabric_description' => $detail['fabric_description'],
                ]);
        }

        return response()->json([
            'message' => 'Data Sync Successfully',
            'status' => Response::HTTP_CREATED,
        ], Response::HTTP_CREATED);
    }
}
