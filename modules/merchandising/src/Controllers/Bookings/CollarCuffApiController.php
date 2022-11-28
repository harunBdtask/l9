<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\Response;

class CollarCuffApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $garment_item_id = $request->input('garment_item_id');
            $body_part_id = $request->input('body_part_id');
            $color_type_id = $request->input('color_type_id');
            $fabric_composition_id = $request->input('fabric_composition_id');
            $fabric_composition_value = $request->input('fabric_composition_value');
            $budget_id = $request->input('budget_id');
            $body_part_type = $request->input('body_part_type');
            $body_part_text = $request->input('text');
            $fabric_booking = Budget::with('fabricCosting')
                ->where('id', $budget_id)
                ->first();
            $fabric_form = $fabric_booking->fabricCosting->details['details']['fabricForm'];
            $fabric_data = $this->formatFabricDetails($fabric_form, $garment_item_id, $body_part_id, $color_type_id, $fabric_composition_id, $fabric_composition_value);
            $po_no = collect($fabric_data)->pluck('po_no');
            $purchase_order = PurchaseOrder::with('poDetails')
                ->whereIn('po_no', $po_no)
                ->get()->toArray();
            $data = collect($fabric_data)->map(function ($value) use ($purchase_order, $garment_item_id, $body_part_id, $color_type_id, $fabric_composition_id, $fabric_composition_value, $body_part_type, $body_part_text) {
                $item_color_details = collect($purchase_order)
                    ->where('po_no', $value['po_no'])
                    ->pluck('po_details')
                    ->flatten(1)
                    ->pluck('quantity_matrix')
                    ->flatten(1)
                    ->where('size_id', $value['size_id'])
                    ->where('color_id', $value['color_id'])
                    ->where('particular', 'Qty.')
                    ->pluck('value')->first();

                return collect($value)->only([
                    'po_no', 'color', 'color_id', 'size_id', 'size', 'item_size',
                ])->merge([
                    'garment_item_id' => $garment_item_id,
                    'body_part_id' => $body_part_id,
                    'color_type_id' => $color_type_id,
                    'fabric_composition_id' => $fabric_composition_id,
                    'fabric_composition_value' => $fabric_composition_value,
                    'body_part_type' => $body_part_type,
                    'body_part' => $body_part_text,
                    'qty' => $item_color_details,
                    'excess' => null,
                    'total_qty' => null,
                    'actual_work_order_qty' => null,
                ]);
            });
            $response = [
                'data' => $data,
                'message' => 'collar cuff data fetched',
                'status' => Response::HTTP_OK,

            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function formatFabricDetails($fabric_form, $garment_item_id, $body_part_id, $color_type_id, $fabric_composition_id, $fabric_composition_value): \Illuminate\Support\Collection
    {
        return collect($fabric_form)
            ->where('garment_item_id', $garment_item_id)
            ->where('body_part_id', $body_part_id)
            ->where('color_type_id', $color_type_id)
            ->where('fabric_composition_id', $fabric_composition_id)
            ->where('fabric_composition_value', $fabric_composition_value)
            ->pluck('greyConsForm.details')->flatten(1);
    }
}
