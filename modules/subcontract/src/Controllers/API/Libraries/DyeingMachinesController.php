<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use Symfony\Component\HttpFoundation\Response;

class DyeingMachinesController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $dyeingMachines = DyeingMachine::query()->active()->get(['id', 'name as text', 'capacity']);

            return response()->json([
                'message' => 'Fetch dyeing machine successfully 🙂',
                'data' => $dyeingMachines,
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
