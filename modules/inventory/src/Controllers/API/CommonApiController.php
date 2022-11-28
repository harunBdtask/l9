<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;

class CommonApiController extends Controller
{
    public function fabricReceiveBatchNo(): JsonResponse
    {
        $batchNo = FabricReceiveDetail::query()->get()->map(function ($value) {
            return [
                'id' => $value['batch_no'],
                'text' => $value['batch_no'],
            ];
        });

        return response()->json($batchNo, Response::HTTP_OK);
    }
}
