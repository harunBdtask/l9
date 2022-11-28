<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use Symfony\Component\HttpFoundation\Response;

class ProjectWiseUnitApiController extends Controller
{

    public function  __invoke($companyId,$projectId): jsonResponse
    {
        try{

            if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
                $units = Unit::query()->where('factory_id', $companyId)->where('bf_project_id', $projectId)
                    ->get(['id', 'unit as text']);
            }else{
                $id = (string)(\Auth::id());
                $units = Unit::query()->where('factory_id', $companyId)->where('bf_project_id', $projectId)
                    ->whereJsonContains('user_ids', [$id])
                    ->get(['id', 'unit as text']);
            }
            return response()->json($units, Response::HTTP_OK);
        }catch (Exception $exception){
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
