<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use DB;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Bank;
use SkylarkSoft\GoRMG\Finance\Models\Unit;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\AcCompany;
use SkylarkSoft\GoRMG\Finance\Models\BankAccount;
use SkylarkSoft\GoRMG\Finance\Jobs\SubLedgerAndLedgerJob;
use SkylarkSoft\GoRMG\Finance\Requests\BankAccountRequest;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
         $bank_accounts = BankAccount::query()->when($request->get('search'), function($q) use ($request){
            return $q->where('account_number', 'LIKE', $request->get('search'));
        })->with(['accountType','bank','unit','project'])->latest('id')->paginate();
        return view('finance::pages.bank_accounts', compact('bank_accounts'));
    }

    public function create()
    {
        $companies = AcCompany::query()->pluck('name', 'id')->all();
        $banks = Bank::query()->pluck('name', 'id');
        $units = Unit::query()->pluck('unit', 'id');
        $controlAccounts = Account::query()->where('account_type', Account::CONTROL)
            ->pluck('name', 'id');

        return view('finance::forms.bank_account', [
            'banks' => $banks,
            'units' => $units,
            'companies' => $companies,
            'status' => BankAccount::STATUS,
            'control_accounts' => $controlAccounts,
            'currency_types' => Bank::CURRENCY_TYPES,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(BankAccountRequest $request, BankAccount $bankAccount)
    {
        try {
            DB::beginTransaction();
            $bankAccount->fill($request->all())->save();
            SubLedgerAndLedgerJob::dispatchNow($request, $bankAccount, 'store');
            DB::commit();

            return response()->json([
                'message' => 'Data Stored Successfully!!',
                'data' => $bankAccount,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);

        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function edit($id): JsonResponse
    {
        $bankAccount = BankAccount::with('accountInfo')->where('id', $id)->first();
        try {
            return response()->json([
                'message' => 'Fetch Bank account successfully',
                'data' => $bankAccount,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function update(BankAccountRequest $request, BankAccount $bankAccount)
    {
        try {
            DB::beginTransaction();
            SubLedgerAndLedgerJob::dispatchNow($request, null, 'update');
            $bankAccount->fill($request->all())->save();
            DB::commit();
            return response()->json([
                'message' => 'Data Stored Successfully!!',
                'data' => $bankAccount,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
            
        } catch (\Exception $exception) {
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
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }

    public function bankShortName(Bank $bank): JsonResponse
    {
        return response()->json($bank->short_name ?? null, Response::HTTP_OK);
    }
}
