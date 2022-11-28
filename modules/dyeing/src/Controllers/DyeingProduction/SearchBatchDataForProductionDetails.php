<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingProduction;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\DyeingProductionService;

class SearchBatchDataForProductionDetails extends Controller
{

    /**
     * @param DyeingBatch $dyeingBatch
     * @return JsonResponse
     */
    public function __invoke(DyeingBatch $dyeingBatch): JsonResponse
    {
        try {
            $dyeingBatch->load(['subDyeingUnit', 'dyeingBatchDetails', 'machineAllocations.machine']);

            $batchData = (new DyeingProductionService())->fetchBatchDetailsData($dyeingBatch);

            return response()->json([
                'message' => 'Fetched Successfully',
                'data' => $batchData,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
