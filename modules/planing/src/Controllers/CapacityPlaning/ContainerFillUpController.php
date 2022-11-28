<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\CapacityPlaning;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Planing\Services\ContainerProfileService;
use SkylarkSoft\GoRMG\Planing\Services\PurchaseOrderService;
use Symfony\Component\HttpFoundation\Response;

class ContainerFillUpController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            if ($request->query('id')) {
                $containerProfiles = ContainerProfileService::containerProfilesEdit($request);
            } else {
                $containerProfiles = ContainerProfileService::containerProfiles($request);
            }

            $purchaseOrders = PurchaseOrderService::purchaseOrders($request);
            $itemKeys = [];

            foreach ($containerProfiles as $containerProfileKey => $containerProfile) {
                foreach ($purchaseOrders as $purchaseOrderKey => $purchaseOrder) {
                    if ($containerProfile['ex_factory_date'] === $purchaseOrder['ex_factory_date'] &&
                        $purchaseOrder['cbm'] > 0 &&
                        $purchaseOrder['cbm'] <= $containerProfile['balance']) {
                        $containerProfile['items'][] = $purchaseOrder;
                        $containerProfile['balance'] -= $purchaseOrder['cbm'];
                        array_unshift($itemKeys, $purchaseOrderKey);
                    }
                }

                $containerProfiles[$containerProfileKey] = $containerProfile;
                $this->removePurchaseOrders($itemKeys, $purchaseOrders);
            }

            return response()->json([
                'message' => 'Fetch purchase orders successfully',
                'data' => [
                    'container_profiles' => $containerProfiles,
                    'purchase_orders' => collect($purchaseOrders)->values(),
                ],
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removePurchaseOrders(&$itemKeys, &$purchaseOrders)
    {
        foreach ($itemKeys as $key) {
            unset($purchaseOrders[$key]);
        }

        $itemKeys = [];
    }
}
