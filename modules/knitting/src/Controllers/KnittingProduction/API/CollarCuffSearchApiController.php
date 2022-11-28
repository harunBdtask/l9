<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\KnittingProduction\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramCollarCuff;
use Symfony\Component\HttpFoundation\Response;

class CollarCuffSearchApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $knitProgramCollarCuff = KnittingProgramCollarCuff::query()
                ->where('knitting_program_id', $request->get('knitting_program_id'))
                ->get()
                ->map(function ($collection) {
                    return [
                        'knitting_program_id' => $collection->knitting_program_id,
                        'knitting_program_collar_cuff_id' => $collection->id,
                        'gmt_color_id' => $collection->gmt_color_id,
                        'gmt_color' => $collection->gmt_color,
                        'size_id' => $collection->size_id,
                        'size' => $collection->size,
                        'booking_item_size' => $collection->booking_item_size,
                        'program_item_size' => $collection->program_item_size,
                        'booking_qty' => $collection->booking_qty,
                        'excess_percentage' => $collection->excess_percentage,
                        'program_qty' => $collection->program_qty,
                        'balance_qty' => $collection->program_qty,
                        'knitting_program_roll_id' => null,
                        'production_qty' => null,
                    ];
                });

            return response()->json($knitProgramCollarCuff, Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
