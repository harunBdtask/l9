<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use SkylarkSoft\GoRMG\Finance\Requests\GroupAccountFormRequest;
use SkylarkSoft\GoRMG\Finance\Services\AccountCode\AccountCodeStrategy;

class GroupAccountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $parentAccounts = Account::query()->whereHas('accountInfo', function ($query) use ($request) {
                $query->where('parent_account_id', $request->get('parent_account_id'));
            })->groupAccounts()->get();
            return response()->json($parentAccounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(GroupAccountFormRequest $request, Account $account): JsonResponse
    {
        try {
            $account->fill($request->merge(['code' => $this->accountCode($request)])->all())->save();
            $accountInfo = new AccountInfo();
            $accountInfo->accounts_id = $account->id;
            $accountInfo->parent_account_id = $request->get('parent_account_id');
            $accountInfo->save();
            return response()->json($account, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function accountCode(Request $request)
    {
        return (new AccountCodeStrategy())->setStrategy(Account::GROUP)
            ->setType($request->get('type_id'))
            ->setParentId($request->get('parent_account_id'))
            ->generate();
    }
}
