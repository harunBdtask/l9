<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Actions\SyncRecipeDetailsAction;
use SkylarkSoft\GoRMG\Dyeing\Requests\Recipe\DyeingRecipeDetailFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipe;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipeDetail;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\DyeingRecipeDetailFormatter;

class RecipeDetailController extends Controller
{

    /**
     * @param DyeingRecipe $dyeingRecipe
     * @param DyeingRecipeDetailFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(DyeingRecipe $dyeingRecipe, DyeingRecipeDetailFormatter $formatter): JsonResponse
    {
        try {
            $dyeingRecipe->load('recipeDetails');

            $dyeingRecipeDetails = $dyeingRecipe->getRelation('recipeDetails')
                ->map(function ($collection) use ($formatter) {
                    return $formatter->format($collection);
                });

            return response()->json([
                'message' => 'Fetch dyeing recipe details successfully',
                'data' => $dyeingRecipeDetails,
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
     * @param DyeingRecipeDetailFormRequest $request
     * @param DyeingRecipe $dyeingRecipe
     * @return JsonResponse
     */
    public function store(DyeingRecipeDetailFormRequest $request, DyeingRecipe $dyeingRecipe): JsonResponse
    {
        try {
            $dyeingRecipe->recipeDetails()->updateOrCreate([
                'id' => $request->input('id'),
            ], $request->all());

            return response()->json([
                'message' => 'Dyeing recipe details stored successfully',
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
     * @param DyeingRecipe $dyeingRecipe
     * @param SyncRecipeDetailsAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function storeDetails(Request                 $request,
                                 DyeingRecipe            $dyeingRecipe,
                                 SyncRecipeDetailsAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $detail) {

                if (!$detail['dyeing_recipe_id'] ||
                    !$detail['recipe_operation_id'] ||
                    !$detail['recipe_function_id'] ||
                    !$detail['item_id'] ||
                    !$detail['unit_of_measurement_id']) {

                    continue;
                }

                $dyeingRecipe->recipeDetails()->updateOrCreate([
                    'id' => $detail['id'] ?? null,
                ], array_merge($detail, [
                    'dyeing_recipe_id' => $dyeingRecipe->id,
                ]));
            }

            $action->syncTotalQty($dyeingRecipe);
            DB::commit();

            return response()->json([
                'message' => 'Dyeing recipe details stored & updated successfully',
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

    public function destroy(DyeingRecipeDetail $dyeingRecipeDetail): JsonResponse
    {
        try {
            $dyeingRecipeDetail->delete();

            return response()->json([
                'message' => 'Dyeing recipe details stored successfully',
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
