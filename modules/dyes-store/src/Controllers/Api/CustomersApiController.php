<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DsCustomer;
use Symfony\Component\HttpFoundation\Response;

class CustomersApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $customers = DsCustomer::query()->orderBy('name', 'asc')->get();

            return response()->json($customers, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

}
