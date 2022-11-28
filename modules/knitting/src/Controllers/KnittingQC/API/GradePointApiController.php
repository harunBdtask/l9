<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\KnittingQC\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnitFabricGradeSetting;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class GradePointApiController extends Controller
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {
        $maxGradePoint = KnitFabricGradeSetting::query()->select(\DB::raw('MAX(abs(`to`)) as max'))->first()->max;
        $minGradePoint = KnitFabricGradeSetting::query()->select(\DB::raw('MIN(abs(`from`)) as min'))->first()->min;

        $request->validate([
            'qc_grade_point' => "required|lt:$maxGradePoint|gt:$minGradePoint"
        ]);

        try {
            $point = $request->get('qc_grade_point');
            $gradePoint = KnitFabricGradeSetting::query()
                ->whereRaw('ABS(`from`) <= ?', $point)
                ->whereRaw('ABS(`to`) >= ?', $point)
                ->firstOr(function () use ($point) {
                    return KnitFabricGradeSetting::query()
                        ->whereRaw('ABS(`to`) <= ?', $point)
                        ->orderByRaw('ABS(`to`) DESC')->first();
                });
            return response()->json($gradePoint, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //SELECT * FROM `knit_fabric_grade_settings` WHERE 101 >= `to` ORDER BY ABS(`to`) DESC;

    }
}
