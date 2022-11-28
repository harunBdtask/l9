<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\AccountType;
use Symfony\Component\HttpFoundation\Response;

class AccountTypeShortFormApiController extends Controller
{

    public function __invoke(int $accountTypeId): JsonResponse
    {
        try {
            $accountTypeShortForm = AccountType::query()->findOrFail($accountTypeId)['short_form'] ?? null;

            return response()->json([
                'message' => 'Fetch account type short form',
                'data' => $accountTypeShortForm,
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
