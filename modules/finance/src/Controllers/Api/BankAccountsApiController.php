<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Finance\Models\BankAccount;
use Symfony\Component\HttpFoundation\Response;

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
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
