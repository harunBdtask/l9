<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class ControlAccountsApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $controlAccounts = Account::query()->where('account_type', Account::CONTROL)->get()->map(
                function ($account) {
                    return [
                        'id' => $account->id,
                        'text' => "{$account->name} ({$account->code})",
                    ];
                }
            );

            return response()->json($controlAccounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
