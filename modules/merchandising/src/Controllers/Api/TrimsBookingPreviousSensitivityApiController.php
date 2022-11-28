<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\TrimsSensitivityVariable;
use Symfony\Component\HttpFoundation\Response;

class TrimsBookingPreviousSensitivityApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $variable = TrimsSensitivityVariable::query()
                ->where('factory_id', auth()->user()->factory_id)
                ->first();

            $sensitivity = (isset($variable) && $variable['sensitivity_variable'] == 1)
                ? TrimsBookingDetails::query()
                ->where('budget_unique_id', $request->get('budget_unique_id'))
                ->where('item_id', $request->get('item_id'))
                ->whereNotNull('sensitivity')
                ->first()['sensitivity'] ?? null
                : null;

            return response()->json([
                'message' => 'Fetch sensitivity successfully',
                'data' => $sensitivity,
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
