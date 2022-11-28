<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\BasicFinance\Models\Bank;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Requests\BankRequest;
use SkylarkSoft\GoRMG\BasicFinance\Services\Banks\BankWiseAccountCreateService;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $banks = Bank::query()->with('bankContractDetails')
            ->search($request->get('search'))
            ->latest('id')
            ->paginate();

        return view('basic-finance::pages.banks', compact('banks'));
    }

    public function create()
    {
        return view('basic-finance::forms.bank', [
            'parent_ac' => Account::BANK_ACCOUNT,
            'currency_types' => Bank::CURRENCY_TYPES,
        ]);
    }

    /**
     * @param BankRequest $request
     * @param Bank $bank
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(BankRequest $request, Bank $bank): JsonResponse
    {
        try {
            DB::beginTransaction();
            $accountId = BankWiseAccountCreateService::store($request);
            $bank->fill($request->merge(['account_id' => $accountId])->all())->save();

            if (count($request->input('bank_contract_details'))) {
                $bank->bankContractDetails()->createMany($request->input('bank_contract_details'));
            }
            DB::commit();

            return response()->json([
                'message' => 'Bank created successfully',
                'data' => $bank->load('account'),
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
     * @param Bank $bank
     * @return JsonResponse
     */
    public function edit(Bank $bank): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch Bank Data successfully',
                'data' => $bank->load('account', 'bankContractDetails'),
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param BankRequest $request
     * @param Bank $bank
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(BankRequest $request, Bank $bank): JsonResponse
    {
        try {
            DB::beginTransaction();
            BankWiseAccountCreateService::update($request);
            $bank->fill($request->all())->save();

            foreach ($request->input('bank_contract_details') as $detail) {
                $bank->bankContractDetails()->updateOrCreate([
                    'id' => $detail['id'] ?? null,
                ], $detail);
            }
            DB::commit();

            return response()->json([
                'message' => 'Bank updated successfully',
                'data' => $bank,
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

    public function destroy(Bank $bank): RedirectResponse
    {
        try {
            $bank->delete();
            Session::flash('success', 'Data Deleted Successfully!!');
        } catch (Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }
}
