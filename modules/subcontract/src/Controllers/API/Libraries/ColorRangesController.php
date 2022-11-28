<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorRange;
use Symfony\Component\HttpFoundation\Response;

class ColorRangesController extends Controller
{
    public function __invoke()
    {
        try {
            $colorRangers = ColorRange::all(['id', 'name as text']);

            return response()->json([
                'message' => 'Fetch color range successfully',
                'data' => $colorRangers,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
