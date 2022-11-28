<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Planing\DTO\CapacityPlanSearchDTO;
use SkylarkSoft\GoRMG\Planing\Models\FactoryCapacity;
use SkylarkSoft\GoRMG\Planing\Models\Settings\ItemCategory;
use SkylarkSoft\GoRMG\Sewingdroplets\Requests\FactoryCapacityRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CapacityPlanEntryAPIController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function searchCapacityPlan(Request $request): JsonResponse
    {
        $capacitySearchDTO = new CapacityPlanSearchDTO();
        $capacitySearchDTO->setDate($request->get('date'));
        $capacitySearchDTO->setFactoryId($request->get('factory_id'));
        $capacitySearchDTO->setFloorId($request->get('floor_id'));

        $factoryCapacityForm = $capacitySearchDTO->getFactoryCapacityForm();

        return response()->json($factoryCapacityForm);
    }

    /**
     * @param FactoryCapacityRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function save(FactoryCapacityRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $requestedData = collect($request->all())->filter(function ($item) {
                return $item['item_category_id'];
            });

            foreach ($requestedData as $capacityForm) {
                FactoryCapacity::query()->updateOrCreate(
                    [
                        'date' => $capacityForm['date'],
                        'factory_id' => $capacityForm['factory_id'],
                        'floor_id' => $capacityForm['floor_id'],
                        'line_id' => $capacityForm['line_id'],
                        'item_category_id' => $capacityForm['item_category_id'],
                    ],
                    [
                        'smv' => $capacityForm['smv'],
                        'efficiency' => $capacityForm['efficiency'],
                        'operator_machine' => $capacityForm['operator_machine'],
                        'helper' => $capacityForm['helper'],
                        'wh' => $capacityForm['wh'],
                        'capacity_pcs' => $capacityForm['capacity_pcs'],
                        'capacity_available_mins' => $capacityForm['capacity_available_mins'],
                        'updated_by' => auth()->id(),
                    ]
                );
            }
            DB::commit();

            return response()->json(['message' => "Successfully Data Stored"], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(FactoryCapacity $factoryCapacity): JsonResponse
    {
        try {
            $factoryCapacity->delete();

            return response()->json(['message' => 'Successfully Deleted'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchItemCategories(): JsonResponse
    {
        try {
            $itemCategories = ItemCategory::query()->get(['id', 'name as text']);

            return response()->json([
                'data' => $itemCategories,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function factoryWiseBuyers($factoryId): JsonResponse
    {
        try {
            $buyers = Buyer::query()->where('factory_id', $factoryId)->pluck('name', 'id');

            return response()->json([
                'data' => $buyers,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
