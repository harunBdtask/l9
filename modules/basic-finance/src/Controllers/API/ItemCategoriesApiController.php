<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Services\Strategies\ItemCategoryStrategy\ItemCategoryStrategy;
use Symfony\Component\HttpFoundation\Response;

class ItemCategoriesApiController extends Controller
{

    public function __invoke($type): JsonResponse
    {
        try {
            $items = (new ItemCategoryStrategy())->setType($type)
                ->generate();
            return response()->json([
                'message' => 'Fetch Items successfully',
                'data' => $items,
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
