<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports;

use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\Journal;


class BankManagementService
{

    public static function opening_balance($filter = [])
    {
        if(!empty($filter)){
            
            $debit = Journal::query()
                ->whereDate('trn_date', '<', $filter['fromDate'])
                ->where('trn_type', 'dr')    
                ->whereIn('account_code', $filter['account_codes'])
                ->when($filter['factoryId'], function ($query) use ($filter) {
                    $query->whereHas('account', function ($query) use ($filter) {
                        $query->where('factory_id', $filter['factoryId']);
                    });
                })->when($filter['projectId'], function ($query) use ($filter) {
                    $query->where('project_id', $filter['projectId']);
                })->when($filter['unitId'], function ($query) use ($filter) {
                    $query->where('unit_id', $filter['unitId']);
                })->when($filter['costCenterId'], function ($query) use ($filter) {
                    $query->where('cost_center_id', $filter['costCenterId']);
                })->when($filter['ledgerId'], function ($query) use ($filter) {
                    $query->where('account_id', $filter['ledgerId']);
                })->sum('trn_amount');

            $credit = Journal::query()
                ->whereDate('trn_date', '<', $filter['fromDate'])
                ->where('trn_type', 'cr')    
                ->whereIn('account_code', $filter['account_codes'])
                ->when($filter['factoryId'], function ($query) use ($filter) {
                    $query->whereHas('account', function ($query) use ($filter) {
                        $query->where('factory_id', $filter['factoryId']);
                    });
                })->when($filter['projectId'], function ($query) use ($filter) {
                    $query->where('project_id', $filter['projectId']);
                })->when($filter['unitId'], function ($query) use ($filter) {
                    $query->where('unit_id', $filter['unitId']);
                })->when($filter['costCenterId'], function ($query) use ($filter) {
                    $query->where('cost_center_id', $filter['costCenterId']);
                })->when($filter['ledgerId'], function ($query) use ($filter) {
                    $query->where('account_id', $filter['ledgerId']);
                })->sum('trn_amount');
            
            return $debit - $credit;

        }
        return 0; 
    }

    public static function closing_balance($filter = [])
    {
        if(!empty($filter)){
            
            $debit = Journal::query()
                ->whereDate('trn_date', '<=', $filter['toDate'])
                ->where('trn_type', 'dr')    
                ->whereIn('account_code', $filter['account_codes'])
                ->when($filter['factoryId'], function ($query) use ($filter) {
                    $query->whereHas('account', function ($query) use ($filter) {
                        $query->where('factory_id', $filter['factoryId']);
                    });
                })->when($filter['projectId'], function ($query) use ($filter) {
                    $query->where('project_id', $filter['projectId']);
                })->when($filter['unitId'], function ($query) use ($filter) {
                    $query->where('unit_id', $filter['unitId']);
                })->when($filter['costCenterId'], function ($query) use ($filter) {
                    $query->where('cost_center_id', $filter['costCenterId']);
                })->when($filter['ledgerId'], function ($query) use ($filter) {
                    $query->where('account_id', $filter['ledgerId']);
                })->sum('trn_amount');

            $credit = Journal::query()
                ->whereDate('trn_date', '<=', $filter['toDate'])
                ->where('trn_type', 'cr')    
                ->whereIn('account_code', $filter['account_codes'])
                ->when($filter['factoryId'], function ($query) use ($filter) {
                    $query->whereHas('account', function ($query) use ($filter) {
                        $query->where('factory_id', $filter['factoryId']);
                    });
                })->when($filter['projectId'], function ($query) use ($filter) {
                    $query->where('project_id', $filter['projectId']);
                })->when($filter['unitId'], function ($query) use ($filter) {
                    $query->where('unit_id', $filter['unitId']);
                })->when($filter['costCenterId'], function ($query) use ($filter) {
                    $query->where('cost_center_id', $filter['costCenterId']);
                })->when($filter['ledgerId'], function ($query) use ($filter) {
                    $query->where('account_id', $filter['ledgerId']);
                })->sum('trn_amount');
            
            return $debit - $credit;

        }
        return 0; 
    }

}
