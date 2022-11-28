<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Finance\Models\BankContact;
use Symfony\Component\HttpFoundation\Response;

class BankContactController extends Controller
{

    public function destroy(BankContact $bankContractDetail): JsonResponse
    {
        try {
            $bankContractDetail->delete();

            return response()->json([
                'message' => 'Bank contact detail deleted successfully',
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
