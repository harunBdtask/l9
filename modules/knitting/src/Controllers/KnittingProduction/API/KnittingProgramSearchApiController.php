<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\KnittingProduction\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use Symfony\Component\HttpFoundation\Response;

class KnittingProgramSearchApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $knitProgram = KnittingProgram::query()
                ->with([
                    'factory:id,factory_name',
                    'knittingParty:id,factory_name',
                    'planInfo'
                ])
                ->when($request->get('factory_id'),
                    Filter::applyFilter('factory_id', $request->get('factory_id')))
                ->when($request->get('knitting_source_id'),
                    Filter::applyFilter('knitting_source_id', $request->get('knitting_source_id')))
                ->when($request->get('production_status'),
                    Filter::applyFilter('production_pending_status', $request->get('production_status')))
                ->when($request->get('program_no'),
                    Filter::applyFilter('program_no', $request->get('program_no')))
                ->when($request->get('from_date') && $request->get('to_date'),
                    Filter::dateRangeFilter('program_date', [
                        $request->get('from_date'),
                        $request->get('to_date')
                    ]))
                ->orderBy('id', 'DESC')
                ->paginate(15);

            return response()->json($knitProgram, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
