<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Requests\ControlAccountFormRequest;
use SkylarkSoft\GoRMG\Finance\Services\AccountCode\AccountCodeStrategy;
use Symfony\Component\HttpFoundation\Response;

class ControlAccountController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        try {
            $controlAccounts = Account::query()->whereHas('accountInfo', function ($query) use ($request) {
                $query->where([
                    'parent_account_id' => $request->get('parent_account_id'),
                    'group_account_id' => $request->get('group_account_id'),
                ]);
            })->controlAccounts()->get();
            return response()->json($controlAccounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ControlAccountFormRequest $request, Account $account): JsonResponse
    {
        try {
            $account->fill($request->merge(['code' => $this->accountCode($request)])->all())->save();

            $accountInfo = new AccountInfo();
            $accountInfo->accounts_id = $account->id;
            $accountInfo->parent_account_id = $request->get('parent_account_id');
            $accountInfo->group_account_id = $request->get('group_account_id');
            $accountInfo->save();

            $groupAccount = Account::query()->findOrFail($request->get('group_account_id'));
            $groupAccount->update(['is_transactional' => 0]);

            return response()->json($account, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function accountCode(Request $request)
    {
        return (new AccountCodeStrategy())->setStrategy(Account::CONTROL)
            ->setType($request->get('type_id'))
            ->setParentId($request->get('parent_account_id'))
            ->setGroupId($request->get('group_account_id'))
            ->generate();
    }
}
