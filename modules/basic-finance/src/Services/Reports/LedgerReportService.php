<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports;


class LedgerReportService
{

    public function ledgerFormatter($account,$requestTranId)
    {
        $allItemsArray = $accountCodesArray = $accountHeadsArray = $accountParticularsArray = $debitBalancesArray = $creditBalancesArray = [];
        foreach ($account->journalentries as $journalEntry) {
            $accountHeads = [];
            $ref = collect($account->reference_no)->toArray();
            if ($journalEntry->voucher && isset($journalEntry->voucher->details)) {
                if (isset($journalEntry->voucher->details->items)) {
                    if (count($journalEntry->voucher->details->items) > 1) {
                        $allItems = collect($journalEntry->voucher->details->items)->whereNotIn('account_id', $requestTranId)->all();
                    } else {
                        $allItems = collect($journalEntry->voucher->details->items)->all();
                    }
                    $allItems = array_values($allItems);
                    $type_id = $journalEntry->voucher->details->type_id;
                    $account_name = [];
                    $accountCodes1 = $accountParticular1 = $debitBalances1 = $creditBalances1 = [];
                    if ($type_id == 1) {
                        if ($journalEntry->voucher->details->credit_account != $requestTranId) {
                            if (count($journalEntry->voucher->details->items) <= 1) {
                                $name = $journalEntry->voucher->details->credit_account_name;
                                array_push($account_name, $name);
                            } else {
                                $accountCodes1[] = $journalEntry->voucher->details->credit_account_code;
                                $accountParticular1 = [''];
                                $debitBalances1 = [0];
                                array_push($account_name, $journalEntry->voucher->details->credit_account_name);
                                $creditBalances1[] = $journalEntry->voucher->details->total_credit;
                                foreach ($allItems as $item) {
                                    array_push($account_name, $item->account_name);
                                }
                            }
                        } else {
                            foreach ($allItems as $item) {
                                array_push($account_name, $item->account_name);
                            }
                        }
                    }
                    if ($type_id == 2) {
                        if ($journalEntry->voucher->details->debit_account != $requestTranId) {
                            if (count($journalEntry->voucher->details->items) <= 1) {
                                $name = $journalEntry->voucher->details->debit_account_name;
                                array_push($account_name, $name);
                            } else {
                                $accountCodes1[] = $journalEntry->voucher->details->debit_account_code;
                                $accountParticular1 = [''];
                                $debitBalances1[] = $journalEntry->voucher->details->total_debit;
                                array_push($account_name, $journalEntry->voucher->details->debit_account_name);
                                $creditBalances1 = [0];
                                foreach ($allItems as $item) {
                                    array_push($account_name, $item->account_name);
                                }
                            }
                        } else {
                            foreach ($allItems as $item) {
                                array_push($account_name, $item->account_name);
                            }
                        }
                    }
                    if ($type_id == 3) {
                        foreach ($allItems as $item) {
                            array_push($account_name, $item->account_name);
                        }
                    }
                    if ($type_id == 4) {
                        if(!empty($allItems)){
                            array_push($account_name, $allItems[0]->account_name);
                        }else{
                            array_push($account_name, '');
                        }
                    }
                    $accountCodes2 = collect($allItems)->pluck("account_code")->toArray();
                    $accountHeads = $account_name;
                    $accountParticulars2 = collect($allItems)->pluck("narration")->toArray() ?? [];
                    $debitBalances2 = collect($allItems)->pluck("debit")->toArray();
                    $creditBalances2 = collect($allItems)->pluck("credit")->toArray();

                    $accountCodes = array_merge($accountCodes1, $accountCodes2);
                    $accountParticulars = array_merge($accountParticular1, $accountParticulars2);
                    $debitBalances = array_merge($debitBalances1, $debitBalances2);
                    $creditBalances = array_merge($creditBalances1, $creditBalances2);

                    array_push($allItemsArray, $allItems);
                    array_push($accountCodesArray, $accountCodes);
                    array_push($accountHeadsArray, $accountHeads);
                    array_push($accountParticularsArray, $accountParticulars);
                    array_push($debitBalancesArray, $debitBalances);
                    array_push($creditBalancesArray, $creditBalances);
                }
            }
        }

        return [
            'allItemsArray' => $allItemsArray,
            'accountCodesArray' => $accountCodesArray,
            'accountHeadsArray' => $accountHeadsArray,
            'accountParticularsArray' => $accountParticularsArray,
            'debitBalancesArray' => $debitBalancesArray,
            'creditBalancesArray' => $creditBalancesArray
        ];
    }
}
