<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Actions\DyeingProcessActions\SyncBatchCreationAction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchMachineAllocation;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingBatchMachineFormRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BatchMachineController extends Controller
{
    /**
     * @param SubDyeingBatch $dyeingBatch
     * @return JsonResponse
     */
    public function index(SubDyeingBatch $dyeingBatch): JsonResponse
    {
        try {
            $batchMachineAllocation = SubDyeingBatchMachineAllocation::query()
                ->with('machine')
                ->where('sub_dyeing_batch_id', $dyeingBatch->id)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'data' => $batchMachineAllocation,
                'message' => 'dyeing batch machine allocations fetched successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingBatchMachineFormRequest $request
     * @param SubDyeingBatch $dyeingBatch
     * @param SyncBatchCreationAction $syncBatchCreationAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        SubDyeingBatchMachineFormRequest $request,
        SubDyeingBatch                   $dyeingBatch,
        SyncBatchCreationAction          $syncBatchCreationAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $dyeingBatch->machineAllocations()->updateOrCreate([
                'id' => $request->input('id'),
            ], $request->all());
            $syncBatchCreationAction->syncBatchFromMachineAllocation($dyeingBatch);
            DB::commit();

            return response()->json([
                'data' => $dyeingBatch,
                'message' => 'dyeing batch machine created successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingBatch $dyeingBatch
     * @param SubDyeingBatchMachineAllocation $batchMachineAllocation
     * @param SyncBatchCreationAction $syncBatchCreationAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(
        SubDyeingBatch                  $dyeingBatch,
        SubDyeingBatchMachineAllocation $batchMachineAllocation,
        SyncBatchCreationAction         $syncBatchCreationAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $batchMachineAllocation->delete();
            $syncBatchCreationAction->syncBatchFromMachineAllocation($dyeingBatch);
            DB::commit();

            return response()->json([
                'message' => 'dyeing batch machine deleted successfully',
                'data' => [],
                'status' => Response::HTTP_NO_CONTENT,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
