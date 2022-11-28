<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;
use Symfony\Component\HttpFoundation\Response;

class SubTextileOrderWiseSubDyeingBatchApiController
{
    /**
     * @param int $orderId
     * @return JsonResponse
     */
    public function __invoke(int $orderId): JsonResponse
    {
        //        $batches = SubDyeingBatch::query()
        //            ->whereJsonContains('sub_textile_order_ids', ['sub_textile_order_id' => $orderId])
        //            ->get(['batch_no as text', 'id']);

        $batches = SubDyeingBatchDetail::query()
            ->with('subDyeingBatch')
            ->has('subDyeingBatch')
            ->where('sub_textile_order_id', $orderId)
            ->groupBy('sub_dyeing_batch_id')
            ->get()->map(function ($batchDetailCollection) {
                return [
                    'id' => $batchDetailCollection->sub_dyeing_batch_id,
                    'text' => $batchDetailCollection->subDyeingBatch->batch_no,
                    'order_id' => $batchDetailCollection->sub_textile_order_id,
                    'supplier_id' => $batchDetailCollection->supplier_id,
                ];
            });

        return response()->json([
            'data' => $batches,
            'message' => 'Batch fetched successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
