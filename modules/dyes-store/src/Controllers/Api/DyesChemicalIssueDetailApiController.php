<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsIssue;
use Symfony\Component\HttpFoundation\Response;

class DyesChemicalIssueDetailApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $systemGenerateId = $request->get('system_generate_id');
            $dyesChemicalIssue = DyesChemicalsIssue::query()
                ->when($systemGenerateId, function (Builder $builder) use ($systemGenerateId) {
                    $builder->where('id', $systemGenerateId);
                })->first();
            $details = collect($dyesChemicalIssue->details)->map(function ($detail) {
                return array_merge($detail, [
                    'return_qty' => null
                ]);
            });
            return response()->json([
                'message' => 'Fetch Successfully',
                'data' => $details,
                'status' => Response::HTTP_OK
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
