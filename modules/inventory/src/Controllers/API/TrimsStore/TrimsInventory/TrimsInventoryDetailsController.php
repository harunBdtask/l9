<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreInventory\SyncTrimsInventoryAction;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventory;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventoryDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsInventoryDetailFormRequest;
use SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters\TrimsInventoryDetailFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsInventoryDetailsController extends Controller
{
    /**
     * @param TrimsInventory $trimsInventory
     * @param TrimsInventoryDetailFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(
        TrimsInventory                $trimsInventory,
        TrimsInventoryDetailFormatter $formatter
    ): JsonResponse {
        try {
            $loadData = [
                'details',
                'details.itemGroup',
                'details.color',
                'details.uom',
                'details.createdBy',
            ];

            $trimsInventory->load($loadData);

            $details = $trimsInventory->getRelation('details')->map(function ($detail) use ($formatter) {
                return $formatter->format($detail);
            });

            return response()->json([
                'message' => 'Fetch Trims Inventory Details Successfully',
                'data' => $details,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param TrimsInventory $trimsInventory
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request, TrimsInventory $trimsInventory): JsonResponse
    {
        try {
            DB::beginTransaction();
            $trimsInventory->details()->createMany($request->all());
            DB::commit();

            return response()->json([
                'message' => 'Trims Inventory Details Stored Successfully',
                'data' => $trimsInventory->load('details')->details,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TrimsInventoryDetailFormRequest $request
     * @param TrimsInventoryDetail $detail
     * @param SyncTrimsInventoryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        TrimsInventoryDetailFormRequest $request,
        TrimsInventoryDetail            $detail,
        SyncTrimsInventoryAction        $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->load('trimsInventory');
            $detail->fill($request->all())->save();
            $action->handle($detail->trimsInventory);
            DB::commit();

            return response()->json([
                'message' => 'Trims Inventory Details Updated Successfully',
                'data' => $detail,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeAndUpdateDetails(Request $request)
    {
        // TODO: Multiple details store and update;
    }

    /**
     * @param TrimsInventoryDetail $detail
     * @return JsonResponse
     */
    public function destroy(TrimsInventoryDetail $detail): JsonResponse
    {
        try {
            $detail->delete();

            return response()->json([
                'message' => 'Trims Inventory Details Deleted Successfully',
                'data' => $detail,
                'status' => Response::HTTP_NO_CONTENT,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
