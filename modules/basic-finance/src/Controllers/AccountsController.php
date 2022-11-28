<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\BasicFinance\Actions\BankAccountCreate;
use SkylarkSoft\GoRMG\BasicFinance\Actions\BankCreate;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\AccountsAPIControllerV2;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;
use SkylarkSoft\GoRMG\BasicFinance\Requests\AccountRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        return view(PackageConst::VIEW_NAMESPACE . '::pages.accounts-root');
    }
    public function index2(Request $request)
    {
        $AccountsAPIControllerV2 = new AccountsAPIControllerV2 ;
        $data = $AccountsAPIControllerV2->fetchData();
        $types = Account::$types;
//dd($data);
        return view(PackageConst::VIEW_NAMESPACE . '::pages.accounts-root-v2', [
            'data' => $data,
            'types' => $types,
        ]);
    }
    public function create()
    {
        $accounts = Account::query()->factoryFilter()->get()->map(function ($item) {
            return (object)[
                'id' => $item->id,
                'name' => $item->name . ' (' . $item->code . ')'
            ];
        })->pluck('name', 'id')->all();

        return view(PackageConst::VIEW_NAMESPACE . '::forms.account', [
            'ac_types' => Account::$types,
            'accounts' => $accounts,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(AccountRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $account = new Account();
            $account->fill($request->all());
            $account->save();
            if ($account->parent_ac == Account::BANK_ACCOUNT) {
                (new BankCreate())->handle($account->id);
            }

            if ($account->parentAc->parent_ac == Account::BANK_ACCOUNT) {
                (new BankAccountCreate())->handle($account);
            }
            DB::commit();
            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (Exception $e) {
            DB::rollBack();
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $error = $e->getMessage();
            $message = \SOMETHING_WENT_WRONG;
        }

        return response()->json([
            'data' => $account ?? null,
            'error' => $error ?? null,
            'message' => $message ?? null,
        ], $status);
    }

    public function edit($id)
    {
        $account = Account::query()->factoryFilter()->findOrFail($id);

        $accounts = Account::query()->factoryFilter()->get()
            ->filter(function ($item) use ($account) {
                return $item->id != $account->id;
            })
            ->map(function ($item) {
                $account = [
                    'id' => $item->id,
                    'name' => $item->name . ' (' . $item->code . ')'
                ];

                return $account;
            })->pluck('name', 'id')->all();

        return view(PackageConst::VIEW_NAMESPACE . '::forms.account', [
            'ac_types' => Account::$types,
            'accounts' => $accounts,
            'account' => $account
        ]);
    }

    public function update(Account $account, AccountRequest $request)
    {
        try {
            DB::beginTransaction();
            $account->fill($request->all());
            $account->save();
            DB::commit();
            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (Exception $e) {
            DB::rollBack();
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $error = $e->getMessage();
            $message = \SOMETHING_WENT_WRONG;
        }

        return response()->json([
            'data' => $account ?? null,
            'error' => $error ?? null,
            'message' => $message ?? null,
        ], $status);
    }

    public function fetchAccounts(Request $request): JsonResponse
    {
        return response()->json(Account::query()->factoryFilter()->where('type_id', $request->get('type'))->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name . ' (' . $item->code . ')',
                    'text' => $item->name . ' (' . $item->code . ')',
                ];
            }));
    }

    public function fetchAccountTypes()
    {
        try {
            $data = collect(Account::$types)->map(function($type, $type_id) {
                return [
                    'id' => $type_id,
                    'text' => $type,
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
            'data' => $data ?? [],
            'status' => $status ?? null,
            'message' => $message ?? null,
            'error' => $error ?? null,
        ]);
    }
}
