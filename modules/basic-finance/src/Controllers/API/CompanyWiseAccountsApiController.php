<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class CompanyWiseAccountsApiController extends Controller
{
    public function cashAccounts($companyId): JsonResponse
    {
        try {
                $cashAccounts = Account::query()->where('factory_id', $companyId)
                    ->where('code','LIKE','1201001%')
                    ->where('is_active',1)
                    ->where('is_transactional',1)
                    ->get(['code as id', 'name as text']);

            return response()->json($cashAccounts, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function bankAccounts($companyId): JsonResponse
    {
        try {
            $bankAccounts = Account::query()->where('factory_id', $companyId)
                ->where('code','LIKE','1201002%')
                ->where('is_active',1)
                ->where('is_transactional',1)
                ->get(['code as id', 'name as text']);
            return response()->json($bankAccounts, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function allAccounts($companyId): JsonResponse
    {
        try {
            $allAccounts = Account::query()->where('factory_id', $companyId)
                ->where('code','LIKE','1201%')
                ->where('is_active',1)
                ->where('is_transactional',1)
                ->get(['code as id', 'name as text']);
            return response()->json($allAccounts, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
