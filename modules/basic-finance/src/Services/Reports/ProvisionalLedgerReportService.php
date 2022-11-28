<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports;

use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;


class ProvisionalLedgerReportService
{
    public static function voucherSort($voucher, $account)
    {
        if($voucher->type_id == Voucher::DEBIT_VOUCHER){

            $details = $voucher->details;
            
            // if($voucher->details->credit_account == $account->id){  //Credit
            if($voucher->credit_account == $account->id){  //Credit
                
                $voucher->trn_type = 'cr';
                $debitAcc = collect($voucher->details->items)->first();
                $voucher->account_id = intval($debitAcc->account_id);
                $voucher->account_name = $debitAcc->account_name;
                $voucher->account_code = $debitAcc->account_code;
                
                $voucher->department_id = intval($debitAcc->department_id);
                $voucher->department_name = $debitAcc->department_name;
                $voucher->const_center = intval($debitAcc->const_center);
                $voucher->const_center_name = $debitAcc->const_center_name;

                $voucher->fcDebit = 0;
                $voucher->fcCredit = (float) $details->total_debit_fc??0;
                $voucher->bdtDebit = 0;
                $voucher->bdtCredit =(float) $details->total_credit;
                $voucher->conversion_rate = floatval($debitAcc->conversion_rate);

            }else{   //Debit

                $voucher->trn_type = 'dr';
                
                $voucher->account_id = intval($details->credit_account);
                $voucher->account_name = $details->credit_account_name;
                $voucher->account_code = $details->credit_account_code;
                
                $debitAcc = collect($voucher->details->items)->whereIn('account_id', [$account->id, "$account->id"])->first();
                $voucher->department_id = intval($debitAcc->department_id);
                $voucher->department_name = $debitAcc->department_name??'';
                $voucher->const_center = intval($debitAcc->const_center);
                $voucher->const_center_name = $debitAcc->const_center_name??'';

                $voucher->fcDebit = floatval($debitAcc->dr_fc);
                $voucher->fcCredit = 0;
                $voucher->bdtDebit = floatval($debitAcc->dr_bd);
                $voucher->bdtCredit = 0;
                $voucher->conversion_rate = floatval($debitAcc->conversion_rate);
                
            }

        } else if($voucher->type_id == Voucher::CREDIT_VOUCHER){  //Credit Voucher

            $details = $voucher->details;
            
            // if($voucher->details->debit_account == $account->id){  //If Debit
            if($voucher->debit_account == $account->id){  //If Debit
                
                $voucher->trn_type = 'dr';
                $creditAcc = collect($voucher->details->items)->first();
                $voucher->account_id = intval($creditAcc->account_id);
                $voucher->account_name = $creditAcc->account_name;
                $voucher->account_code = $creditAcc->account_code;
                
                $voucher->department_id = intval($creditAcc->department_id);
                $voucher->department_name = $creditAcc->department_name;
                $voucher->const_center = intval($creditAcc->const_center);
                $voucher->const_center_name = $creditAcc->const_center_name;

                $voucher->fcDebit = $details->total_credit_fc??0;
                $voucher->fcCredit = 0;
                $voucher->bdtDebit = $details->total_debit??0;
                $voucher->bdtCredit = 0;
                $voucher->conversion_rate = floatval($creditAcc->conversion_rate);

            }else{  //Credit

                $voucher->trn_type = 'cr';
                $voucher->account_id = intval($details->debit_account);
                $voucher->account_name = $details->debit_account_name;
                $voucher->account_code = $details->debit_account_code;
                
                $creditAcc = collect($voucher->details->items)->whereIn('account_id', [$account->id, "$account->id"])->first();
                $voucher->department_id = intval($creditAcc->department_id);
                $voucher->department_name = $creditAcc->department_name??'';
                $voucher->const_center = intval($creditAcc->const_center);
                $voucher->const_center_name = $creditAcc->const_center_name??'';

                $voucher->fcDebit = 0;
                $voucher->fcCredit = floatval($creditAcc->cr_fc);
                $voucher->bdtDebit = 0;
                $voucher->bdtCredit = floatval($creditAcc->cr_bd);
                $voucher->conversion_rate = floatval($creditAcc->conversion_rate);

            }
        } 
        else {  //Journal/Contra Voucher

            $details = $voucher->details;

            $itemInfo = collect($details->items)->whereIn('account_id', [$account->id, "$account->id"])->first();
            if($itemInfo->debit){  //If Debit
                
                $voucher->trn_type = 'dr';

                $creditAcc = collect($details->items)->firstWhere('credit', '>', 0);
                $voucher->account_id = intval($creditAcc->account_id);
                $voucher->account_name = $creditAcc->account_name;
                $voucher->account_code = $creditAcc->account_code;
                
                $voucher->fcDebit = (float) $itemInfo->dr_fc;
                $voucher->fcCredit = 0;
                $voucher->bdtDebit = (float) $itemInfo->dr_bd;
                $voucher->bdtCredit = 0;

            }else{  //Credit

                $voucher->trn_type = 'cr';

                $debitAcc = collect($details->items)->firstWhere('debit', '>', 0);
                $voucher->account_id = intval($debitAcc->account_id);
                $voucher->account_name = $debitAcc->account_name;
                $voucher->account_code = $debitAcc->account_code;

                $voucher->fcDebit = 0;
                $voucher->fcCredit = (float) $itemInfo->cr_fc;
                $voucher->bdtDebit = 0;
                $voucher->bdtCredit = (float) $itemInfo->cr_bd;

            }

            $voucher->department_id = intval($itemInfo->department_id);
            $voucher->department_name = $itemInfo->department_name;
            $voucher->const_center = intval($itemInfo->const_center);
            $voucher->const_center_name = $itemInfo->const_center_name;
            $voucher->conversion_rate = floatval($itemInfo->conversion_rate);
        }
        return $voucher;

    }
    public static function formatLedger($account, $vouchers)
    {
        return  collect($vouchers)->map(function($voucher) use($account) {
            $voucher = self::voucherSort($voucher, $account);
            return $voucher;
        });
    }

    public static function openingLedgerBalance($account, $date = null, $companyId, $projectId, $unitId, $departmentId=false, $costCenterId=false)
    {

        $vouchers =  Voucher::query()->where('factory_id', $companyId)
        ->whereNotIn('status_id', [Voucher::CANCELED])
        ->whereDate('trn_date', '<', $date)
        ->when($projectId, function($q) use ($projectId){
            return $q->where('project_id', $projectId);
        })
        ->when($projectId, function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->when($unitId, function ($query) use ($unitId) {
            $query->where('unit_id', $unitId);
        })
        ->when($departmentId, function ($query) use ($departmentId) {
            $query->whereJsonContains('details->items', ['department_id' => $departmentId]);
        })->when($costCenterId, function ($query) use ($costCenterId) {
            $query->whereJsonContains('details->items', ['const_center' => $costCenterId]);
        })
        ->when($account->id, function($query) use($account){
            $query->where(function($q) use($account) {
                return $q->WhereJsonContains('details->credit_account', [$account->id, "$account->id"])
                    ->orWhereJsonContains('details->debit_account', [$account->id, "$account->id"])
                    ->orWhereJsonContains('details->items', ['account_id' => $account->id])
                    ->orWhereJsonContains('details->items', ['account_id' => "$account->id"]);
            });
        })
        ->get();

        $voucherList = $vouchers ? collect($vouchers)->map(function($voucher) use($account) {
            $voucher = self::voucherSort($voucher, $account);
            return $voucher;
        }):[];

        $data = $voucherList ? collect($voucherList)->groupBy('trn_type'): [];
        
        $result['openingBalance'] = (isset($data['dr'])? $data['dr']->sum('bdtDebit'): 0)  - (isset($data['cr']) ? $data['cr']->sum('fcCredit'): 0);
        $result['openingFCBalance'] = (isset($data['dr'])? $data['dr']->sum('fcDebit'): 0)  - (isset($data['cr']) ? $data['cr']->sum('fcCredit'): 0);

        return $result;

    }
}
