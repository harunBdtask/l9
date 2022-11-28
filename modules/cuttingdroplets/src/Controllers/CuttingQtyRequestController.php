<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Handlers\HandleCuttingQtyRequestNotification;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\CuttingQtyRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\Response;

class CuttingQtyRequestController extends Controller
{
    public function index()
    {
        return view('cuttingdroplets::forms.cutting_qty_request_form');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function searchMatrix(Request $request): JsonResponse
    {
        $poId = $request->get('po_id');
        $itemId = $request->get('item_id');
        $colorId = $request->get('color_id');
        $cuttingQtyRequest = null;
        $particulars = [PurchaseOrder::QTY, PurchaseOrder::EX_CUT, PurchaseOrder::PLAN_CUT_QTY, 'Additional Ex. Cut%', 'Additional % QTY'];
        if ($request->get('type') == 'approval') {
            $cuttingQtyRequest = CuttingQtyRequest::query()
                ->where([
                    'item_id' => $itemId,
                    'po_id' => $poId,
                    'color_id' => $colorId,
                    'is_approved' => 0,
                ])->first() ?? null;

            if (empty($cuttingQtyRequest)) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'No cutting qty request available.'
                ], Response::HTTP_OK);
            }

        }

        $matrix = PoColorSizeBreakdown::query()
            ->where(
                [
                    'purchase_order_id' => $poId,
                    'garments_item_id' => $itemId,
                ])
            ->first()->quantity_matrix;
        $matrix = collect($matrix)->where('color_id', $colorId);
        $sizes = $matrix->pluck('size', 'size_id')->toArray();
        $formattedMatrix = [];

        foreach ($particulars as $particular) {
            $collection = collect($matrix)->where('particular', $particular);
            $formattedMatrix[$particular] = $this->formatParticular($particular, $sizes, $collection);
        }

        if (isset($cuttingQtyRequest->id)) {
            $formattedMatrix['Additional Ex. Cut%'] = $cuttingQtyRequest->additional_ex_cut;
            $formattedMatrix['Additional % QTY'] = $cuttingQtyRequest->additional_cut_qty;
        }

        $data = [
            'particulars' => $particulars,
            'matrix' => $formattedMatrix,
            'sizes' => $sizes,
            'remarks' => $cuttingQtyRequest->remarks ?? '',
            'cuttingQtyRequestId' => $cuttingQtyRequest->id ?? '',
        ];
        return response()->json([
            'data' => $data,
            'message' => 'PO Qty matrix fetched successfully.',
            'status' => Response::HTTP_OK,
        ]);
    }

    private function formatParticular($particular, $sizes, $collection = []): array
    {
        if ($collection) {
            $total = $particular == PurchaseOrder::EX_CUT
                ? collect($collection)->avg('value')
                : collect($collection)->sum('value');
        } else {
            $total = 0;
        }

        $data = [
            'particular' => $particular,
            'total' => $total,
            'sizes' => [],
        ];

        foreach ($sizes as $sizeId => $size) {
            $sizeCollection = collect($collection)
                ->where('size_id', $sizeId)
                ->first() ?? [];

            $data['sizes'][$size] = $sizeCollection ? $sizeCollection['value'] : 0;
        }

        return $data;
    }

    /**
     * @param Request $request
     * @param CuttingQtyRequest $cuttingQtyRequest
     * @return JsonResponse
     */
    public function store(Request $request, CuttingQtyRequest $cuttingQtyRequest): JsonResponse
    {
        $request->validate([
            'remarks' => 'required',
        ]);

        try {
            $cuttingQtyRequest->fill($request->all())->save();
            $cuttingQtyRequest->load(['buyer', 'order', 'color', 'purchaseOrder:id,po_no']);
            HandleCuttingQtyRequestNotification::for($cuttingQtyRequest)->notify();

            return response()->json([
                'data' => $cuttingQtyRequest,
                'status' => Response::HTTP_CREATED,
                'message' => 'Cutting qty request sent successfully.',
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Something went wrong.'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
