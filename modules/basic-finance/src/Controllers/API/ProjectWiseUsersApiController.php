<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;

class ProjectWiseUsersApiController extends Controller
{
    public function getProjectWiseUsers($companyId, $projectId): JsonResponse
    {
        try {
            $project = Project::query()
                ->where('factory_id', $companyId)
                ->where('id', $projectId)
                ->first();
            $users = [];
            if($project->user_ids !== null) {
                $users = User::query()
                    ->where('factory_id', $companyId)
                    ->whereIn('id', $project->user_ids)
                    ->get(['id', 'screen_name as text']);
            }
            return response()->json($users, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
