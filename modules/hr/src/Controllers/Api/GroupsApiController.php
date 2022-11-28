<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\HR\Models\HrGroup;

class GroupsApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json(HrGroup::query()->get());
    }
}
