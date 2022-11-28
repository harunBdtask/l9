<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipe;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipeDetail;

class BatchWiseRecipeDetailsSearchController extends Controller
{

    /**
     * @param $batchId
     * @return JsonResponse
     */
    public function __invoke($batchId): JsonResponse
    {
        try {
            $recipeDetails = DyeingRecipeDetail::query()
                ->whereHas('recipe', Filter::applyFilter('dyeing_batch_id', $batchId))
                ->get()->map(function ($collection) {
                    return [
                        'id' => null,
                        'dyeing_recipe_id' => $collection->dyeing_recipe_id,
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
                });

            return response()->json([
                'message' => 'Fetch previous recipe details successfully',
                'data' => $recipeDetails,
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
