<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Planing\Services\ContainerProfileService;
use Symfony\Component\HttpFoundation\Response;

class ContainerProfileApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            if ($request->query('id')) {
                $containerProfiles = ContainerProfileService::containerProfilesEdit($request);
            } else {
                $containerProfiles = ContainerProfileService::containerProfiles($request);
            }

            return response()->json([
                'message' => 'Fetch container profile successfully',
                'data' => $containerProfiles,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
