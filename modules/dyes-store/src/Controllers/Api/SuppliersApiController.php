<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Symfony\Component\HttpFoundation\Response;

class SuppliersApiController extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $suppliers = Supplier::query()->orderBy('name', 'asc')->get();

        return response()->json($suppliers, Response::HTTP_OK);
    }
}
