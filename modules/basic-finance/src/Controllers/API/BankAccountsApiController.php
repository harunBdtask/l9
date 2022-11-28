<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\BankAccount;

class BankAccountsApiController extends Controller
{
    public function __invoke($bankId): JsonResponse
    {
        try {
            $bankAccounts = BankAccount::query()->where('bank_id', $bankId)->get()->map(function ($bankAccount) {
                return [
                    'id' => $bankAccount->id,
                    'text' => $bankAccount->account_number,
                ];
            });

            return response()->json($bankAccounts, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBankAccounts($bankId): JsonResponse
    {
        try {
            $bankAccounts = BankAccount::query()->where('bank_id', $bankId)->get()->map(function ($bankAccount) {
                return [
                    'id' => $bankAccount->account_id,
                    'text' => $bankAccount->account_number,
                ];
            });

            return response()->json($bankAccounts, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
