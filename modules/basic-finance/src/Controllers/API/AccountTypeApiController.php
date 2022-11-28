<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\AccountType;
use Symfony\Component\HttpFoundation\Response;

class AccountTypeApiController extends Controller
{

    public function __invoke(): JsonResponse
    {
        try {
            $accountTypes = AccountType::query()->get([
                'id', 'account_type as text', 'short_form',
            ]);

            return response()->json([
                'message' => 'Fetch account types successfully',
                'data' => $accountTypes,
                'status' => Response::HTTP_OK,
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
