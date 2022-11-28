<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;

class CompanyApiController extends Controller
{
    public function fetchFactories(): JsonResponse
    {
        try {
            $factories = Factory::all([
                'id',
                'factory_name as text',
                'factory_address as location'
            ]);

            return response()->json($factories, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
