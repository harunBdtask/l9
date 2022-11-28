<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\GroupWiseField;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use Symfony\Component\HttpFoundation\Response;

class TrimsItemWiseGroupFieldsApiController extends Controller
{
    /**
     * @param $itemId
     * @return JsonResponse
     */
    public function __invoke($itemId): JsonResponse
    {
        $group = GroupWiseField::query()->where('group_name', $itemId)->first();
        $groupFields = $group->fields ?? [];

        return response()->json($groupFields, Response::HTTP_OK);
    }
}
