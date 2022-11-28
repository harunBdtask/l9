<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStorageLocation;
use Symfony\Component\HttpFoundation\Response;

class StorageLocationApiController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $storageLocations = DsStorageLocation::query()->orderby('name', 'asc')->get();

        return response()->json($storageLocations, Response::HTTP_OK);
    }

}
