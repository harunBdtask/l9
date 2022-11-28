<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class GarmentsItemAPIController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $items = GarmentsItem::query()->get(['id', 'name as text']);
        return response()->json($items);
    }
}
