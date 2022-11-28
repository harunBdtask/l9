<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Bank;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Requests\BankRequest;
use SkylarkSoft\GoRMG\Finance\Services\Banks\BankCreateService;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $banks = Bank::query()->with('bankContacts')
            ->search($request->get('search'))
            ->latest('id')
            ->paginate();

        return view('finance::pages.banks', compact('banks'));
    }

    public function create()
    {
        return view('finance::forms.bank', [
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
            // $accountId = BankCreateService::store($request);
            // $bank->fill($request->merge(['account_id' => $accountId])->all())->save();
            $bank->fill($request->all())->save();

            if ($request->filled('bank_contacts')) {
                $bank->bankContacts()->createMany($request->get('bank_contacts'));
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
                'data' => $bank->load('account', 'bankContacts'),
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
            // BankCreateService::update($request);
            $bank->fill($request->all())->save();

            foreach ($request->input('bank_contacts') as $detail) {
                $bank->bankContacts()->updateOrCreate([
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
