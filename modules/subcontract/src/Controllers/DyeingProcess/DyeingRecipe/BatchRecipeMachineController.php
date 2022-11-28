<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Actions\DyeingProcessActions\SyncBatchCreationAction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchMachineAllocation;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingBatchMachineFormRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BatchRecipeMachineController extends Controller
{
    /**
     * @param SubDyeingBatch $dyeingBatch
     * @return JsonResponse
     */
    public function index(SubDyeingRecipe $dyeingRecipe): JsonResponse
    {
        try {
            $batchMachineAllocation = SubDyeingBatchMachineAllocation::query()
                ->with('machine')
                ->where('sub_dyeing_recipe_id', $dyeingRecipe->id)
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
        SubDyeingRecipe                   $dyeingRecipe
        // SyncBatchCreationAction          $syncBatchCreationAction
    ): JsonResponse {
        // ) {
        try {
            DB::beginTransaction();
            $dyeingRecipe->machineAllocations()->updateOrCreate([
                'id' => $request->input('id'),
            ], $request->all());

            //Update dyeing batch capacity value
            $machines = $dyeingRecipe->load('machineAllocations.machine');
            $capacity = collect($machines->machineAllocations)->pluck('machine.capacity')->implode(', ');
            $dyeingRecipe->subDyeingBatch()->update([
                'total_machine_capacity' => $capacity,
            ]);
            $dyeingRecipe->total_machine_capacity = $capacity;
            $dyeingRecipe->machine_nos = collect($machines->machineAllocations)->pluck('machine.name')->implode(', ');


            // $syncBatchCreationAction->syncBatchFromMachineAllocation($dyeingBatch);
            DB::commit();

            return response()->json([
                'data' => $dyeingRecipe,
                'message' => 'dyeing recipe machine created successfully',
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
        SubDyeingRecipe                  $dyeingRecipe,
        SubDyeingBatchMachineAllocation $batchMachineAllocation
        // SyncBatchCreationAction         $syncBatchCreationAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $batchMachineAllocation->delete();

            //Update dyeing batch capacity value
            $machines = $dyeingRecipe->load('machineAllocations.machine');
            $capacity = collect($machines->machineAllocations)->pluck('machine.capacity')->implode(', ');
            $dyeingRecipe->subDyeingBatch()->update([
                'total_machine_capacity' => $capacity,
            ]);
            $dyeingRecipe->total_machine_capacity = $capacity;
            $dyeingRecipe->machine_nos = collect($machines->machineAllocations)->pluck('machine.name')->implode(', ');

            // $syncBatchCreationAction->syncBatchFromMachineAllocation($dyeingBatch);
            DB::commit();

            return response()->json([
                'message' => 'dyeing batch machine deleted successfully',
                'data' => $dyeingRecipe,
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
