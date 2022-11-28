<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\API;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class DefaultValueController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $data['factory_id'] = Factory::query()->first()['id'];
            $data['today_date'] = Carbon::now()->format('Y-m-d');

            return response()->json([
                'message' => 'Fetch Successfully',
                'data' => $data,
                'status' => Response::HTTP_OK,
            ],Response::HTTP_OK);
        } catch(Exception $exception){
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}