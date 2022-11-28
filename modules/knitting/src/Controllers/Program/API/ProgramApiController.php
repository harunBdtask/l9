<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Program\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use Symfony\Component\HttpFoundation\Response;

class ProgramApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function __invoke(Request $request): JsonResponse
    {
        $buyerId = $request->get('buyer_id');
        $knittingSourceId = $request->get('knitting_source_id');
        try {
            $knitProgram = KnittingProgram::query()
                ->when($request->get('search'), function ($query) use ($request) {
                    $query->where('program_no', $request->get('search'));
                })
                ->when($knittingSourceId, Filter::applyFilter('knitting_source_id', $knittingSourceId))
                ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
                ->orderByDesc('created_at')
                ->get(['id', 'program_no as text']);

            return response()->json($knitProgram, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
