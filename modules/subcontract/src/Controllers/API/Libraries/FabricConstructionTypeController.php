<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use Symfony\Component\HttpFoundation\Response;

class FabricConstructionTypeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $fabricConstructionType = FabricConstructionEntry::query()
                ->where('construction_name', 'LIKE', "%$request->search%")
                ->get(['id', 'construction_name as text']);

            return response()->json(['data' => $fabricConstructionType], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
