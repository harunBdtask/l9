<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Services\Strategies\ItemStrategy\ItemStrategy;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use Symfony\Component\HttpFoundation\Response;

class ItemsApiController extends Controller
{

    public function __invoke($type, $itemCategoryId): JsonResponse
    {
        try {
            $items = (new ItemStrategy())->setType($type)
                ->setCategoryId($itemCategoryId)
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

    public function get_item_groups(): JsonResponse
    {
        try {
            $items = ItemGroup::orderBy('item_group', 'asc')->get(['id', 'item_group as text']);
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
