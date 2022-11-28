<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use Symfony\Component\HttpFoundation\Response;

class SubDyeingBatchApiController extends Controller
{
    /**
     * Undocumented function
     *
     * @param int $factoryId
     * @param int|null $supplierId
     * @return JsonResponse
     */
    public function __invoke(int $factoryId, int $supplierId = null): JsonResponse
    {
        try {
            $batches = SubDyeingBatch::query()
                ->where('factory_id', $factoryId)
                ->when($supplierId, function ($query) use ($supplierId) {
                    $query->where('supplier_id', $supplierId);
                })
                ->get(['batch_no as text', 'id']);

            return response()->json([
                'data' => $batches,
                'message' => 'Batch fetch successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
