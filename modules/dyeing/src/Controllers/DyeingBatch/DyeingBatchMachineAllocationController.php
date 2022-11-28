<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingBatch;

use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Actions\SyncDyeingBatchAction;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Requests\DyeingBatch\DyeingBatchMachineAllocationRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatchMachineAllocation;

class DyeingBatchMachineAllocationController extends Controller
{

    /**
     * @param DyeingBatch $dyeingBatch
     * @return JsonResponse
     */
    public function index(DyeingBatch $dyeingBatch): JsonResponse
    {
        try {
            $batchMachineAllocation = $dyeingBatch->load('machineAllocations')['machineAllocations'];
            $batchMachineAllocation->load('machine');

            return response()->json([
                'data' => $batchMachineAllocation,
                'message' => 'Dyeing batch machine allocations fetched successfully',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingBatchMachineAllocationRequest $request
     * @param DyeingBatch $dyeingBatch
     * @param SyncDyeingBatchAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(DyeingBatchMachineAllocationRequest $request,
                          DyeingBatch                         $dyeingBatch,
                          SyncDyeingBatchAction               $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatch->machineAllocations()->updateOrCreate([
                'id' => $request->input('id'),
            ], $request->all());

            $action->syncBatchFromMachineAllocation($dyeingBatch);
            DB::commit();

            return response()->json([
                'message' => 'Dyeing batch machine allocation created successfully',
                'data' => $dyeingBatch,
                'status' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingBatch $dyeingBatch
     * @param DyeingBatchMachineAllocation $dyeingBatchMachineAllocation
     * @param SyncDyeingBatchAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(DyeingBatch                  $dyeingBatch,
                            DyeingBatchMachineAllocation $dyeingBatchMachineAllocation,
                            SyncDyeingBatchAction        $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatchMachineAllocation->delete();
            $action->syncBatchFromMachineAllocation($dyeingBatch);
            DB::commit();

            return response()->json([
                'message' => 'Dyeing batch machine allocation deleted successfully',
                'data' => [],
                'status' => Response::HTTP_NO_CONTENT
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
