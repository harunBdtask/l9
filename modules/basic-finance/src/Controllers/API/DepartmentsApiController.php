<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;
use Symfony\Component\HttpFoundation\Response;

class DepartmentsApiController extends Controller
{

    public function __invoke(): JsonResponse
    {
        try {
            $departments = Department::query()->get(['id', 'department as text']);
            return response()->json([
                'message' => 'Fetch departments successfully',
                'data' => $departments,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
