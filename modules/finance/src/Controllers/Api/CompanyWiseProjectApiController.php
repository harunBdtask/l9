<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\Project;

class CompanyWiseProjectApiController extends Controller
{

    public function getProjects($companyId): JsonResponse
    {
        try {
            if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
                $projects = Project::query()->where('factory_id', $companyId)
                    ->get(['id', 'project as text']);
            } else {
                $id = (string)(\Auth::id());
                $projects = Project::query()->where('factory_id', $companyId)
                    ->whereJsonContains('user_ids', [$id])
                    ->get(['id', 'project as text']);
            }

            return response()->json($projects, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllProjects($companyId): JsonResponse
    {
        try {
            $data = Project::query()
                ->where('factory_id', $companyId)
                ->get(['id', 'project as text']);
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
