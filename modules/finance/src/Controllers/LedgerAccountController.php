<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use SkylarkSoft\GoRMG\Finance\Requests\LedgerAccountFormRequest;
use SkylarkSoft\GoRMG\Finance\Requests\ControlAccountFormRequest;
use SkylarkSoft\GoRMG\Finance\Services\AccountCode\AccountCodeStrategy;

class LedgerAccountController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        try {
            $controlAccounts = Account::query()->whereHas('accountInfo', function ($query) use ($request) {
                $query->where([
                    'parent_account_id' => $request->get('parent_account_id'),
                    'group_account_id' => $request->get('group_account_id'),
                    'control_account_id' => $request->get('control_account_id'),
                ]);
            })->ledgerAccounts()->get();
            return response()->json($controlAccounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(LedgerAccountFormRequest $request, Account $account): JsonResponse
    {
        try {
            $accountCode = (new AccountCodeStrategy())->setStrategy(Account::LEDGER)
                ->setType($request->get('type_id'))
                ->setParentId($request->get('parent_account_id'))
                ->setGroupId($request->get('group_account_id'))
                ->setControlId($request->get('control_account_id'))
                ->generate();

            $account->fill($request->merge(['code' => $accountCode])->all())->save();

            $accountInfo = new AccountInfo();
            $accountInfo->accounts_id = $account->id;
            $accountInfo->parent_account_id = $request->get('parent_account_id');
            $accountInfo->group_account_id = $request->get('group_account_id');
            $accountInfo->control_account_id = $request->get('control_account_id');
            $accountInfo->save();

            $controlAccount = Account::query()->findOrFail($request->get('control_account_id'));
            $controlAccount->update(['is_transactional' => 0]);

            return response()->json($account, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function accountCode(Request $request)
    {
        return (new AccountCodeStrategy())->setStrategy(Account::LEDGER)
            ->setType($request->get('type_id'))
            ->setParentId($request->get('parent_account_id'))
            ->setGroupId($request->get('group_account_id'))
            ->setControlId($request->get('control_account_id'))
            ->generate();
    }

    // Update chart of account
    public function update(LedgerAccountFormRequest $request, Account $account)
    {

        try {

            $account->fill($request->all())->save();

            $account->accountInfo->accounts_id = $account->id;
            $account->accountInfo->parent_account_id = $request->get('parent_account_id');
            $account->accountInfo->group_account_id = $request->get('group_account_id');
            $account->accountInfo->control_account_id = $request->get('control_account_id');
            $account->accountInfo->save();
            return response()->json($account, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
