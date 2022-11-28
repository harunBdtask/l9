<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatchDetail;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrderDetail;

class SearchTextileOrderDetailsApiController extends Controller
{

    /**
     * @param $textileOrderId
     * @return JsonResponse
     */
    public function __invoke($textileOrderId)
    {
        try {
            $textileOrder = TextileOrder::query()->find($textileOrderId);
            $textileOrderDetails = TextileOrderDetail::query()
                ->where('textile_order_id', $textileOrderId)
                ->get()->map(function ($collection) {

                    $previousBatchDetails = DyeingBatchDetail::query()
                        ->where('textile_order_detail_id', $collection->id)
                        ->get();

                    $prevBatchRoll = $previousBatchDetails->sum('batch_roll');

                    $prevBatchWeight = $previousBatchDetails->sum('batch_weight');

                    return [
                        'id' => null,
                        'textile_order_id' => $collection->textile_order_id,
                        'textile_order_no' => $collection->unique_id,
                        'textile_order_detail_id' => $collection->id,
                        'sub_textile_operation_id' => $collection->sub_textile_operation_id,
                        'sub_textile_process_id' => $collection->sub_textile_process_id,
                        'fabric_composition_id' => $collection->fabric_composition_id,
                        'fabric_type_id' => $collection->fabric_type_id,
                        'fabric_type_value' => $collection->fabricType->name,
                        'body_part_id' => $collection->body_part_id,
                        'color_id' => $collection->item_color_id,
                        'color' => $collection->color->name,
                        'ld_no' => $collection->ld_no,
                        'color_type_id' => $collection->color_type_id,
                        'finish_dia' => $collection->finish_dia,
                        'dia_type_id' => $collection->dia_type_id,
                        'dia_type_value' => $collection->dia_type_value,
                        'gsm' => $collection->gsm,
                        'fabric_description' => $collection->fabric_composition_value,
                        'yarn_details' => $collection->yarn_details,
                        'uom_id' => $collection->uom_id,
                        'stitch_length' => null,
                        'prev_batch_roll' => $prevBatchRoll,
                        'batch_roll' => null,
                        'order_qty' => $collection->order_qty,
                        'prev_batch_weight' => $prevBatchWeight,
                        'batch_weight' => null,
                        'remarks' => null,
                    ];
                });

            return response()->json([
                'message' => 'Fetch order details successfully',
                'sales_order_id' => $textileOrder->fabric_sales_order_id??null,
                'data' => $textileOrderDetails,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
