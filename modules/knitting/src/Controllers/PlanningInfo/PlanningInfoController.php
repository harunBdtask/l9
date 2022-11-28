<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\PlanningInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfoDetail;
use Symfony\Component\HttpFoundation\Response;

class PlanningInfoController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $planningInfoDetail = PlanningInfoDetail::query()->insert($request->all());
            return response()->json($planningInfoDetail, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(Request $request): JsonResponse
    {
        try {
            $planningInfoDetail = PlanningInfoDetail::query()->insert($request->all());
            return response()->json($planningInfoDetail, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(PlanningInfo $planningInfo): JsonResponse
    {
        try {
            $planningInfo->load([
                'knittingPrograms.knittingParty:id,factory_name',
                'knittingPrograms.knitting_program_colors_qtys',
                'knittingPrograms.factory:id,factory_name',
                'knittingPrograms.colorRange'
            ]);
            return response()->json($planningInfo, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProgramType($programId): JsonResponse
    {
        $type = KnittingProgram::query()
            ->where('id', $programId)
            ->with('planInfo')
            ->first(['id', 'plan_info_id'])->planInfo->programmable_type ?? '';

        return response()->json(class_basename($type));
    }

    public function getProgramColorPreview(PlanningInfo $planningInfo): JsonResponse
    {
        try {
            $views = array();
            $planningInfo->load('knittingPrograms.knitting_program_colors_qtys');
            $planningInfo->knittingPrograms->each(function($program) use(&$views) {
                $views[] = view('knitting::program.program_color_preview', [
                    'program' => $program
                ])->render();
            });

            return response()->json($views, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
