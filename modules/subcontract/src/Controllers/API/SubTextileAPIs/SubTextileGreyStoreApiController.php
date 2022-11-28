<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubContractGreyStore;
use Symfony\Component\HttpFoundation\Response;

class SubTextileGreyStoreApiController extends Controller
{
    /**
     * @param Request $request
     * @param $factoryId
     * @return JsonResponse
     */
    public function __invoke(Request $request, $factoryId): JsonResponse
    {
        try {
            $grey_stores = SubContractGreyStore::query()
                ->where('factory_id', $factoryId)
                ->get(['id', 'name as text']);

            return response()->json($grey_stores, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
