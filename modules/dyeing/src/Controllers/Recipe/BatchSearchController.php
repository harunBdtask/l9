<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;

class BatchSearchController extends Controller
{

    /**
     * @param $dyeingBatchId
     * @return JsonResponse
     */
    public function __invoke($dyeingBatchId): JsonResponse
    {
        try {
            $dyeingBatch = DyeingBatch::query()
                ->with('machineAllocations')
                ->where('id', $dyeingBatchId)
                ->first();

            if (!$dyeingBatch) {
                throw new Exception('Batch not found');
            }

            $machines = collect($dyeingBatch->machineAllocations)
                ->pluck('machine.name')
                ->implode(',');

            $response = [
                'buyer' => $dyeingBatch->buyer->name,
                'orders_no' => collect($dyeingBatch->orders_no)->implode(', '),
                'dyeing_batch_id' => $dyeingBatch->id,
                'dyeing_batch_no' => $dyeingBatch->batch_no,
                'machines_no' => $machines,
                'fabric_description' => $dyeingBatch->fabric_description,
                'color' => $dyeingBatch->fabric_color_id,
                'gsm' => $dyeingBatch->gsm,
                'machine_capacity' => $dyeingBatch->total_machine_capacity,
                'yarn_description' => null,
                'ld_no' => $dyeingBatch->ld_no,
                'fabric_weight' => $dyeingBatch->total_batch_weight,
                'recipe_date' => date('Y-m-d'),
                'liquor_ratio' => null,
                'total_liq_level' => null,
                'shift_id' => null,
                'remarks' => null,
            ];

            return response()->json([
                'message' => 'Fetch batch data successfully',
                'data' => $response,
                'status' => Response::HTTP_OK,
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
