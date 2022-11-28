<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;

class LoanDistributionAccountsAPIController extends Controller
{
    public function __invoke()
    {
        try {
            $data = Account::query()->where('parent_ac', '!=', null)
            ->where('is_active', 1)
            ->where('is_transactional', 1)
            ->get()->map(function ($account) {
                return [
                    'id' => $account->id,
                    'text' => $account->name,
                    'bf_account' => $account,
                ];
            });

            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }

        return \response()->json([
            'data' => $data ?? null,
            'status' => $status ?? null,
            'message' => $message ?? null,
            'error' => $error ?? null,
        ], $status);
    }
}