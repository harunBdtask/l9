<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;
use Symfony\Component\HttpFoundation\Response;

class BatchWiseRecipeSearchController extends Controller
{
    /**
     * @param $batchId
     * @return JsonResponse
     */
    public function __invoke($batchId): JsonResponse
    {
        try {
            $subDyeingRecipe = SubDyeingRecipe::query()
                ->with('recipeDetails')
                ->where('batch_id', $batchId)
                ->first();

            $subDyeingRecipeDetail = $subDyeingRecipe->recipeDetails->map(
                function ($collection) {
                    return [
                        'id' => null,
                        'sub_dyeing_recipe_id' => $collection->sub_dyeing_recipe_id,
                        'recipe_operation_id' => $collection->recipe_operation_id,
                        'recipe_function_id' => $collection->recipe_function_id,
                        'item_id' => $collection->item_id,
                        'unit_of_measurement_id' => $collection->unit_of_measurement_id,
                        'unit_of_measurement' => $collection->unitOfMeasurement->name,
                        'percentage' => $collection->percentage,
                        'g_per_ltr' => $collection->g_per_ltr,
                        'plus_minus' => $collection->plus_minus,
                        'additional' => $collection->additional,
                        'total_qty' => $collection->total_qty,
                        'remarks' => $collection->remarks,
                    ];
                }
            );

            return response()->json([
                'message' => 'Fetch recipe details successfully',
                'data' => $subDyeingRecipeDetail,
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
