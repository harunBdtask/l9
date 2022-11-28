<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Actions\DyeingProcessActions\DyeingRecipeActions\SyncRecipeDetails;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipeDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\DyeingRecipeRequests\RecipeDetailsFormRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RecipeDetailsController extends Controller
{
    /**
     * @param SubDyeingRecipe $dyeingRecipe
     * @return JsonResponse
     */
    public function getDetails(SubDyeingRecipe $dyeingRecipe): JsonResponse
    {
        try {
            $dyeingRecipe->load('recipeDetails');
            $dyeingRecipeDetail = $dyeingRecipe->recipeDetails->map(function ($collection) {
                return [
                    'id' => $collection->id,
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
                    'total_qty' => number_format($collection->total_qty, 3),
                    'remarks' => $collection->remarks,
                ];
            });

            return response()->json([
                'message' => 'Fetch recipe details successfully',
                'data' => $dyeingRecipeDetail,
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

    /**
     * @param RecipeDetailsFormRequest $request
     * @param SubDyeingRecipe $dyeingRecipe
     * @return JsonResponse
     */
    public function store(RecipeDetailsFormRequest $request, SubDyeingRecipe $dyeingRecipe): JsonResponse
    {
        try {
            $dyeingRecipe->recipeDetails()->updateOrCreate([
                'id' => $request->input('id'),
            ], $request->all());

            return response()->json([
                'message' => 'Recipe detail store successfully',
                'data' => $dyeingRecipe,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param SubDyeingRecipe $dyeingRecipe
     * @param SyncRecipeDetails $syncRecipeDetails
     * @return JsonResponse
     * @throws Throwable
     */
    public function batchStore(
        Request           $request,
        SubDyeingRecipe   $dyeingRecipe,
        SyncRecipeDetails $syncRecipeDetails
    ): JsonResponse {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $detail) {
                if (! $detail['sub_dyeing_recipe_id'] ||
                    ! $detail['recipe_operation_id']) {
                    continue;
                }
                $dyeingRecipe->recipeDetails()->updateOrCreate([
                    'id' => $detail['id'] ?? null,
                ], $detail);
            }
            $syncRecipeDetails->syncTotalQty($dyeingRecipe);
            DB::commit();

            return response()->json([
                'message' => 'Recipe detail store successfully',
                'data' => $dyeingRecipe,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingRecipeDetail $dyeingRecipeDetail
     * @return JsonResponse
     */
    public function destroy(SubDyeingRecipeDetail $dyeingRecipeDetail): JsonResponse
    {
        try {
            $dyeingRecipeDetail->delete();

            return response()->json([
                'message' => 'Recipe detail store successfully',
                'data' => $dyeingRecipeDetail,
                'status' => Response::HTTP_NO_CONTENT,
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
