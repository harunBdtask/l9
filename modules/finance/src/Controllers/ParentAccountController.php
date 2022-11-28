<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use DB;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Requests\ParentAccountFormRequest;
use SkylarkSoft\GoRMG\Finance\Services\AccountCode\AccountCodeStrategy;

class ParentAccountController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        try {
            $parentAccounts = Account::query()->parentAccounts($request->get('type_id'))->get();
            return response()->json($parentAccounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ParentAccountFormRequest $request, Account $account): JsonResponse
    {
        try {
            $requestData = $request->all();
            $requestData['code'] = $this->accountCode($request);
            $account->fill($requestData)->save();
            return response()->json($account, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy(Account $account)
    {
        try {
            DB::beginTransaction();
            $account->delete();
            $account->accountInfo()->delete();
            DB::commit();
            Session::flash('alert-success', 'Account Deleted Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('alert-warning', "{$exception->getMessage()}");
        } finally {
            return response()->json([]);
        }
    }

    public function accountCode(Request $request)
    {
        return (new AccountCodeStrategy)->setStrategy(Account::PARENT)
            ->setType($request->get('type_id'))
            ->generate();
    }
}
