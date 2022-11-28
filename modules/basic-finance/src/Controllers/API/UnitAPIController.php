<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;

class UnitAPIController extends Controller
{
    public function getAllUnits($projectId): JsonResponse
    {
        try {
            $data = Unit::query()
                ->where('bf_project_id', $projectId)
                ->get(['id', 'unit as text']);
            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (Exception $exception) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $error = $exception->getMessage();
        }
        return response()->json([
            'status' => $status,
            'data' => $data ?? null,
            'message' => $message ?? null,
            'error' => $error ?? null,
        ], $status);
    }
}