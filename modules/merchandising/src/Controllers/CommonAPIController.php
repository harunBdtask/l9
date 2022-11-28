<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Brand;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\CareInstruction;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\Process;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;
use Symfony\Component\HttpFoundation\Response;

class CommonAPIController extends Controller
{
    public function processes()
    {
        $processes = Process::all([
            'id',
            'process_name as text',
            'color_wise_charge_unit',
        ]);

        return response()->json($processes);
    }

    public function brands(): JsonResponse
    {
        try {
            $brands = Brand::all()->map(function ($collection) {
                return [
                    'id' => $collection->id,
                    'text' => $collection->brand_name,
                ];
            });

            return response()->json($brands, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function yarnCounts(): JsonResponse
    {
        try {
            $yarnCounts = YarnCount::all()->map(function ($collection) {
                return [
                    'id' => $collection->id,
                    'text' => $collection->yarn_count,
                ];
            });

            return response()->json($yarnCounts, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function yarnCompositions(): JsonResponse
    {
        try {
            $yarnCompositions = YarnComposition::all()->map(function ($collection) {
                return [
                    'id' => $collection->id,
                    'text' => $collection->yarn_composition,
                ];
            });

            return response()->json($yarnCompositions, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function buyers($factoryId): JsonResponse
    {
        $buyers = Buyer::query()
            ->filterWithAssociateFactory('buyerWiseFactories', $factoryId)
            ->get()->map(function ($collection) {
                $collection['text'] = $collection->name;
                return $collection;
            });

        return response()->json($buyers);
    }

    public function allBuyers(): JsonResponse
    {
        $buyers = Buyer::query()
            ->permittedBuyer()
            ->get(['id', 'name as text']);

        return response()->json($buyers);
    }

    public function yarnTypes(): JsonResponse
    {
        $yarnTypes = YarnType::all(['id', 'yarn_type as text']);

        return response()->json($yarnTypes);
    }

    public function unitOfMeasurements(): JsonResponse
    {
        $uoms = UnitOfMeasurement::where('status', 'Active')->get(['id', 'unit_of_measurement as text']);

        return response()->json($uoms);
    }

    public function styleNames()
    {
        return Budget::all('style_name')->map(function ($budget) {
            return [
                'id' => $budget['style_name'],
                'text' => $budget['style_name'],
            ];
        })->unique()->values();
    }

    public function buyerStyleNames($id)
    {
        return Budget::query()
            ->where('buyer_id', $id)->get(['style_name', 'job_no', 'id'])->map(function ($budget) {
                return [
                    'id' => $budget['style_name'],
                    'text' => $budget['style_name'],
                    'unique_id' => $budget['job_no'],
                    'budget_id' => $budget['id'],
                ];
            })->unique()->values();
    }

    public function shipModes(OrderService $orderService): JsonResponse
    {
        $data = collect($orderService->shipMode())->map(function ($value, $key) {
            return [
                'id' => $key + 1,
                'text' => $value,
            ];
        });

        return response()->json($data, Response::HTTP_OK);
    }

    public function items(): JsonResponse
    {
        $data = Item::query()->get()->map(function ($value, $key) {
            return [
                'id' => $value['id'],
                'text' => $value['item_name'],
            ];
        });

        return response()->json($data, Response::HTTP_OK);
    }

    public function garmentsItems(): JsonResponse
    {
        $data = GarmentsItem::query()->get()->map(function ($value) {
            return [
                'id' => $value['id'],
                'text' => $value['name'],
            ];
        });

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function buyersStyle($id): JsonResponse
    {
        $style_names = Order::query()
            ->where('buyer_id', $id)
            ->get(['id', 'style_name as text', 'fabrication as item_group']);
        return response()->json($style_names);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function ordersItems($id): JsonResponse
    {
        $items = PoColorSizeBreakdown::query()
            ->with('garmentItem')
            ->where('order_id', $id)
            ->get()
            ->map(function ($items) {
                return [
                    'text' => $items->garmentItem->name,
                    'id' => $items->garments_item_id,
                ];
            })->unique('id');
        return response()->json($items);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function styleItemPO(Request $request): JsonResponse
    {
        $orderId = $request->get('order_id');
        $itemId = $request->get('item_id');
        $pos = PoColorSizeBreakdown::query()
            ->with('purchaseOrder')
            ->where('order_id', $orderId)
            ->where('garments_item_id', $itemId)
            ->get()
            ->map(function ($po) {
                return [
                    'id' => optional($po->purchaseOrder)->id,
                    'text' => optional($po->purchaseOrder)->po_no,
                ];
            })
            ->unique('id')
            ->values();
        return response()->json($pos);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function poItemColors(Request $request): JsonResponse
    {
        $purchaseOrderId = $request->get('po_id');
        $itemId = $request->get('item_id');

        $color_ids = PoColorSizeBreakdown::query()
            ->with('purchaseOrder')
            ->where('purchase_order_id', $purchaseOrderId)
            ->where('garments_item_id', $itemId)
            ->pluck("colors")
            ->flatten()
            ->unique();

        $get_colors = Color::query()->whereIn("id", $color_ids)->get();

        $colors = collect($color_ids)
            ->map(function ($value) use ($get_colors) {
                $color_name = collect($get_colors)->where("id", $value)->first();

                return [
                    "id" => $value,
                    "text" => $color_name['name'],
                ];
            })->values();

        return response()->json($colors);
    }

    public function deleteImageFromFolder()
    {
        $path = request()->get('path');
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    public function currentUserInfo()
    {
        $user = Auth::user();
        $user['role_details'] = getRole();
        return response()->json($user);
    }

    public function suppliersInfo()
    {
        $suppliers = Supplier::query()
            ->with('supplierWiseFactories')
            ->withoutGlobalScopes()
            ->filterWithAssociateFactory('supplierWiseFactories', factoryId())
            ->get([
                'id',
                'name as text',
                'contact_person as attn',
                'contact_no',
                'address_1 as party_address',
                'email as party_email',
            ])
            ->toArray();
        return response()->json($suppliers);
    }

    public function factoryBuyersStyle($factoryId, $buyerId)
    {
        $styles = Order::query()->where(['factory_id' => $factoryId, 'buyer_id' => $buyerId])
            ->get()->map(function ($item) {
                return [
                    'id' => $item['style_name'],
                    'text' => $item['style_name'],
                ];
            });
        return response()->json($styles);
    }

    public function fetchPo(Request $request)
    {
        $pos = Order::query()->with('purchaseOrders')->where([
            'factory_id' => $request->get('factoryId'),
            'buyer_id' => $request->get('buyerId'),
            'style_name' => $request->get('style_name'),
        ])->get()->map(function ($item) {
            return collect($item['purchaseOrders'])->map(function ($po) {
                return [
                    'id' => $po['po_no'],
                    'text' => $po['po_no'],
                ];
            });
        })->collapse();

        return response()->json($pos);
    }

    public function fetchUniqueId(Request $request)
    {
        $buyerId = $request->get('buyerId');
        $factoryId = $request->get('companyId');
        $style = $request->get('style');
        $unique = Order::query()
            ->where([
                'factory_id' => $factoryId,
                'buyer_id' => $buyerId,
                'style_name' => $style,
            ])->first();
        $uniqueId = isset($unique) ? $unique['job_no'] : null;
        return response()->json($uniqueId);
    }

    public function factoryBuyerStyleNames($factoryId, $buyerId)
    {
        $data = Order::query()->where([
            'factory_id' => $factoryId,
            'buyer_id' => $buyerId,
        ])->get()->map(function ($order) {
            return [
                'id' => $order['style_name'],
                'text' => $order['style_name'],
                'unique_id' => $order['job_no'],
                'order_id' => $order['id'],
            ];
        });
        return response()->json($data);
    }

    public function teams(): JsonResponse
    {
        try {
            $teams = Team::query()->get(['team_name as text', 'id']);
            return response()->json($teams, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function careInstructions(): JsonResponse
    {
        try {
            $careInstructions = CareInstruction::query()->get(['id', 'instruction as text']);
            return response()->json($careInstructions, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function garmentsItemGroup(): JsonResponse
    {
        try {
            $teams = GarmentsItemGroup::query()->get(['name as text', 'id']);
            return response()->json($teams, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function currencies(): JsonResponse
    {
        try {
            $currencies = Currency::query()->get(['id', 'currency_name as text']);

            return response()->json([
                'message' => 'Fetch currencies',
                'data' => $currencies,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function supplierDetail(Supplier $supplier): JsonResponse
    {
        try {
            return response()->json($supplier, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function itemGroups(): JsonResponse
    {
        try {
            $itemGroups = ItemGroup::query()
                ->where('factory_id', factoryId())
                ->get(['id', 'item_group as text', 'group_code']);
            return response()->json($itemGroups, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sizes(): JsonResponse
    {
        try {
            $sizes = Size::query()
                ->where('factory_id', factoryId())
                ->get(['id', 'name as text']);
            return response()->json($sizes, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function colors(): JsonResponse
    {
        try {
            $sizes = Color::query()
                ->where('factory_id', factoryId())
                ->get(['id', 'name as text']);
            return response()->json($sizes, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
