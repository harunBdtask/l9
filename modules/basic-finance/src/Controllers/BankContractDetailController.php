<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\BankContractDetail;
use Symfony\Component\HttpFoundation\Response;

class BankContractDetailController extends Controller
{

    public function destroy(BankContractDetail $bankContractDetail): JsonResponse
    {
        try {
            $bankContractDetail->delete();

            return response()->json([
                'message' => 'Bank contract detail deleted successfully',
                'data' => $bankContractDetail,
                'status' => Response::HTTP_NO_CONTENT,
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
