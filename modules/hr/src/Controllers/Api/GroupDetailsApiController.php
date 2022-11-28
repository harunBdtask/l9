<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\HR\Models\HrGroup;

class GroupDetailsApiController extends Controller
{
    /**
     * @param HrGroup $hrGroup
     * @return JsonResponse
     */
    public function __invoke(HrGroup $hrGroup): JsonResponse
    {
        return response()->json($hrGroup);
    }
}
