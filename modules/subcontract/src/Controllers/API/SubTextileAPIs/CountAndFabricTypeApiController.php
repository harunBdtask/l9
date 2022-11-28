<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use Symfony\Component\HttpFoundation\Response;

class CountAndFabricTypeApiController extends Controller
{
    public function __invoke(NewFabricComposition $composition): JsonResponse
    {
        try {
            $composition->load('newFabricCompositionDetails');

            $newFabricCompositionDetails = $composition->newFabricCompositionDetails()
                ->with('yarnCount', 'compositionType')
                ->first();

            if (! isset($newFabricCompositionDetails)) {
                return response()->json([
                    'message' => 'No fabric composition details found',
                    'data' => null,
                    'status' => Response::HTTP_NOT_FOUND,
                ], Response::HTTP_NOT_FOUND);
            }

            $yarnCount = $newFabricCompositionDetails->yarnCount->yarn_count;
            $type = $newFabricCompositionDetails->compositionType->name;

            return response()->json([
                'message' => 'Fetch fabric composition wise yarn count and composition type',
                'data' => $yarnCount . ($yarnCount ? ', ' : '') . $type,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getLine(),
                'line' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
