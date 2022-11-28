<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Illuminate\Http\RedirectResponse;
use SkylarkSoft\GoRMG\BasicFinance\Services\AcBudgetCodeGenerator;
use SkylarkSoft\GoRMG\BasicFinance\Services\AcBudgetPreviousMonthAmount;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\AcBudget;
use SkylarkSoft\GoRMG\BasicFinance\Models\AcBudgetDetail;
use SkylarkSoft\GoRMG\BasicFinance\Requests\AcBudgetRequest;

class AcBudgetController extends Controller
{
    public function getBudgetDetails(Request $request): JsonResponse
    {
        $code = AcBudgetCodeGenerator::currentMonthCode($request);
        $previousCode = AcBudgetCodeGenerator::previousMonthCode($request->get('month'));

        $budget = AcBudget::query()->factoryFilter()->where('code', $code)->first();
        $previousBudgets = AcBudgetDetail::query()->factoryFilter()
            ->whereRelation('bfBudget', 'code', $previousCode)
            ->get();

        if (isset($budget)) {
            return response()->json('Already budget created. Please edit from the list');
        }

        $accounts = Account::query()->factoryFilter()
            ->whereIn('type_id', [Account::EXPENSE_OP, Account::EXPENSE_NOP])
            ->get()->map(function ($account) use ($previousBudgets) {

                $previousAmount = collect($previousBudgets)->where('bf_account_id', $account->id)->first()['amount'] ?? 0.00;

                return [
                    'id' => null,
                    'bf_account_id' => $account->id,
                    'bf_account_name' => $account->name,
                    'previous_amount' => $previousAmount,
                    'amount' => 0.00,
                    'remarks' => '',
                ];
            });

        return response()->json(['code' => $code, 'accounts' => $accounts]);
    }

    public function index()
    {
        $budgets = AcBudget::query()->factoryFilter()->orderByDesc('id')->get();

        return view(PackageConst::VIEW_NAMESPACE . '::pages.budget.budgets', compact('budgets'));
    }

    public function create()
    {
        return view(PackageConst::VIEW_NAMESPACE . '::forms.budget.budget');
    }

    /**
     * @throws Throwable
     */
    public function store(AcBudgetRequest $request, AcBudget $acBudget): JsonResponse
    {
        try {
            DB::beginTransaction();
            $explode = explode('-', $request->get('month'));
            $acBudget->fill($request->merge(['month' => $explode[1], 'year' => $explode[0]])->all())->save();
            $acBudget->details()->createMany($request->input('details'));
            DB::commit();

            return response()->json(['message' => 'Data stored successfully'], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(AcBudget $acBudget): array
    {
        $previousCode = AcBudgetCodeGenerator::previousMonthCode($acBudget->code);
        $previousBudgets = AcBudgetDetail::query()->whereRelation('bfBudget', 'code', $previousCode)->get();

        return [
            'id' => $acBudget->id,
            'month' => $acBudget->year . '-' . $acBudget->month,
            'code' => $acBudget->code,
            'date' => $acBudget->date,
            'total_amount' => $acBudget->total_amount,
            'details' => $acBudget->details->map(function ($detail) use ($previousBudgets) {

                $previousAmount = collect($previousBudgets)->where('bf_account_id', $detail->bf_account_id)
                                      ->first()['amount'] ?? 0.00;

                return [
                    'id' => $detail->id,
                    'bf_ac_budget_id' => $detail->bf_ac_budget_id,
                    'bf_account_id' => $detail->bf_account_id,
                    'bf_account_name' => $detail->bfAccount->name,
                    'previous_amount' => $previousAmount ?? 0.00,
                    'amount' => $detail->amount,
                    'remarks' => $detail->remarks,
                ];
            })
        ];
    }

    public function edit()
    {
        return view(PackageConst::VIEW_NAMESPACE . '::forms.budget.budget');
    }

    /**
     * @throws Throwable
     */
    public function update(AcBudgetRequest $request, AcBudget $acBudget): JsonResponse
    {
        try {
            DB::beginTransaction();
            $acBudget->update(['total_amount' => $request->get('total_amount')]);
            foreach ($request->get('details') as $value) {
                $acBudgetDetail = AcBudgetDetail::query()->findOrFail($value['id']);
                $acBudgetDetail->fill($value)->save();
            }
            DB::commit();

            return response()->json(['message' => 'Data updated successfully'], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy(AcBudget $acBudget): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $acBudget->delete();
            $acBudget->details()->delete();
            $acBudget->approvals()->delete();
            DB::commit();
            Session::flash('success', "Data deleted Successfully");
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }

    public function view(AcBudget $acBudget)
    {
        $acBudget = [
            'id' => $acBudget->id,
            'month' => $acBudget->month,
            'year' => $acBudget->year,
            'code' => $acBudget->code,
            'date' => $acBudget->date,
            'total_amount' => $acBudget->total_amount,
            'details' => $acBudget->details->map(function ($detail) use ($acBudget) {

                $previousCode = AcBudgetCodeGenerator::previousMonthCode($acBudget->year . '-' . $acBudget->month);
                $previousMonthAmount = AcBudgetPreviousMonthAmount::prevBudgetAmount($previousCode, $detail->bf_account_id);

                return [
                    'bf_account_name' => $detail->bfAccount->name,
                    'previous_month_amount' => $previousMonthAmount ?? 0.00,
                    'amount' => $detail->amount,
                    'remarks' => $detail->remarks,
                ];
            })
        ];

        return view(PackageConst::VIEW_NAMESPACE . '::pages.budget.budget_view', compact('acBudget'));
    }
}
