<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\Response;

class SmvController extends Controller
{

    public function showSmv()
    {
        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->all();

        return view(PackageConst::PACKAGE_NAME . '::forms.smv')->with('buyers', $buyers);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSmvOrders(Request $request): JsonResponse
    {
        $month = $request->get('month');
        $year = $request->get('year');
        $buyer_id = $request->get('buyer_id');
        $order_id = $request->get('order_id');
        $startOfMonth = Carbon::make($year . '-' . $month)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::make($year . '-' . $month)->endOfMonth()->toDateString();

        $orderIds = PurchaseOrder::query()
            ->select('ex_factory_date', 'order_id')
            ->whereDate('ex_factory_date', '>=', $startOfMonth)
            ->whereDate('ex_factory_date', '<=', $endOfMonth)
            ->when($order_id,
                function ($query) use ($order_id) {
                    $query->where('order_id', $order_id);
                },
                function ($query) use ($buyer_id) {
                    $query->where('buyer_id', $buyer_id);
                })
            ->orderBy('ex_factory_date', 'ASC')
            ->distinct('order_id')
            ->pluck('order_id')
            ->toArray();

        $smvList = [];
        Order::query()
            ->select(['id', 'factory_smv', 'item_details', 'buyer_id', 'style_name'])
            ->with('buyer')
            ->whereIn('id', $orderIds)
            ->get()
            ->map(function ($orderData) use (&$smvList) {

                return collect($orderData['item_details']['details'] ?? [])
                    ->flatMap(function ($item, $key) use ($orderData, &$smvList) {
                        $smvList[] = [
                            'buyer' => $orderData->buyer->name,
                            'style_name' => $orderData->style_name,
                            'item_name' => $item['item_name'],
                            'item_id' => $item['item_id'],
                            'order_smv' => $item['item_smv'] ?? 0,
                            'factory_smv' => $orderData['factory_smv']['details'][$key]['item_smv'] ?? $item['item_smv'] ?? 0,
                            'order_id' => $orderData['id'],
                            'remarks' => $orderData['factory_smv']['details'][$key]['remarks'] ?? ''
                        ];
                    })->toArray();

            });
        return response()->json($smvList);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateOrderSmv(Request $request, $id): JsonResponse
    {

        try {
            $factory_smv = $request->get('factory_smv');
            $remarks = $request->get('remarks');
            $itemId = $request->get('itemId');

            $order = Order::query()->findOrFail($id);
            $details = $order->factory_smv ?? $order->item_details;
            $details['details'] = collect($details['details'])
                ->map(function ($data) use ($itemId, $factory_smv, $remarks) {
                    if ($itemId === $data['item_id']) {
                        $data['item_smv'] = $factory_smv;
                        $data['remarks'] = $remarks;
                    }
                    return $data;
                });

            $order->factory_smv = $details;
            $order->save();

            return response()->json([
                'data' => $order->factory_smv,
                'code' => Response::HTTP_OK,
                'message' => 'Successfully Factory SMV Updated'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

}
