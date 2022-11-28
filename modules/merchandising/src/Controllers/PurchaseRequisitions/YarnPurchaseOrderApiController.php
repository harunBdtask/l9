<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\PurchaseRequisitions;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions\YarnPurchaseRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Requests\YarnPurchaseOrderRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder\IndependentBasisEditFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder\RequisitionBasisEditFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder\StyleBasisEditFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder\YarnPurchaseOrderDetailsStrategy;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;
use Symfony\Component\HttpFoundation\Response;

class YarnPurchaseOrderApiController extends Controller
{
    public function store(YarnPurchaseOrderRequest $request): JsonResponse
    {

        try {
            $order = new YarnPurchaseOrder();
            $order['is_approved'] = 0;
            $order->fill($request->all())->save();

            return response()->json([
                'message' => 'Saved Successfully!',
                'data' => $order,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return response()->json(
                ['message' => 'Failed!', 'info' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function edit($id): JsonResponse
    {
        $order = YarnPurchaseOrder::with([
            'details.unitOfMeasurement',
            'details.buyer',
            'details.yarnCount',
            'details.yarnComposition'
        ])->findOrFail($id);
        $details = [];
        if ($order['wo_basis'] == 1) {
            $details = RequisitionBasisEditFormatter::format($order);
        } elseif ($order['wo_basis'] == 2) {
            $details = StyleBasisEditFormatter::format($order);
        } elseif ($order['wo_basis'] == 4) {
            $details = IndependentBasisEditFormatter::format($order);
        }
        unset($order->details);
        $order['details'] = $details;
        return response()->json($order);
    }

    public function update(YarnPurchaseOrderRequest $request, YarnPurchaseOrder $order): JsonResponse
    {
        try {
            $order->fill($request->all())->save();
            return response()->json(['message' => 'Updated Successfully!', 'data' => $order], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function requisitionSearch(Request $request): JsonResponse
    {
        $requisition = YarnPurchaseRequisition::query()
            ->where('requisition_no', 'LIKE', "%{$request->get('search')}%")
            ->get()->map(function ($item) {
                $data['id'] = $item['id'];
                $data['text'] = $item['requisition_no'];
                return $data;
            });
        return response()->json($requisition);
    }

    public function detailsSearch(Request $request): JsonResponse
    {
        try {
            $type = $request->get('type');
            $data = (new YarnPurchaseOrderDetailsStrategy)->setStrategy($type)->search($request);
            return response()->json(['message' => 'Details Fetch Successful!', 'data' => $data], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::info('YarnPurchaseOrder Search: ' . $e->getMessage() . ':' . $e->getLine());
            return response()->json(['message' => 'Failed!', 'info' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function orderDetailsCreateUpdate(Request $request, $id): JsonResponse
    {
        try {
            $details = $request->all();
            foreach ($details as $detail) {
                $detail['yarn_purchase_order_id'] = $id;
                YarnPurchaseOrderDetail::query()->updateOrCreate(['id' => $detail['id'] ?? null], $detail);
            }
            return response()->json([
                'message' => 'Successfully Updated',
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'data' => null,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function orderDetailsDelete(YarnPurchaseOrderDetail $detail): JsonResponse
    {
        try {
            $detail->delete();

            return response()->json(['message' => 'Deleted Successfully!'], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::info('YarnPurchaseOrder Delete: ' . $e->getMessage() . ':' . $e->getLine());

            return response()->json(['message' => 'Failed!', 'info' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getStyles(Request $request): JsonResponse
    {
        $buyerId = $request->get('buyer_id');
        $styles = Order::query()
            ->where('buyer_id', $buyerId)
            ->get(['id', 'style_name', 'job_no'])
            ->map(function ($collection) {
                return [
                    'id' => $collection->style_name,
                    'text' => $collection->style_name,
                    'job_no' => $collection->job_no,
                ];
            });

        return response()->json([
            'data' => $styles,
            'message' => 'Successfully style fetched',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function getYarnCounts(): JsonResponse
    {
        $yarnCounts = YarnCount::query()->get(['id', 'yarn_count as text']);

        return response()->json([
            'data' => $yarnCounts,
            'message' => 'Successfully yarn counts fetched',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function getYarnCompositions(): JsonResponse
    {
        $yarnCompositions = YarnComposition::query()->get(['id', 'yarn_composition as text']);

        return response()->json([
            'data' => $yarnCompositions,
            'message' => 'Successfully yarn compositions fetched',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function getYarnTypes(): JsonResponse
    {
        $yarnTypes = CompositionType::query()->get(['id', 'name as text']);

        return response()->json([
            'data' => $yarnTypes,
            'message' => 'Successfully yarn types fetched',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
