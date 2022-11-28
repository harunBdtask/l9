<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;

class CommonController extends Controller
{
    public const ACTIVE_STATUS = 1;
    public const INACTIVE_STATUS = 0;
    public const STATUS_OPTIONS = [
        '1' => 'Active',
        '0' => 'InActive',
    ];

    public function getGarmentProductionVariable(): JsonResponse
    {
        $variable = GarmentsProductionEntry::query()->where('factory_id', factoryId())->first()['entry_method'] ?? 1;
        return response()->json((int)$variable);
    }

    public function getCompanies(): JsonResponse
    {
        $factories = Factory::all([
            'id',
            'factory_name as text',
            'factory_address as location',
        ]);

        return response()->json($factories);
    }

    public function getBuyers($id): JsonResponse
    {
        $buyers = Buyer::query()->where('factory_id', $id)->get([
            'id',
            'name as text',
        ]);
        return response()->json($buyers);
    }

    public function subcontractCompanies(Request $request): JsonResponse
    {
        $subcontract_companies = SubcontractFactoryProfile::query()
            ->where('operation_type', $request->operation_type)->get(['id', 'name as text', 'address']);
        return response()->json($subcontract_companies);
    }

    public function getBuyersOrders(Request $request): JsonResponse
    {
        $orders = Order::query()->where('buyer_id', $request->buyer_id)->get()->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'text' => $item->style_name
            ];
        });
        return response()->json([
            'data' => $orders,
            'error' => null,
            'message' => 'Success',
            'status' => 200,
        ]);
    }

    public function getOrdersColors(Request $request): JsonResponse
    {
        $colors = PurchaseOrderDetail::query()->where('order_id', $request->get('order_id'))->groupBy('color_id')->get()
            ->map(function ($item, $key) {
                return [
                    'id' => Arr::get($item, 'color.id'),
                    'text' => Arr::get($item, 'color.name')
                ];
            })->toArray();
        return response()->json([
            'data' => $colors,
            'error' => null,
            'message' => 'Success',
            'status' => 200,
        ]);
    }

    public function getBuyerData(Request $request): JsonResponse
    {
        $buyers = Buyer::query()->where('factory_id', $request->get('factory_id'))->get()->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        });
        return response()->json([
            'data' => $buyers,
            'error' => null,
            'message' => 'Success',
            'status' => 200,
        ]);
    }

    public function getFloors(Request $request): JsonResponse
    {
        $factory_id = $request->get('factory_id');
        $buyers = Floor::query()->withoutGlobalScope('factoryId')
        ->when($factory_id, function ($query) use ($factory_id) {
            $query->where('factory_id', $factory_id);
        })
        ->when(!$factory_id, function ($query) {
            $query->where('factory_id', factoryId());
        })
        ->get()
        ->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'text' => $item->floor_no
            ];
        });
        return response()->json([
            'data' => $buyers,
            'error' => null,
            'message' => 'Success',
            'status' => 200,
        ]);
    }
}
