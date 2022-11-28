<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Directives\BladeDirectiveCriteria;
use Symfony\Component\HttpFoundation\Response;

class BuyerPermissionCheckApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $hasPermission = BladeDirectiveCriteria::permittedBuyers();
            $viewPermission = BladeDirectiveCriteria::permittedViewBuyers();
            $response = [
                'buyer_permission' => $hasPermission,
                'view_permission' => $viewPermission
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
