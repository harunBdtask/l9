<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Actions\FeatureVersionAction;
use SkylarkSoft\GoRMG\Merchandising\Events\OrderWiseBudgetUpdate;
use SkylarkSoft\GoRMG\Merchandising\Features;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\LeadTimeCalculator;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderService;
use SkylarkSoft\GoRMG\Merchandising\Services\PurchaseOrder\PODeleteAbleCheckingService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\TimeAndAction\Services\TNAReportService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PurchaseOrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @return JsonResponse
     */
    public function loadCommonData(): JsonResponse
    {
        $data['packing_ratio'] = $this->orderService->packing();
        $data['delay_for'] = $this->orderService->delayFor();
        $data['cut_offs'] = $this->orderService->cutOff();
        $data['garments_items'] = GarmentsItem::all();
        $data['countries'] = Country::all();
        $data['sizes'] = Size::all();
        $data['default_delivery_country'] = $this->defaultDeliveryCountry();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        // Data Save Or Update For Purchase Order
        $data = $request->except('poColorSizeBreakdown');
        $receivedDate = date_create($request->get('po_receive_date'));
        $shipDate = date_create($request->get('ex_factory_date'));
        $data['lead_time'] = $receivedDate->diff($shipDate)->format("%a");
        $data['production_lead_time'] = LeadTimeCalculator::calculate()->setLeadTime($data['lead_time'])->getProductionLeadTime();
        $data['pi_bunch_budget_date'] = LeadTimeCalculator::calculate()->setDate($receivedDate)->getPiBunchBudgetDate();
        $data['ex_bom_handover_date'] = LeadTimeCalculator::calculate()->setLeadTime($data['lead_time'])->setDate($shipDate)->getExBomHandOverDate();
        $po = PurchaseOrder::query()
            ->where('order_id', $request->get('order_id'))
            ->where('factory_id', $request->get('factory_id'))
            ->where('id', $request->get('id'))
            ->first();
        if ($po) {
            $data['is_locked'] = false;
            $po->update($data);
        } else {
            $po = PurchaseOrder::query()->create($data);
            $tnaService = new TNAReportService();
            $variable = $tnaService->variable($po->factory_id, $po->buyer_id)->variables_details;
            if ($variable && collect($variable)->get('tna_maintain') == 1) {
                $tnaService->dataAssignToReportTableForPOWise($po->id);
            }
        }
        // Data Save Or Update For Po Garment Color Size Breakdown
        $colorSizeDetails = $request->only('poColorSizeBreakdown');
        $poColorSizeBreakdowns = [];
        foreach ($colorSizeDetails['poColorSizeBreakdown']['itemQuantity'] as $quantity) {
            $poDetails['purchase_order_id'] = $po->id;
            $poDetails['factory_id'] = $request->get('factory_id');
            $poDetails['order_id'] = $request->get('order_id');
            $poDetails['buyer_id'] = $request->get('buyer_id');
            $poDetails['garments_item_id'] = $quantity['garment_item_id'];
            $colorIds = $colorSizeDetails['poColorSizeBreakdown']['colors'] ? collect($colorSizeDetails['poColorSizeBreakdown']['colors'])->where('garment_item_id', $quantity['garment_item_id'])->pluck('values')->flatten() : [];
            $sizeIds = $colorSizeDetails['poColorSizeBreakdown']['sizes'] ? collect($colorSizeDetails['poColorSizeBreakdown']['sizes'])->where('garment_item_id', $quantity['garment_item_id'])->pluck('values')->flatten() : [];
            $poDetails['colors'] = $colorIds;
            $poDetails['sizes'] = $sizeIds;
            $poDetails['quantity'] = $quantity['quantity'];
            $poDetails['item_wise_quantity'] = $quantity['item_wise_quantity'] ?? 0;
            $poDetails['color_types'] = $colorSizeDetails['poColorSizeBreakdown']['color_types'] ?? [];

            // checking po details is edit or not
            $poColorSizeBreakdown = PoColorSizeBreakdown::query()
                ->where('buyer_id', $poDetails['buyer_id'])
                ->where('factory_id', $poDetails['factory_id'])
                ->where('order_id', $poDetails['order_id'])
                ->where('garments_item_id', $poDetails['garments_item_id'])
                ->where('purchase_order_id', $poDetails['purchase_order_id'])
                ->first();

            if ($poColorSizeBreakdown) {
                $poColorSizeBreakdown->update($poDetails);
            } else {
                $poColorSizeBreakdown = PoColorSizeBreakdown::query()->create($poDetails);
            }
            $poColorSizeBreakdowns[] = $poColorSizeBreakdown;
        }

        return response()->json([
            'status' => 'Success',
            'data_type' => 'Color Size Breakdown',
            'colorSizeBreakdown' => $poColorSizeBreakdowns,
            'poDetails' => $po,
            'code' => Response::HTTP_OK,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getColors(Request $request): JsonResponse
    {
        $status = $request->get('team') == 1 ? 2 : 1;
        $colors = Color::query()->where('status', $status)->get();

        return response()->json($colors);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function loadOldData(Request $request): JsonResponse
    {
        $po = PurchaseOrder::with('poDetails')
            ->where('order_id', $request->get('orderId'))
            ->where('factory_id', $request->get('factoryId'))
            ->where('po_no', $request->get('po'))
            ->first();

        return response()->json([
            'status' => 'Success',
            'data_type' => 'Purchase Order',
            'data' => $po,
        ]);
    }

    /**
     * @param $orderId
     * @return JsonResponse
     */
    public function loadOrderDependentData($orderId): JsonResponse
    {
        $garmentIds = collect(Order::query()
                ->find($orderId)->item_details['details'] ?? [])->pluck('item_id');
        $garments = GarmentsItem::query()->whereIn('id', $garmentIds)->get();

        return response()->json([
            'status' => 'Success',
            'type' => 'smv wise Garment Items',
            'data' => $garments,
        ]);
    }

    /**
     * @param Request $request
     * @param FeatureVersionAction $featureVersionAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function breakdownUpdate(Request $request, FeatureVersionAction $featureVersionAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $orderId = $request->get('order_id');
            $poId = PurchaseOrder::query()->where('order_id', $request->get('order_id'))
                ->where('po_no', $request->get('po_no'))
                ->first()->id;
            foreach ($request->get('particulars') as $particular) {
                $data['quantity_matrix'] = $particular['details'] ?? [];
                $data['ratio_matrix'] = collect($request->get('matrix'))->where('item_id', $particular['garment_item_id'])->values();
                PoColorSizeBreakdown::query()->where('order_id', $request->get('order_id'))
                    ->where('garments_item_id', $particular['garment_item_id'])
                    ->where('purchase_order_id', $poId)
                    ->update($data);
            }
            $featureVersionAction->handle(Features::ORDER, $orderId);
            DB::commit();

            return response()->json([
                'status' => 'Success',
                'type' => 'Breakdown Update',
                'queryString' => $request->all(),
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Success',
                'type' => 'Breakdown Update Failed',
                'queryString' => $request->all(),
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $poNo
     * @param $orderId
     * @param $poId
     * @return JsonResponse
     */
    public function uniquePOCheck($poNo, $orderId, $poId): JsonResponse
    {
        $purchaseOrder = PurchaseOrder::query()->where('order_id', $orderId)
            ->where('po_no', $poNo)
            ->where('id', '!=', $poId)
            ->first();

        return response()->json([
            'status' => 'Success',
            'type' => 'Unique PO Check',
            'data' => $purchaseOrder,
        ]);
    }

    /**
     * @param $poId
     * @return JsonResponse
     * @throws Throwable
     */
    public function copyPo($poId): JsonResponse
    {
        try {
            DB::beginTransaction();
//            Clone Purchase Order
            $purchaseOrder = PurchaseOrder::query()->findOrFail($poId);
            $newPurchaseOrder = $purchaseOrder->replicate();
            $newPurchaseOrder->po_no = PurchaseOrder::COPY_STRING;
            $newPurchaseOrder->copy_from = $poId;
            $newPurchaseOrder->ready_to_approved = 0;
            $newPurchaseOrder->is_approved = 0;
            $newPurchaseOrder->un_approve_request = null;
            $newPurchaseOrder->save();

//            Clone Color Size Breakdown
            $breakdowns = PoColorSizeBreakdown::query()->where('purchase_order_id', $poId)->get();
            foreach ($breakdowns as $breakdown) {
                $newBreakdown = $breakdown->replicate();
                $newBreakdown->purchase_order_id = $newPurchaseOrder->id;
                $newBreakdown->save();
            }
            DB::commit();

            return response()->json([
                'status' => 'Success',
                'type' => 'Unique PO Check',
                'data' => $newPurchaseOrder,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'type' => 'Unique PO Check Error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @param $poId
     * @return JsonResponse
     */
    public function deletePo($poId): JsonResponse
    {
        try {
            $purchaseOrder = PurchaseOrder::query()->findOrFail($poId);
            $purchaseOrderDeleteAbleResponse = (new PODeleteAbleCheckingService($purchaseOrder))->action();
            if ($purchaseOrderDeleteAbleResponse['delete_status']) {
                $purchaseOrder->delete();
            }
            return response()->json($purchaseOrderDeleteAbleResponse);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Error',
                'type' => 'PO Delete',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function defaultDeliveryCountry()
    {
        $defaultCountry = "UNITED STATES";
        return Country::query()->where('name', 'LIKE', "%$defaultCountry%")->first();
    }

    public function checkColorInBundleCurd($poId, $itemId, $colorId): JsonResponse
    {
        $bundleCard = BundleCard::query()
            ->where('purchase_order_id', $poId)
            ->where('garments_item_id', $itemId)
            ->where('color_id', $colorId)
            ->first();

        return response()->json([
            'message' => 'Fetch Color status',
            'data' => isset($bundleCard),
        ], Response::HTTP_OK);
    }

    public function checkSizeInBundleCurd($poId, $itemId, $sizeId): JsonResponse
    {
        $bundleCard = BundleCard::query()
            ->where('purchase_order_id', $poId)
            ->where('garments_item_id', $itemId)
            ->where('size_id', $sizeId)
            ->first();

        return response()->json([
            'message' => 'Fetch Size status',
            'data' => isset($bundleCard),
        ], Response::HTTP_OK);
    }
}
