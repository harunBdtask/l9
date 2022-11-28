<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive;


use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class YarnSupplierApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $yarnSuppliers = Supplier::query()
                ->where('party_type', 'like', '%Yarn Supplier%')
                ->where('factory_id',request('factory_id'))
                ->orderByDesc('id')
                ->get(['id','name as text']);
            return response()->json($yarnSuppliers, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
