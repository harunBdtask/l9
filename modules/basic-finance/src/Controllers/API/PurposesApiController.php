<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\FundRequisitionPurpose;

class PurposesApiController extends Controller
{
    public function getPurposes(): JsonResponse
    {
        try {
            $purposes = FundRequisitionPurpose::query()->get(['id', 'purpose as text']);

            return response()->json($purposes, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
