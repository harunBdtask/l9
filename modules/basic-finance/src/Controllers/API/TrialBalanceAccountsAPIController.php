<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\Journal;
use Symfony\Component\HttpFoundation\Response;

class TrialBalanceAccountsAPIController extends Controller
{
    private $startDate;
    private $endDate;
    private $allFormattedData=[];

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
        try {
            $allTrialBalanceData = $this->getAccountTypes();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        $allTrialBalanceFormattedData = $this->formatedData($allTrialBalanceData);

                if ($request->print == true) {
            return view('basic-finance::print.trial_balance_tree', [
                'allTrialBalanceFormattedData' =>  $allTrialBalanceFormattedData,
                'report_title' => 'Trial Balance',
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }

        return view('basic-finance::reports.trial_balance_tree', [
            'allTrialBalanceFormattedData' =>  $allTrialBalanceFormattedData,
        ]);
    }

    private function formatedData($allTrialBalanceData)
    {
        foreach($allTrialBalanceData as $singleTrialBalanceData){
            $level = 0;
            $formattedData['code'] = $singleTrialBalanceData['code'] ?? str_pad($singleTrialBalanceData['type_id'], 13, '0');
            $formattedData['name'] = $singleTrialBalanceData['level'] ?? '';
            $formattedData['openingDebitBalance'] = $singleTrialBalanceData['openingDebit'] ?? 0;
            $formattedData['openingCreditBalance'] = $singleTrialBalanceData['openingCredit'] ?? 0;
            $formattedData['transactionDebitBalance'] = $singleTrialBalanceData['transactionDebit'] ?? 0;
            $formattedData['transactionCreditBalance'] = $singleTrialBalanceData['transactionCredit'] ?? 0;
            $formattedData['closingDebitBalance'] = $singleTrialBalanceData['closingDebit'] ?? 0;
            $formattedData['closingCreditBalance'] = $singleTrialBalanceData['closingCredit'] ?? 0;
            $formattedData['space_level'] = $singleTrialBalanceData['space_level'];
            array_push($this->allFormattedData,$formattedData);
            if(isset($singleTrialBalanceData['children'])){
                $level = $level + 1;
                $this->formatedData($singleTrialBalanceData['children']);
            }
        }
        return $this->allFormattedData;
    }

    private function getAccountTypeWiseData($type_id, $id = null, $space_level=0): Collection
    {
        return Account::query()
            ->with('parentAc')
            ->where('type_id', $type_id)
            ->when($id, function ($query) use ($id) {
                $query->where('parent_ac', $id);
            })
            ->when(!$id, function ($query) {
                $query->whereNull('parent_ac');
            })
            ->get()
            ->map(function ($account, $key) use ($type_id, $id, $space_level) {
//                $getChildrenId = collect($account->childAcs)->pluck('id')->toArray();
                return [
                    'id' => $account->id,
                    'level' => $account->name,
                    'type' => Account::$types[$account->type_id],
                    'name' => $account->name,
                    'code' => $account->code,
                    'type_id' => $account->type_id,
                    'parent_ac' => $account->parent_ac,
                    'factory_id' => $account->factory_id,
                    'children' => $account->id && $account->type_id ? $this->getAccountTypeWiseData($account->type_id, $account->id, $space_level+1) : [],
                    'has_children' => $account->childAcs->count(),
                    'openingDebit' => $this->getOpeningDebitBalance($account->code),
                    'openingCredit' => $this->getOpeningCreditBalance($account->code),
                    'transactionDebit' => $this->getTransactionalDebitBalance($account->code),
                    'transactionCredit' => $this->getTransactionalCreditBalance($account->code),
                    'closingDebit' => $this->getClosingDebitBalance($account->code),
                    'closingCredit' => $this->getClosingCreditBalance($account->code),
                    'space_level' => $space_level+1
                ];
            });
    }

    private function getAccountTypes(): Collection
    {
        $space_level = 0;
        return collect(Account::$types)
            ->map(function ($level, $type_id) use($space_level){
                $has_children = count($this->getAccountTypeWiseData($type_id));
//                $getChildrenId = collect($this->getAccountTypeWiseData($type_id))->pluck('id');
                return [
                    'id' => null,
                    'level' => $level,
                    'type' => $level,
                    'name' => null,
                    'code' => null,
                    'type_id' => $type_id,
                    'parent_ac' => null,
                    'factory_id' => factoryId(),
                    'children' => $this->getAccountTypeWiseData($type_id, null, 0),
                    'has_children' => $has_children > 0 ? $has_children : 1,
                    'openingDebit' => $this->getOpeningDebitBalance($type_id),
                    'openingCredit' => $this->getOpeningCreditBalance($type_id),
                    'transactionDebit' => $this->getTransactionalDebitBalance($type_id) ,
                    'transactionCredit' => $this->getTransactionalCreditBalance($type_id),
                    'closingDebit' => $this->getClosingDebitBalance($type_id),
                    'closingCredit' => $this->getClosingCreditBalance($type_id),
                    'space_level' => $space_level
                ];
            })->values();
    }

    private function getOpeningDebitBalance($account_code)
    {
        return Journal::query()->where('account_code', 'like', rtrim($account_code, '0') . '%')
            ->where('trn_date', '<', $this->startDate)
            ->where('trn_type', 'dr')
            ->sum('trn_amount');
    }

    private function getOpeningCreditBalance($account_code)
    {
        return Journal::query()->where('account_code', 'like', rtrim($account_code, '0') . '%')
            ->where('trn_date', '<', $this->startDate)
            ->where('trn_type', 'cr')
            ->sum('trn_amount');
    }

    private function getTransactionalDebitBalance($account_code)
    {
        return Journal::query()->where('account_code', 'like', rtrim($account_code, '0') . '%')
            ->where('trn_date', '>=', $this->startDate)
            ->where('trn_date', '<', $this->endDate)
            ->where('trn_type', 'dr')
            ->sum('trn_amount');
    }

    private function getTransactionalCreditBalance($account_code)
    {
        return Journal::query()->where('account_code', 'like', rtrim($account_code, '0') . '%')
            ->where('trn_date', '>=', $this->startDate)
            ->where('trn_date', '<', $this->endDate)
            ->where('trn_type', 'cr')
            ->sum('trn_amount');
    }

    private function getClosingDebitBalance($account_code)
    {
        return Journal::query()->where('account_code', 'like', rtrim($account_code, '0') . '%')
            ->where('trn_date', '<', $this->endDate)
            ->where('trn_type', 'dr')
            ->sum('trn_amount');
    }

    private function getClosingCreditBalance($account_code)
    {
        return Journal::query()->where('account_code', 'like', rtrim($account_code, '0') . '%')
            ->where('trn_date', '<', $this->endDate)
            ->where('trn_type', 'cr')
            ->sum('trn_amount');
    }
}
