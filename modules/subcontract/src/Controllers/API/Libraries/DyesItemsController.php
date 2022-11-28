<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use Symfony\Component\HttpFoundation\Response;

class DyesItemsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $items = DsItem::query()->with('uomDetails')
                ->get()->map(function ($collection) {
                    return [
                        'id' => $collection->id,
                        'text' => $collection->name,
                        'uom_id' => $collection->uom,
                        'uom_value' => $collection->uomDetails->name,
                    ];
                });

            return response()->json([
                'message' => 'Fetch dyes items successfully',
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
