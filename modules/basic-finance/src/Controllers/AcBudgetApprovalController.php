<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;
use SkylarkSoft\GoRMG\BasicFinance\Models\AcBudget;
use SkylarkSoft\GoRMG\BasicFinance\Models\AcBudgetApproval;
use SkylarkSoft\GoRMG\BasicFinance\Services\AcBudgetCodeGenerator;
use SkylarkSoft\GoRMG\BasicFinance\Requests\AcBudgetApprovalRequest;
use SkylarkSoft\GoRMG\BasicFinance\Services\AcBudgetPreviousMonthAmount;

class AcBudgetApprovalController extends Controller
{
    public function getBudgetDetails(Request $request)
    {
        $code = AcBudgetCodeGenerator::currentMonthCode($request);
        $previousMonthCode = AcBudgetCodeGenerator::previousMonthCode($request->get('month'));

        $previousApprovals = AcBudgetApproval::query()->factoryFilter()->where('code', $code)->first();

        if (isset($previousApprovals)) {
            return response()->json('Already budget approved...', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $budget = AcBudget::query()->factoryFilter()
            ->with('details')->where('code', $code)
            ->first();

        if (!isset($budget)) {
            return response()->json('No Budget Found...', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return [
            'id' => $budget->id,
            'month' => $budget->year . '-' . $budget->month,
            'date' => $budget->date,
            'code' => $budget->code,
            'total_amount' => $budget->total_amount,
            'details' => $budget->details->map(function ($detail) use ($code, $previousMonthCode) {

                $previousApprovedAmount = AcBudgetApproval::query()->factoryFilter()->where('bf_account_id', $detail->bf_account_id)
                    ->where('code', $code)->sum('apprv_amount');

                return [
                    'id' => null,
                    'bf_ac_budget_id' => $detail->bf_ac_budget_id,
                    'bf_ac_budget_detail_id' => $detail->id,
                    'bf_account_id' => $detail->bf_account_id,
                    'bf_account_name' => $detail->bfAccount->name,
                    'date' => date('Y-m-d'),
                    'code' => $code,
                    'previous_month_amount' => AcBudgetPreviousMonthAmount::prevApprovalAmount($detail->bf_account_id, $previousMonthCode),
                    'amount' => $detail->amount,
                    'previous_amount' => $previousApprovedAmount,
                    'apprv_amount' => $detail->amount,
                    'remarks' => null,
                ];
            })
        ];
    }

    public function index(Request $request)
    {
        $budgetApprovals = AcBudgetApproval::query()->factoryFilter()->orderByDesc('id')->get();

        return view(PackageConst::VIEW_NAMESPACE . '::pages.budget.approval', compact('budgetApprovals'));
    }

    public function create()
    {
        return view(PackageConst::VIEW_NAMESPACE . '::forms.budget.approval');
    }

    public function store(AcBudgetApprovalRequest $request): JsonResponse
    {
        try {
            foreach ($request->all() as $value) {
                $value['date'] = date('Y-m-d');
                $approval = AcBudgetApproval::query()->factoryFilter()->findOrNew($value['id'] ?? null);
                $approval->fill($value)->save();
            }

            return response()->json(['message' => 'Data stored successfully'], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view($id, Request $request)
    {
        $approval = AcBudgetApproval::query()->factoryFilter()->where(['bf_ac_budget_id' => $id, 'date' => $request->get('date')])
            ->orderByDesc('id')->get()->groupBy('bf_account_id')->map(function ($data) {
                $previousMonthCode = AcBudgetCodeGenerator::previousMonthCode($data->first()->code);
                return [
                    'id' => $data->first()['id'],
                    'budget_id' => $data->first()['bf_ac_budget_id'],
                    'budget_date' => $data->first()->acBudget->date,
                    'date' => $data->first()['date'],
                    'code' => $data->first()['code'],
                    'account' => $data->first()->bfAccount->name,
                    'prev_month_amount' => AcBudgetPreviousMonthAmount::prevApprovalAmount($data->first()->bf_account_id, $previousMonthCode),
                    'budget_amount' => $data->first()->acBudgetDetail->amount,
                    'approved_amount' => $data->sum('apprv_amount'),
                    'budget_remarks' => $data->first()->acBudgetDetail->remarks,
                    'approved_remarks' => $data->first()->remarks,
                ];
            });

        return view(PackageConst::PACKAGE_NAME . '::pages.budget.approval_view', compact('approval'));
    }
}
