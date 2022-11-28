<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Operator;
use Symfony\Component\HttpFoundation\Response;

class OperatorApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $operators = Operator::query()
                ->when($request->get('type'), Filter::applyFilter('operator_type', $request->get('type')))
                ->get()
                ->map(function ($collection) {
                    return [
                        'id' => $collection->id,
                        'text' => $collection->operator_name,
                        'operator_code' => $collection->operator_code,
                        'operator_type' => $collection->operator_type,
                    ];
                });
            return response()->json($operators, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
