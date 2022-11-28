<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;

class CashInHadAccountsApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $accounts = Account::query()
                ->where('parent_ac', Account::CASH_IN_HAND_ACCOUNT)
                ->where('factory_id', $request->get('factory_id'))
                ->get(['id', 'name as text']);

            return response()->json([
                'message' => 'Cash in hand accounts successfully',
                'data' => $accounts,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
