<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;

class ItemGroupsApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $itemGroups = ItemGroup::query()->get()->map(function ($itemGroup) {
                return [
                    'id' => $itemGroup->id,
                    'text' => $itemGroup->item_group,
                    'item_id' => $itemGroup->item_id,
                    'item_name' => $itemGroup->item->item_name,
                    'uom_id' => $itemGroup->orderUOM->id,
                    'uom_name' => $itemGroup->orderUOM->unit_of_measurement,
                ];
            });

            return response()->json($itemGroups, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
