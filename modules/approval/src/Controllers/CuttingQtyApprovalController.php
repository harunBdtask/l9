<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\CuttingQtyRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CuttingQtyApprovalController extends Controller
{
    public function index()
    {
        return view('approval::approvals.modules.cutting-qty-approval');
    }

    /**
     * @param CuttingQtyRequest $cuttingQtyRequest
     * @param Request $request
     * @return JsonResponse
     */
    public function store(CuttingQtyRequest $cuttingQtyRequest, Request $request): JsonResponse
    {
        $additionalQty = "Additional % QTY";
        $additionalExCut = "Additional Ex. Cut%";
        $matrix = $request->get('matrix');
        $poId = $request->get('po_id');
        $itemId = $request->get('item_id');
        $colorId = $request->get('color_id');

        try {
            DB::beginTransaction();
            $poColorSize = PoColorSizeBreakdown::query()
                ->where([
                    'purchase_order_id' => $poId,
                    'garments_item_id' => $itemId,
                ])
                ->first();

            $poColorSizeMatrix = collect($poColorSize->quantity_matrix)
                ->map(function ($collection) use ($colorId, $matrix, $additionalExCut, $additionalQty) {
                    if ($collection['color_id'] == $colorId) {
                        if ($collection['particular'] == PurchaseOrder::EX_CUT) {
                            $collection['value'] += $matrix[$additionalExCut]['sizes'][$collection['size']];
                        } else if ($collection['particular'] == PurchaseOrder::PLAN_CUT_QTY) {
                            $collection['value'] += $matrix[$additionalQty]['sizes'][$collection['size']];
                        }
                    }

                    return $collection;
                });

            $poColorSize->quantity_matrix = $poColorSizeMatrix;
            $poColorSize->save();
            $cuttingQtyRequest->update([
                'is_approved' => 1,
            ]);
            DB::commit();

            return response()->json([
                'status' => Response::HTTP_CREATED,
                'message' => 'Cutting qty approval updated successfully.'
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Something went wrong.'
            ]);
        }
    }

    /**
     * @param CuttingQtyRequest $cuttingQtyRequest
     * @return JsonResponse
     */
    public function rejectRequest(CuttingQtyRequest $cuttingQtyRequest): JsonResponse
    {
        $cuttingQtyRequest->update(['is_approved' => 2]);

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Cutting qty request rejected successfully.',
        ]);
    }
}
