<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Program;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramColorsQty;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramStripeDetail;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;
use SkylarkSoft\GoRMG\Knitting\Requests\KnitProgramStripeFormRequest;
use Symfony\Component\HttpFoundation\Response;

class ProgramStripeDetailsController extends Controller
{
    /**
     * @param KnittingProgram $knittingProgram
     * @return JsonResponse
     */

    public function index(KnittingProgram $knittingProgram): JsonResponse
    {
        try {
            $knittingProgram->load('stripeDetails.', 'planInfo.programmable.bookingDetails');
            $planningInfo = PlanningInfo::query()
                ->with(['bodyPart', 'programmable.bookingDetails'])
                ->findOrFail($knittingProgram->plan_info_id);
            $stripeDetails = KnittingProgramStripeDetail::query()
                ->where('knitting_program_id', $knittingProgram->id)
                ->first();
            $data['information'] = [
                'knitting_program_id' => $knittingProgram->id,
                'fabric_nature' => $planningInfo->fabric_nature,
                'fabric_colors' => $knittingProgram->fabric_colors,
                'fabric_nature_id' => $planningInfo->fabric_nature_id,
                'body_part' => optional($planningInfo->bodyPart)->name,
                'fabric_description' => $planningInfo->fabric_description,
                'item_color_id' => optional($stripeDetails)->item_color_id,
            ];
            $data['stripe_details'] = optional($stripeDetails)->stripe_details;
            $data['colors'] = KnittingProgramColorsQty::query()
                ->where([
                    'knitting_program_id' => $knittingProgram->id,
                    'plan_info_id' => $knittingProgram->plan_info_id,
                ])
                ->get()->map(function ($color) {
                    return [
                        'id' => $color['item_color_id'],
                        'text' => $color['item_color']
                    ];
                });

            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param KnitProgramStripeFormRequest $request
     * @param KnittingProgram $knittingProgram
     * @return JsonResponse
     */
    public function store(KnitProgramStripeFormRequest $request, KnittingProgram $knittingProgram): JsonResponse
    {
        try {
            $uniqueItems = [
                'body_part' => $request->input('body_part'),
                'item_color_id' => $request->input('item_color_id'),
                'fabric_nature_id' => $request->input('fabric_nature_id'),
                'fabric_description' => $request->input('fabric_description'),
            ];
            $knittingProgram->stripeDetails()->updateOrCreate($uniqueItems, array_except($request->all(), $uniqueItems));
            return response()->json($knittingProgram, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
