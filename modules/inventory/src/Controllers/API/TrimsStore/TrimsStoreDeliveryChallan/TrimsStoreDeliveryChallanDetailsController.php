<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreDeliveryChallan;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreDeliveryChallan\TrimsStoreDeliveryChallanDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsDeliveryChallan\TrimsStoreDeliveryChallanDetailFormRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreDeliveryChallanDetailsController extends Controller
{
    /**
     * @param TrimsStoreDeliveryChallanDetailFormRequest $request
     * @param TrimsStoreDeliveryChallanDetail $detail
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        TrimsStoreDeliveryChallanDetailFormRequest $request,
        TrimsStoreDeliveryChallanDetail            $detail
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Trims Store Delivery Challan Details Updated Successfully',
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

    /**
     * @param TrimsStoreDeliveryChallanDetail $detail
     * @return JsonResponse
     */
    public function destroy(TrimsStoreDeliveryChallanDetail $detail): JsonResponse
    {
        try {
            $detail->delete();

            return response()->json([
                'message' => 'Trims Store Delivery Challan Details Deleted Successfully',
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
