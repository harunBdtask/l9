<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\ChequeBookDetail;

class ChequeBookStatusTypeApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $statusTypes = ChequeBookDetail::STATUS_TYPE;

            return response()->json($statusTypes, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
