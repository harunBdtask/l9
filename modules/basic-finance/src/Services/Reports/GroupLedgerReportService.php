<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports;

use SkylarkSoft\GoRMG\BasicFinance\Models\Account;


class GroupLedgerReportService
{


    private static function getBalance($item, $filter){

        $item->opening_balance = self::openingLedgerBalance($item, $filter);

        $item->debit = collect($item->journalEntries)->map(function ($val){
            if($val->trn_type == 'dr'){
                return $val->trn_amount;
            }
        })->sum();


        $item->credit = collect($item->journalEntries)->map(function ($val){
            if($val->trn_type == 'cr'){
                return $val->trn_amount;
            }
        })->sum();

        $item->closing_balance = self::closingLedgerBalance($item, $filter);

        return $item;

    }
    private  function getAccountInfo($requestIds, $filter)
    {
        $ledgersData = Account::query()
            ->when($filter['companyId'], function($q) use ($filter) {
                return $q->where('factory_id', $filter['companyId']);
            })
            ->with([
                'journalEntries' => function ($query) use ($filter) {
                    return  $query->whereDate('trn_date', '>=', $filter['start_date'])
                        ->whereDate('trn_date', '<=', $filter['end_date'])
                        ->when($filter['companyId'], function ($query2) use ($filter) {
                             return $query2->where('factory_id', $filter['companyId']);
                        })
                        ->when($filter['projectId'], function ($query2) use ($filter) {
                             return $query2->where('project_id', $filter['projectId']);
                        })
                        ->when($filter['unitId'], function ($query2) use ($filter) {
                             return $query2->where('unit_id', $filter['unitId']);
                        })
                        ->when($filter['currency_id'], function ($query2) use ($filter) {
                            if($filter['currency_id'] ==1){
                                return $query2->where('currency_id', 1);
                            }else if($filter['currency_id'] == 2){
                                return $query2->where('currency_id', '!=', 1);
                            }else{
                                return $query2->whereNotNull('currency_id');
                            }
                        })
                        ;
                },
                'childAcs'

            ])
            ->whereIn('id', $requestIds)
            ->where('is_active', 1)
            ->get(['id','name','code','parent_ac as parent_id','is_transactional']);
            return $ledgersData;
    }


    public static function getLedger($requestIds, $filter)
    {

        $accounts = Account::query()
            ->with('childAcs')
            ->whereIn('id', $requestIds)
            ->get();


        $ledgerList = collect($accounts)->map(function($item) use($filter) {

            $level_1_ids = collect($item->childAcs)->pluck('id');

            $childOneInfo = self::getAccountInfo($level_1_ids, $filter);  // Level 1 child
            return $childLevelOneData = collect($childOneInfo)->map(function($item) use ($filter) {

                $itemInfo = self::getBalance($item, $filter);
                $itemInfo->parentAccountName = Account::find($itemInfo->parent_id)->name;

                if($itemInfo->childAcs && count($itemInfo->childAcs) > 0){   //If Account has Child level 2

                     $child_2 = collect($itemInfo->childAcs)->map(function($item2) use($itemInfo, $filter) {

                        $childInfo2 = self::getAccountInfo([$item2->id], $filter)->first();
                        if(!empty($childInfo2)){
                            $childData2 = self::getBalance($childInfo2, $filter);

                            $itemInfo->opening_balance += $childData2->opening_balance;
                            $itemInfo->debit += $childData2->debit;
                            $itemInfo->credit += $childData2->credit;
                            $itemInfo->closing_balance += $childData2->closing_balance;

                            if($item2->childAcs && count($item2->childAcs) > 0){   /// If Account has Child level3  3

                                 $child_3 = collect($item2->childAcs)->map(function($item3) use($itemInfo, $filter) {

                                    $childInfo3 = self::getAccountInfo([$item3->id], $filter)->first();
                                    if(!empty($childInfo3))
                                    {
                                        $childData3 = self::getBalance($childInfo3, $filter);

                                        $itemInfo->opening_balance += $childData3->opening_balance;
                                        $itemInfo->debit += $childData3->debit;
                                        $itemInfo->credit += $childData3->credit;
                                        $itemInfo->closing_balance += $childData3->closing_balance;

                                    }
                                });
                            }
                        }

                    });
                }
                return $itemInfo;


            });

        })->flatten(1);

        return collect($ledgerList)->groupBy('parentAccountName');

    }


    private static function openingLedgerBalance($item, $filter = [])
    {
        // $account = Account::find($item);
        if($item)
        {

            $debit = $item->journalEntries()
                ->whereDate('trn_date', '<', $filter['start_date'])
                ->where('trn_type', 'dr')
                ->when($filter['companyId'], function ($query) use ($filter) {
                    return $query->whereHas('account', function ($query) use ($filter) {
                        return $query->where('factory_id', $filter['companyId']);
                    });
                })
                ->when($filter['projectId'], function ($query) use ($filter) {
                    return $query->where('project_id', $filter['projectId']);
                })->when($filter['unitId'], function ($query) use ($filter) {
                    return $query->where('unit_id', $filter['unitId']);
                })->when($filter['currency_id'], function ($query) use ($filter) {
                    if($filter['currency_id'] ==1){
                        return $query->where('currency_id', 1);
                    }else if($filter['currency_id'] == 2){
                        return $query->where('currency_id', '!=', 1);
                    }else{
                        return $query->whereNotNull('currency_id');
                    }
                })->sum('trn_amount');

            $credit = $item->journalEntries()
                ->whereDate('trn_date', '<', $filter['start_date'])
                ->where('trn_type', 'cr')
                ->when($filter['companyId'], function ($query) use ($filter) {
                    return $query->whereHas('account', function ($query) use ($filter) {
                        return $query->where('factory_id', $filter['companyId']);
                    });
                })
                ->when($filter['projectId'], function ($query) use ($filter) {
                    $query->where('project_id', $filter['projectId']);
                })->when($filter['unitId'], function ($query) use ($filter) {
                    return $query->where('unit_id', $filter['unitId']);
                })->when($filter['currency_id'], function ($query) use ($filter) {

                    if($filter['currency_id'] ==1){
                        return $query->where('currency_id', 1);
                    }else if($filter['currency_id'] == 2){
                        return $query->where('currency_id', '!=', 1);
                    }else{
                        return $query->whereNotNull('currency_id');
                    }

                })->sum('trn_amount');

            return $debit - $credit;
        }

        return 0;
    }

    private static function closingLedgerBalance($item, $filter = [])
    {
        // $account = Account::find($item);
        if($item)
        {

            $debit = $item->journalEntries()
                ->whereDate('trn_date', '<=', $filter['end_date'])
                ->where('trn_type', 'dr')
                ->when($filter['companyId'], function ($query) use ($filter) {
                    return  $query->whereHas('account', function ($query) use ($filter) {
                        return $query->where('factory_id', $filter['companyId']);
                    });
                })
                ->when($filter['projectId'], function ($query) use ($filter) {
                    return $query->where('project_id', $filter['projectId']);
                })->when($filter['unitId'], function ($query) use ($filter) {
                    return $query->where('unit_id', $filter['unitId']);
                })->when($filter['currency_id'], function ($query) use ($filter) {
                    if($filter['currency_id'] ==1){
                        return $query->where('currency_id', 1);
                    }else if($filter['currency_id'] == 2){
                        return $query->where('currency_id', '!=', 1);
                    }else{
                        return $query->whereNotNull('currency_id');
                    }
                })->sum('trn_amount');

            $credit = $item->journalEntries()
                ->whereDate('trn_date', '<=', $filter['end_date'])
                ->where('trn_type', 'cr')
                ->when($filter['companyId'], function ($query) use ($filter) {
                    return $query->whereHas('account', function ($query) use ($filter) {
                        return  $query->where('factory_id', $filter['companyId']);
                    });
                })
                ->when($filter['projectId'], function ($query) use ($filter) {
                    return $query->where('project_id', $filter['projectId']);
                })->when($filter['unitId'], function ($query) use ($filter) {
                    return $query->where('unit_id', $filter['unitId']);
                })->when($filter['currency_id'], function ($query) use ($filter) {
                    if($filter['currency_id'] ==1){
                        return $query->where('currency_id', 1);
                    }else if($filter['currency_id'] == 2){
                        return $query->where('currency_id', '!=', 1);
                    }else{
                        return $query->whereNotNull('currency_id');
                    }
                })->sum('trn_amount');

            return $debit - $credit;
        }

        return 0;
    }

    private function formatData($ledgersData)
    {
        $data = collect($ledgersData)->map(function($item){
            return (object) [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'opening_balance' => $item->opening_balance,
                'debit' => $item->debit,
                'credit' => $item->credit,
                'closing_balance'=> $item->closing_balance
            ];
        });
        return $data;
    }
}
