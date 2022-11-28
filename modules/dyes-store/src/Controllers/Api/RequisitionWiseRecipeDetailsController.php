<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;
use Symfony\Component\HttpFoundation\Response;

class RequisitionWiseRecipeDetailsController extends Controller
{
    /**
     * @param SubDyeingRecipe $dyeingRecipe
     * @return JsonResponse
     */
    public function __invoke(SubDyeingRecipe $dyeingRecipe): JsonResponse
    {
        try {
            $dyeingRecipe->load('recipeDetails');
            $dyeingRecipeDetails = $dyeingRecipe->recipeDetails->map(function ($collection) use ($dyeingRecipe) {
                $avgRate = DyesChemicalTransaction::query()
                    ->where('item_id', $collection->item_id)
                    ->avg('rate');

                return [
                    "rate" => $avgRate ?? 0.00,
                    "sr_no" => null,
                    "lot_no" => null,
                    "mrr_no" => null,
                    "uom_id" => $collection->unit_of_measurement_id,
                    "item_id" => $collection->item_id,
                    "remarks" => null,
                    "batch_no" => $dyeingRecipe->batch_no,
                    "brand_id" => $collection->item->brand_id,
                    "uom_name" => $collection->unitOfMeasurement->name,
                    "item_name" => $collection->item->name,
                    "brand_name" => $collection->item->brand->name,
                    "category_id" => $collection->item->category_id,
                    "receive_qty" => $collection->total_qty,
                    "delivery_qty" => $collection->total_qty,
                    "category_name" => $collection->item->category->name,
                    "life_end_days" => null,
                ];
            });

            return response()->json([
                'message' => 'Fetch recipe details successfully',
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
}
