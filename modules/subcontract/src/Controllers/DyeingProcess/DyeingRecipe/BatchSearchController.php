<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use Symfony\Component\HttpFoundation\Response;

class BatchSearchController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $dyeingBatch = SubDyeingBatch::query()
                ->with([
                    'supplier',
                    'machineAllocations.machine',
                ])
                ->where('id', $request->get('batch_no'))
                ->first();

            $machines = collect($dyeingBatch->machineAllocations)
                ->pluck('machine.name')
                ->implode(',');

            $response = [
                'supplier' => $dyeingBatch->supplier->name,
                'order_nos' => collect($dyeingBatch->order_nos)->implode(', '),
                'batch_no' => $dyeingBatch->batch_no,
                'machine_nos' => $machines,
                'fabric_description' => $dyeingBatch->material_description,
                'color' => $dyeingBatch->fabricColor->name,
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
                'data' => $response,
                'message' => 'batch fetched successfully',
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
