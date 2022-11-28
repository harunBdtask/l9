<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Program;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramCollarCuff;
use Symfony\Component\HttpFoundation\Response;

class ProgramCollarCuffDetailsController extends Controller
{
    /**
     * @param KnittingProgram $knittingProgram
     * @return JsonResponse
     */

    public function index(KnittingProgram $knittingProgram): JsonResponse
    {
        try {
            $knittingProgram->load('collarCuffs');
            return response()->json($knittingProgram, Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param $knittingProgram
     * @return JsonResponse
     */

    public function store(Request $request, $knittingProgram): JsonResponse
    {
        try {
            $collarCuffDetails = $request->get('details');
            $diffIds = [];
            foreach ($collarCuffDetails as $collarCuff) {
                $knittingProgramCollarCuff = KnittingProgramCollarCuff::query()
                    ->updateOrCreate([
                        'knitting_program_id' => $collarCuff['knitting_program_id'],
                        'gmt_color_id' => $collarCuff['gmt_color_id'],
                        'size_id' => $collarCuff['size_id']
                    ], $collarCuff);

                $diffIds[] = $knittingProgramCollarCuff['id'];
            }
            KnittingProgramCollarCuff::query()
                ->where('knitting_program_id', $knittingProgram)
                ->whereNotIn('id', $diffIds)
                ->delete();

            return response()->json(['Success'], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
