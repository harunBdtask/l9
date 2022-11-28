<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Requests\AccountRequest;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        return view('finance::pages.accounts');
    }

    public function getAccountList(Request $request)
    {
        $orderType = $request->get('order_type') ?? 'DESC';

        $accounts = Account::query()
            ->has('accountInfo.parentAccount')
            ->has('accountInfo.groupAccount')
            ->with('accountInfo.ledgerAccount')
            ->where('is_transactional', 1)
            ->orderBy('id', $orderType)
            ->search($request)
            ->paginate(30);

        return view('finance::pages.accounts-table', [
            'accounts' => $accounts
        ]);
    }

    public function create()
    {
        return view('finance::forms.chart_of_account', [
            'ac_types' => Account::$types,
            'status' => Account::STATUS,
        ]);
    }

    public function store(AccountRequest $request)
    {
        $account = new Account();
        $account->name = $request->get('name');
        $account->code = $request->get('code');
        $account->particulars = $request->get('particulars');
        $account->type_id = $request->get('type_id');
        $account->parent_ac = $request->get('parent_ac');
        $account->created_by = \Auth::id();
        $account->updated_by = \Auth::id();

        $account->save();

        return redirect('finance/accounts');
    }

    public function edit($id)
    {
         $account = Account::query()->with([
            'accountInfo.parentAccount',
            'accountInfo.groupAccount',
            'accountInfo.controlAccount',
            'accountInfo.ledgerAccount',
        ])->findOrFail($id);

        $parentsList = Account::query()->where('type_id', $account->type_id)->where('account_type', 1)->get(['id', 'name']);

        return view('finance::forms.chart_of_account_edit', [
            'ac_types' => Account::$types,
            'account' => $account,
            'parents' => $parentsList,
            'status' => Account::STATUS
        ]);
    }

    public function update($id, AccountRequest $request)
    {
        $account = Account::findOrFail($id);

        $account->name = $request->get('name');
        $account->code = $request->get('code');
        $account->particulars = $request->get('particulars');
        $account->type_id = $request->get('type_id');
        $account->parent_ac = $request->get('parent_ac');
        $account->updated_by = \Auth::id();

        $account->save();

        return redirect('finance/accounts');
    }
}
