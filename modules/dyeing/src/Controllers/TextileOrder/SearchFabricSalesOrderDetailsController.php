<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\TextileOrder;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrderDetail;
use Symfony\Component\HttpFoundation\Response;

class SearchFabricSalesOrderDetailsController extends Controller
{

    /**
     * @param int $fabricSaleOrderId
     * @return JsonResponse
     */
    public function __invoke(int $fabricSaleOrderId): JsonResponse
    {
        try {
            $fabricSaleOrderDetails = FabricSalesOrderDetail::query()
                ->with(['programUOM', 'bodyPart', 'color', 'colorType'])
                ->where('fabric_sales_order_id', $fabricSaleOrderId)
                ->get()->map(function ($collection) {

                    $totalValue = number_format($collection->gray_qty * $collection->average_price,
                        4, '.',
                        '');

                    return [
                        'id' => null,
                        'unique_id' => null,
                        'textile_order_id' => null,
                        'sub_textile_operation_id' => null,
                        'sub_textile_process_id' => null,
                        'operation_description' => null,
                        'fabric_sales_order_detail_id' => $collection->id,
                        'body_part_id' => $collection->body_part_id,
                        'body_part_value' => $collection->bodyPart->name,
                        'fabric_composition_id' => $collection->fabric_composition_id,
                        'fabric_composition_value' => $collection->fabric_composition_value,
                        'fabric_type_id' => null,
                        'item_color_id' => $collection->item_color_id,
                        'item_color' => $collection->color->name,
                        'gmt_color_id' => $collection->gmt_color_id,
                        'ld_no' => $collection->ld_no,
                        'color_type_id' => $collection->color_type_id,
                        'color_type' => $collection->colorType->color_types,
                        'finish_dia' => $collection->fabric_dia,
                        'dia_type_id' => $collection->dia_type_id,
                        'dia_type_value' => $collection->dia_type_value,
                        'gsm' => $collection->fabric_gsm,
                        'yarn_details' => null,
                        'customer_buyer' => $collection->cus_buyer,
                        'customer_style' => $collection->cus_style,
                        'order_qty' => $collection->gray_qty,
                        'uom_id' => $collection->prog_uom,
                        'uom' => $collection->programUOM->unit_of_measurement,
                        'price_rate' => $collection->average_price,
                        'total_value' => $totalValue,
                        'conv_rate' => null,
                        'total_amount_bdt' => null,
                        'delivery_date' => null,
                        'remarks' => null,
                    ];
                });

            return response()->json([
                'message' => 'Fetch fabric sales order details successfully',
                'data' => $fabricSaleOrderDetails,
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
