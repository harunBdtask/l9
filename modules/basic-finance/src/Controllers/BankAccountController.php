<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use DB;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\Bank;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\BasicFinance\Models\BankAccount;
use SkylarkSoft\GoRMG\BasicFinance\Requests\BankAccountRequest;
use SkylarkSoft\GoRMG\BasicFinance\Services\Banks\BankWiseBankAccountCreateService;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $bank_accounts = BankAccount::query()->search($request->get('search'))->latest('id')->paginate();

        return view('basic-finance::pages.bank_accounts', compact('bank_accounts'));
    }

    public function create()
    {
        $companies = Factory::query()->pluck('factory_name', 'id')->all();
        $banks = Bank::query()->with('account')->get()->pluck('account.name', 'id');
        $units = Unit::query()->pluck('unit', 'id');

        return view('basic-finance::forms.bank_account', [
            'banks' => $banks,
            'units' => $units,
            'companies' => $companies,
            'status' => BankAccount::STATUS,
            'currency_types' => Bank::CURRENCY_TYPES,
        ]);
    }

    /**
     * @param BankAccountRequest $request
     * @param BankAccount $bankAccount
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(BankAccountRequest $request, BankAccount $bankAccount): JsonResponse
    {
        try {
            DB::beginTransaction();
            $accountId = BankWiseBankAccountCreateService::store($request);

            $bankAccount->fill($request->merge([
                'account_id' => $accountId
            ])->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Bank account created successfully',
                'data' => $bankAccount,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param BankAccount $bankAccount
     * @return JsonResponse
     */
    public function edit(BankAccount $bankAccount): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch Bank account successfully',
                'data' => $bankAccount,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param BankAccountRequest $request
     * @param BankAccount $bankAccount
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(BankAccountRequest $request, BankAccount $bankAccount): JsonResponse
    {
        try {
            DB::beginTransaction();
            BankWiseBankAccountCreateService::update($request);
            $bankAccount->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Bank account created successfully',
                'data' => $bankAccount,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse
    {
        try {
            $bankAccount->delete();
            Session::flash('success', 'Data Deleted Successfully!!');
        } catch (Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }

    public function bankShortName(Bank $bank): JsonResponse
    {
        return response()->json($bank->short_name ?? null, Response::HTTP_OK);
    }
}
