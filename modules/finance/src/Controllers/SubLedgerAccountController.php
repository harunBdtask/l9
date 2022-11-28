<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Requests\SubLedgerAccountFormRequest;
use SkylarkSoft\GoRMG\Finance\Services\AccountCode\AccountCodeStrategy;
use Symfony\Component\HttpFoundation\Response;

class SubLedgerAccountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $controlAccounts = Account::query()->whereHas('accountInfo', function ($query) use ($request) {
                $query->where([
                    'parent_account_id' => $request->get('parent_account_id'),
                    'group_account_id' => $request->get('group_account_id'),
                    'control_account_id' => $request->get('control_account_id'),
                    'ledger_account_id' => $request->get('ledger_account_id'),
                ]);
            })->subLedgerAccounts()->get();
            return response()->json($controlAccounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(SubLedgerAccountFormRequest $request, Account $account): JsonResponse
    {
        try {
            $accountCode = (new AccountCodeStrategy())->setStrategy(Account::SUB_LEDGER)
                ->setType($request->get('type_id'))
                ->setParentId($request->get('parent_account_id'))
                ->setGroupId($request->get('group_account_id'))
                ->setControlId($request->get('control_account_id'))
                ->setLedgerId($request->get('ledger_account_id'))
                ->generate();
            $account->fill($request->merge(['code' => $accountCode])->all())->save();
            $accountInfo = new AccountInfo();
            $accountInfo->accounts_id = $account->id;
            $accountInfo->parent_account_id = $request->get('parent_account_id');
            $accountInfo->group_account_id = $request->get('group_account_id');
            $accountInfo->control_account_id = $request->get('control_account_id');
            $accountInfo->ledger_account_id = $request->get('ledger_account_id');
            $accountInfo->save();

            return response()->json($account, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
