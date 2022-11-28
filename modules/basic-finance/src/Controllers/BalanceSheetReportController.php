<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PDF;
use SkylarkSoft\GoRMG\BasicFinance\Exports\BalanceSheetReportExcel;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\CostCenter;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;
use SkylarkSoft\GoRMG\BasicFinance\Models\Journal;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\ReportViewService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;

class BalanceSheetReportController extends Controller
{
    private $startDate;
    private $endDate;
    private $companyId;
    private $projectId;
    private $unitId;
    private $departmentId;
    private $costCenterId;
    private $allFormattedData = [];

    public function __construct(Request $request)
    {
        if ($request->has('start_date')) {
            $this->startDate = Carbon::parse($request->get('start_date'));
        } else {
            $this->startDate = Carbon::today()->startOfMonth();
        }

        if ($request->has('end_date')) {
            $this->endDate = Carbon::parse($request->get('end_date'));
        } else {
            $this->endDate = Carbon::today()->endOfMonth();
        }

        $this->endDate->addDay();

    }

    public function fetchData(Request $request)
    {
        $this->companyId = $request->has('company_id') ? $request->get('company_id') : null;
        $this->projectId = $request->has('project_id') ? $request->get('project_id') : null;
        $this->unitId = $request->has('unit_id') ? $request->get('unit_id') : null;
        $this->departmentId = $request->has('department_id') ? $request->get('department_id') : null;
        $this->costCenterId = $request->has('cost_centre') ? $request->get('cost_centre') : null;


        $factories = Factory::all();
        $projects = $units = [];

        if ($this->companyId) {
            $projects = Project::query()->where('factory_id', $this->companyId)->get()->pluck('project', 'id');
        }
        if ($this->projectId) {
            $units = Unit::query()->where('factory_id', $this->companyId)
                ->where('bf_project_id', $this->projectId)
                ->get()->pluck('unit', 'id');
        }

        $allBalanceSheetData = $this->getAccountTypeWiseData([
            Account::ASSET,
            Account::LIABILITY,
            Account::EQUITY
        ]);
        $allBalanceSheetFormattedData = collect($this->formatedData($allBalanceSheetData))->groupBy('type_id');

        $balanceOfIncomeExpense = (($this->getTransactionalCreditBalance('4000000000000') - $this->getTransactionalDebitBalance('4000000000000'))
            - ($this->getTransactionalDebitBalance('5000000000000') - $this->getTransactionalCreditBalance('5000000000000')));

        $header = ReportViewService::for('search_info')
            ->setFactoryId($this->companyId)
            ->setFromDate($this->startDate)
            ->setToDate($this->endDate->subDay())
            ->render();

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::print.balance_sheet_v2', [
                'units' => $units,
                'projects' => $projects,
                'factories' => $factories,
//                'departments' => $departments,
//                'cost_centers' => $costCenters,
                'factoryId' => $this->companyId,
                'projectId' => $this->projectId,
                'unitId' => $this->unitId,
//                'departmentId' => $this->departmentId,
//                'costCenterId' => $this->costCenterId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'allBalanceSheetFormattedData' => $allBalanceSheetFormattedData,
                'balanceOfIncomeExpense' => $balanceOfIncomeExpense,
                'header' => $header
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('balance-sheet_' . date('d:m:Y') . '.pdf');
        }

        if ($request->get('type') == 'excel') {
            $viewData = [
                'units' => $units,
                'projects' => $projects,
                'factories' => $factories,
//                'departments' => $departments,
//                'cost_centers' => $costCenters,
                'factoryId' => $this->companyId,
                'projectId' => $this->projectId,
                'unitId' => $this->unitId,
//                'departmentId' => $this->departmentId,
//                'costCenterId' => $this->costCenterId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'allBalanceSheetFormattedData' => $allBalanceSheetFormattedData,
                'balanceOfIncomeExpense' => $balanceOfIncomeExpense,
                'header' => $header
            ];
            return Excel::download(new BalanceSheetReportExcel($viewData), 'balance-sheet_' . date('d:m:Y') . '.xlsx');
        }

        return view('basic-finance::reports.balance_sheet_v2', [
            'units' => $units,
            'projects' => $projects,
            'factories' => $factories,
//                'departments' => $departments,
//                'cost_centers' => $costCenters,
            'factoryId' => $this->companyId,
            'projectId' => $this->projectId,
            'unitId' => $this->unitId,
//                'departmentId' => $this->departmentId,
//                'costCenterId' => $this->costCenterId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'allBalanceSheetFormattedData' => $allBalanceSheetFormattedData,
            'balanceOfIncomeExpense' => $balanceOfIncomeExpense,
            'header' => $header
        ]);
    }

    private function getAccountTypeWiseData($type_id, $id = null, $space_level = 0): Collection
    {
        return Account::query()
            ->with('parentAc')
            ->whereIn('type_id', $type_id)
            ->when($id, function ($query) use ($id) {
                $query->where('parent_ac', $id);
            })
            ->when(!$id, function ($query) {
                $query->whereNull('parent_ac');
            })
            ->get()
            ->map(function ($account) use ($space_level) {
                return [
                    'id' => $account->id,
                    'level' => $account->name,
                    'type' => Account::$types[$account->type_id],
                    'name' => $account->name,
                    'code' => $account->code,
                    'type_id' => $account->type_id,
                    'parent_ac' => $account->parent_ac,
                    'factory_id' => $account->factory_id,
                    'children' => $account->id && $account->type_id && $space_level < 1 ? $this->getAccountTypeWiseData([$account->type_id], $account->id, $space_level + 1) : [],
                    'has_children' => $account->childAcs->count(),
                    'balance' => ((($account->type_id == 2) || ($account->type_id == 3)) ?
                            ($this->getTransactionalCreditBalance($account->code) - $this->getTransactionalDebitBalance($account->code)) :
                            ($this->getTransactionalDebitBalance($account->code) - $this->getTransactionalCreditBalance($account->code))) ?? 0,
                    'space_level' => $space_level + 1
                ];
            });
    }

    private function getTransactionalDebitBalance($account_code)
    {
        return Journal::query()->where('account_code', 'like', rtrim($account_code, '0') . '%')
            ->where('trn_date', '>=', $this->startDate)
            ->where('trn_date', '<', $this->endDate)
            ->where('trn_type', 'dr')
            ->when($this->companyId, function ($query) {
                $query->where('factory_id', $this->companyId);
            })->when($this->projectId, function ($query) {
                $query->where('project_id', $this->projectId);
            })->when($this->unitId, function ($query) {
                $query->where('unit_id', $this->unitId);
            })->when($this->departmentId, function ($query) {
                $query->where('department_id', $this->departmentId);
            })->when($this->costCenterId, function ($query) {
                $query->where('cost_center_id', $this->costCenterId);
            })->sum('trn_amount');
    }

    private function getTransactionalCreditBalance($account_code)
    {
        return Journal::query()->where('account_code', 'like', rtrim($account_code, '0') . '%')
            ->where('trn_date', '>=', $this->startDate)
            ->where('trn_date', '<', $this->endDate)
            ->where('trn_type', 'cr')
            ->when($this->companyId, function ($query) {
                $query->where('factory_id', $this->companyId);
            })->when($this->projectId, function ($query) {
                $query->where('project_id', $this->projectId);
            })->when($this->unitId, function ($query) {
                $query->where('unit_id', $this->unitId);
            })->when($this->departmentId, function ($query) {
                $query->where('department_id', $this->departmentId);
            })->when($this->costCenterId, function ($query) {
                $query->where('cost_center_id', $this->costCenterId);
            })->sum('trn_amount');
    }

    private function formatedData($allBalanceSheetData)
    {
        foreach ($allBalanceSheetData as $singleBalanceSheetData) {
            $level = 0;
            $formattedData['id'] = $singleBalanceSheetData['id'] ?? '';
            $formattedData['code'] = $singleBalanceSheetData['code'] ?? str_pad($singleBalanceSheetData['type_id'], 13, '0');
            $formattedData['name'] = $singleBalanceSheetData['level'] ?? '';
            $formattedData['type_id'] = $singleBalanceSheetData['type_id'] ?? '';
            $formattedData['balance'] = $singleBalanceSheetData['balance'];
            $formattedData['space_level'] = $singleBalanceSheetData['space_level'];
            array_push($this->allFormattedData, $formattedData);
            if (isset($singleBalanceSheetData['children'])) {
                $level = $level + 1;
                $this->formatedData($singleBalanceSheetData['children']);
            }
        }
        return $this->allFormattedData;
    }


}
