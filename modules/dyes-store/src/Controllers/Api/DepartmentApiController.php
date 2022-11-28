<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DsDepartment;
use Symfony\Component\HttpFoundation\Response;

class DepartmentApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $departments = DsDepartment::query()->orderBy('name', 'asc')->get();

            return response()->json($departments, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
