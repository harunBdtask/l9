<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Finance\Models\FundRequisitionPurpose;
use Symfony\Component\HttpFoundation\Response;

class PurposesApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $purposes = FundRequisitionPurpose::query()->get()->map(function ($purpose) {
                return [
                    'id' => $purpose->id,
                    'text' => $purpose->purpose,
                ];
            });

            return response()->json($purposes, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
