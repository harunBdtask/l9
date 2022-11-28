<?php

namespace SkylarkSoft\GoRMG\Finance\Services;

use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\Voucher;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class CreateJVService
{

    private static $staticAccounts = array(
        0 => [
                'code' => '510316001',
                'name' => 'VAT Expense'
            ],
        1 => [
                'code' => '220101003',
                'name' => 'VAT Payable'
            ],
        2 => [
                'code' => '220101004',
                'name' => 'TDS Payable'
            ],
        3 => [
                'code' => '420108001',
                'name' => 'Dyeing Subcontract Income'
            ],
        4 => [
                'code' => '520104001',
                'name' => 'Foreign Exchange Loss'
            ],
        5 => [
                'code' => '420106001',
                'name' => 'Foreign Exchange Gain'
            ],
        6 => [
                'code' => '420104001',
                'name' => 'Discount Received'
            ],
        7 => [
                'code' => '520101001',
                'name' => 'Discount Allowed'
            ]
        
    );
    // voucher items
    private static function createVoucherItems($data=[])
    {
        return [
            "credit"=>round($data['credit']??0,2),
            "cr_bd"=>round($data['cr_bd']??0,2),
            "cr_fc"=>($data['currency_id']!='1'?round($data['cr_fc']??0,2):0),
            "debit"=>round($data['debit']??0,2),
            "dr_bd"=>round($data['dr_bd']??0,2),
            "dr_fc"=>($data['currency_id']!='1'?round($data['dr_fc']??0,2):0),
            "item_type"=>$data['item_type'],
            "narration"=>$data['narration']??null,
            "ledger_id"=>$data['ledger_id']??null,
            "ledger_name"=>$data['ledger_name']??null,
            "account_code"=>$data['account_code']??null,
            "account_id"=>$data['account_id']??null,
            "account_name"=>$data['account_name']??null,
            "currency_id"=>$data['currency_id']??null,
            "currency_name"=>$data['currency_name']??null,
            "conversion_rate"=>$data['conversion_rate']??null,
            "const_center"=>$data['const_center']??null,
            "const_center_name"=>$data['const_center_name']??null
        ];
    }

    // Voucher Details
    private static function createVoucherDetails($request, $voucher){

        $data = [
            "from"=>"",
            "bank_id"=>null,
            "paymode"=>$voucher['paymode'],
            "type_id"=>$voucher['type_id'],
            "unit_id"=>"",
            "trn_date"=>$voucher['trn_date'],
            "cheque_no"=>null,
            "factory_id"=>$voucher['factory_id'],
            "project_id"=>$voucher['project_id'],
            "voucher_no"=>$voucher['voucher_no'],
            "currency_id"=>$voucher['currency_id'],
            "reference_no"=>$voucher['reference_no'],
            "debit_account"=>null,
            "department_id"=>"",
            "group_company"=>$voucher['group_company'],
            "cheque_due_date"=>null,
            "receive_bank_id"=>null,
            "receive_cheque_no"=>null,
            "debit_account_code"=>"",
            "debit_account_name"=>""
        ];

        $currency_name = collect(CurrencyService::currencies())->where('id', $request->currency_id)->first()['name'];

        // supplier entry list debit transactions debit
        $data['items'] = collect($request->details)->map(function($item) use($request, $currency_name){
            $item = (object)$item;
            $fcVal = ($item->unit_price*$item->qty);
            $account_code = Account::find($item->ledger_account_id??$item->control_account_id)->code;

            return self::createVoucherItems([
                "debit"=>$item->net_price,
                "dr_bd"=>$item->net_price,
                "dr_fc"=>$fcVal,
                "item_type"=>"debit",
                "narration"=>$item->purpose,
                "ledger_id"=>$item->ledger_account_id,
                "ledger_name"=>$item->ledger_account_name,
                "account_code"=>$account_code,
                "account_id"=>$item->control_account_id,
                "account_name"=>$item->control_account_name,
                "currency_id"=>$request->currency_id,
                "currency_name"=>$currency_name,
                "conversion_rate"=>$request->con_rate,
            ]);
        });

        // vat expense debit
        if(floatval($request->total_vat) > 0){
            $accountInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[0]['code'])->first();
            $data['items'][] = self::createVoucherItems([
                "debit"=>$request->total_vat,
                "dr_bd"=>$request->total_vat,
                "dr_fc"=>  round(($request->total_vat/$request->con_rate), 2),
                "item_type"=>"debit",
                "ledger_id"=>(@$accountInfo->id??null),
                "ledger_name"=>self::$staticAccounts[0]['name'],
                "account_code"=>self::$staticAccounts[0]['code'],
                "account_id"=>(@$accountInfo->accountInfo->control_account_id??null),
                "account_name"=>(@$accountInfo->accountInfo->controlAccount->name??null),
                "currency_id"=>$request->currency_id,
                "currency_name"=>$currency_name,
                "conversion_rate"=>$request->con_rate
            ]);
        }
        

        //Vat Payable credit
        if(floatval($request->total_vat) > 0){
            $accountInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[1]['code'])->first();
            $data['items'][] = self::createVoucherItems([
                "credit"=>$request->total_vat,
                "cr_bd"=>$request->total_vat,
                "cr_fc"=>round(($request->total_vat/$request->con_rate), 2),
                "item_type"=>"credit",
                "ledger_id"=>(@$accountInfo->id??null),
                "ledger_name"=>self::$staticAccounts[1]['name'],
                "account_code"=>self::$staticAccounts[1]['code'],
                "account_id"=>(@$accountInfo->accountInfo->control_account_id??null),
                "account_name"=>(@$accountInfo->accountInfo->controlAccount->name??null),
                "currency_id"=>$request->currency_id,
                "currency_name"=>$currency_name,
                "conversion_rate"=>$request->con_rate
            ]);
        }

        //TDS Payable credit
        if(floatval($request->total_tds) > 0){
            $accountInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[2]['code'])->first();
            $data['items'][] = self::createVoucherItems([
                "credit"=>$request->total_tds,
                "cr_bd"=>$request->total_tds,
                "cr_fc"=>round(($request->total_tds/$request->con_rate), 2),
                "item_type"=>"credit",
                "ledger_id"=>(@$accountInfo->id??null),
                "ledger_name"=>self::$staticAccounts[2]['name'],
                "account_code"=>self::$staticAccounts[2]['code'],
                "account_id"=>(@$accountInfo->accountInfo->control_account_id??null),
                "account_name"=>(@$accountInfo->accountInfo->controlAccount->name??null),
                "currency_id"=>$request->currency_id,
                "currency_name"=>$currency_name,
                "conversion_rate"=>$request->con_rate
            ]);
        }

        //Supplier Ledger
        $acc_id = $request->supplier['ledger_account_id']??@$request->supplier['control_ledger_id'];
        $accountInfo = Account::query()->with('accountInfo.controlAccount')->where('id', $acc_id)->first();
        $supplier_amount =  collect($request->details)->sum('net_price')-$request->total_tds;
        $data['items'][] = self::createVoucherItems([
            "credit"=>$supplier_amount,
            "cr_bd"=>$supplier_amount,
            "cr_fc"=>round(($supplier_amount/$request->con_rate), 2),
            "item_type"=>"credit",
            "ledger_id"=>(@$accountInfo->id??null),
            "ledger_name"=>(@$accountInfo->name??null),
            "account_code"=>(@$accountInfo->code??null),
            "account_id"=>(@$accountInfo->accountInfo->control_account_id??null),
            "account_name"=>(@$accountInfo->accountInfo->controlAccount->name??null),
            "currency_id"=>$request->currency_id,
            "currency_name"=>$currency_name,
            "conversion_rate"=>$request->con_rate
        ]);

        // Total Calculation
        $data["total_debit"] = collect($data['items'])->sum('dr_bd');
        $data["total_credit"] = collect($data['items'])->sum('cr_bd');

        $data["total_debit_fc"] = ($request->currency_id!='1'?round(($data["total_debit"]/$request->con_rate),2):0);
        $data["total_credit_fc"] = ($request->currency_id!='1'?round(($data["total_credit"]/$request->con_rate), 2):0);
        return json_encode($data);

    }
    public static function supplierEntryJVPost($request)
    {
        $voucher = new Voucher();
        $type = $voucher::JOURNAL_VOUCHER;
        $type_name = $voucher::VOUCHER_TYPE[$type];
        $voucher_no = $voucher::generateVoucherNo($type_name); 
        $voucher = [
            'voucher_no'=>$voucher_no,
            'type_id' => $type ,
            'trn_date'=>$request->bill_receive_date,
            'file_no'=>'',
            'reference_no'=> $request->bill_number,
            'group_company'=>$request->group_id,
            'factory_id'=>$request->company_id,
            'project_id'=>$request->project_id,
            'unit_id'=>null,
            'currency_id'=>$request->currency_id,
            'paymode'=>1,
            'credit_account'=>null,
            'debit_account'=>null,
            'to'=>null,
            'from'=>null,
            'bank_id'=>null,
            'receive_bank_id'=>null,
            'cheque_no'=>null,
            'receive_cheque_no'=>null,
            'cheque_date'=>null,
            'cheque_due_date'=>null,
            'amount'=>$request->party_payable,
            'details'=>null,
            'status_id'=>0
        ];
        
        $voucher['details'] = self::createVoucherDetails($request, $voucher);
        return $voucher;
    }

    /**
     * =====================================
     * Supplier Payment Debit Voucher Post
     * =====================================
     */
    // Voucher Details
    private static function billPaymentVoucherDetails($request, $voucher){

        $data = [
            "from"=>"",
            "bank_id"=>null,
            "paymode"=>$voucher['paymode']??null,
            "type_id"=>$voucher['type_id'],
            "unit_id"=>"",
            "trn_date"=>$voucher['trn_date'],
            "cheque_no"=>null,
            "factory_id"=>$voucher['factory_id'],
            "project_id"=>$voucher['project_id'],
            "voucher_no"=>$voucher['voucher_no'],
            "currency_id"=>$voucher['currency_id'],
            "reference_no"=>$voucher['reference_no'],
            "debit_account"=>null,
            "department_id"=>"",
            "group_company"=>$voucher['group_company'],
            "cheque_due_date"=>null,
            "receive_bank_id"=>null,
            "receive_cheque_no"=>null,
            "debit_account_code"=>"",
            "debit_account_name"=>""
        ];

        $currency_name = collect(CurrencyService::currencies())->where('id', $request->currency_id)->first()['name'];

         //Payment accounts credit
         $data['items'] = collect($request->payments)->map(function($item) use($request, $currency_name){
            $item = (object)$item;
            $accInfo = Account::query()->with('accountInfo.controlAccount')->where('id', $item->account_no)->first();

            return self::createVoucherItems([
                "credit"=>$item->amount_bdt,
                "cr_bd"=>$item->amount_bdt,
                "cr_fc"=>$item->amount,
                "item_type"=>"credit",
                "narration"=>$item->cheque_no,
                "ledger_id"=>(@$accInfo->id??null),
                "ledger_name"=>(@$accInfo->name??null),
                "account_code"=>(@$accInfo->code??null),
                "account_id"=>(@$accInfo->accountInfo->control_account_id??null),
                "account_name"=>(@$accInfo->accountInfo->controlAccount->name??null),
                "currency_id"=>$request->currency_id,
                "currency_name"=>$currency_name,
                "conversion_rate"=>$request->pay_con_rate
            ]);
        });

        //Discount Received Credit
        if($request->total_discount>0){
            $accInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[6]['code'])->first();
            $total_discount_bdt = ($request->currency_id!='1'?$request->total_discount*($request->pay_con_rate??1):$request->total_discount);

            $data['items'][] = self::createVoucherItems([
                "credit"=>$total_discount_bdt,
                "cr_bd"=>$total_discount_bdt,
                "cr_fc"=>$request->total_discount,
                "item_type"=>"credit",
                "narration"=>'Discount Received',
                "ledger_name"=>self::$staticAccounts[6]['name'],
                "account_code"=>self::$staticAccounts[6]['code'],
                "ledger_id"=>(@$accInfo->id??null),
                "account_id"=>(@$accInfo->accountInfo->control_account_id??null),
                "account_name"=>(@$accInfo->accountInfo->controlAccount->name??null),
                "currency_id"=>$request->currency_id,
                "currency_name"=>$currency_name,
                "conversion_rate"=>$request->pay_con_rate
            ]);
        }   


        //Exchange Gain/Loss debit/credit
        if($request->final_gain_loss<>0){
            $gain_loss_bdt = abs($request->final_gain_loss);
            if($request->final_gain_loss<0){ //loss debit
                $accInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[4]['code'])->first();

                $data['items'][] = self::createVoucherItems([
                    "debit"=>$gain_loss_bdt,
                    "dr_bd"=>$gain_loss_bdt,
                    "dr_fc"=>($gain_loss_bdt/$request->pay_con_rate),
                    "item_type"=>"debit",
                    "narration"=>'Foreign Exchange Loss',
                    "ledger_name"=>self::$staticAccounts[4]['name'],
                    "account_code"=>self::$staticAccounts[4]['code'],
                    "ledger_id"=>(@$accInfo->id??null),
                    "account_id"=>(@$accInfo->accountInfo->control_account_id??null),
                    "account_name"=>(@$accInfo->accountInfo->controlAccount->name??null),
                    "currency_id"=>$request->currency_id,
                    "currency_name"=>$currency_name,
                    "conversion_rate"=>$request->pay_con_rate
                ]);
            }else{  //Gain credit
                $accInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[5]['code'])->first();
                $data['items'][] = self::createVoucherItems([
                    "credit"=>$gain_loss_bdt,
                    "cr_bd"=>$gain_loss_bdt,
                    "cr_fc"=>($gain_loss_bdt/$request->pay_con_rate),
                    "item_type"=>"credit",
                    "narration"=>'Foreign Exchange Gain',
                    "ledger_name"=>self::$staticAccounts[5]['name'],
                    "account_code"=>self::$staticAccounts[5]['code'],
                    "ledger_id"=>(@$accInfo->id??null),
                    "account_id"=>(@$accInfo->accountInfo->control_account_id??null),
                    "account_name"=>(@$accInfo->accountInfo->controlAccount->name??null),
                    "currency_id"=>$request->currency_id,
                    "currency_name"=>$currency_name,
                    "conversion_rate"=>$request->pay_con_rate
                ]);
            }
        }

        //Supplier Debit
        $supplier = Supplier::find($request->supplier_id);
        $accountInfo = Account::query()->with('accountInfo.controlAccount')->where('id', $supplier->ledger_account_id)->first();

        $total_paid_amount_bdt  = collect($request->details)->map(function($item){
            return ($item['con_rate'] * floatval($item['paid_amount']));
        })->sum();

        $total_paid_amount_fc = ($total_paid_amount_bdt/$request->pay_con_rate);

        $data['items'][] = self::createVoucherItems([
            "debit"=>$total_paid_amount_bdt,
            "dr_bd"=>$total_paid_amount_bdt,
            "dr_fc"=>$total_paid_amount_fc,
            "item_type"=>"debit",
            "ledger_id"=>(@$accountInfo->id??null),
            "ledger_name"=>(@$accountInfo->name??null),
            "account_code"=>(@$accountInfo->code??null),
            "account_id"=>(@$accountInfo->accountInfo->control_account_id??null),
            "account_name"=>(@$accountInfo->accountInfo->controlAccount->name??null),
            "currency_id"=>$request->currency_id,
            "currency_name"=>$currency_name,
            "conversion_rate"=>$request->pay_con_rate
        ]);

         // Total Calculation
         $data["total_debit"] = collect($data['items'])->sum('dr_bd');
         $data["total_credit"] = collect($data['items'])->sum('cr_bd');
 
         $data["total_debit_fc"] = ($request->currency_id!='1'?round(($data["total_debit"]/$request->pay_con_rate),2):0);
         $data["total_credit_fc"] = ($request->currency_id!='1'?round(($data["total_credit"]/$request->pay_con_rate), 2):0);

        return json_encode($data);

    }

    //Bill payment jv post
    public static function supplierBillPaymentJVPost($request)
    {
        $voucher = new Voucher();
        $type = $voucher::DEBIT_VOUCHER;
        $type_name = $voucher::VOUCHER_TYPE[$type];
        $voucher_no = $voucher::generateVoucherNo($type_name); 
        $voucher = [
            'voucher_no'=>$voucher_no,
            'type_id' => $type ,
            'trn_date'=>$request->payment_date,
            'file_no'=>'',
            'reference_no'=> collect($request->details)->pluck('bill_number')->implode(', '),
            'group_company'=>$request->group_id,
            'factory_id'=>$request->company_id,
            'project_id'=>$request->project_id,
            'unit_id'=>null,
            'currency_id'=>$request->currency_id,
            'paymode'=>1,
            'credit_account'=>null,
            'debit_account'=>null,
            'to'=>null,
            'from'=>null,
            'bank_id'=>null,
            'receive_bank_id'=>null,
            'cheque_no'=>null,
            'receive_cheque_no'=>null,
            'cheque_date'=>null,
            'cheque_due_date'=>null,
            'amount'=>$request->final_paid_amount_bdt,
            'details'=>null,
            'status_id'=>0
        ];
        
        $voucher['details'] = self::billPaymentVoucherDetails($request, $voucher);
        return $voucher;
    }

    /**
     * ==================================
     * Customer Payment bill Receive
     * ==================================
     */
    // Voucher Details
    private static function customerBillReceiveVoucherDetails($request, $voucher){

        $data = [
            "from"=>"",
            "bank_id"=>null,
            "paymode"=>$voucher['paymode']??null,
            "type_id"=>$voucher['type_id'],
            "unit_id"=>"",
            "trn_date"=>$voucher['trn_date'],
            "cheque_no"=>null,
            "factory_id"=>$voucher['factory_id'],
            "project_id"=>$voucher['project_id'],
            "voucher_no"=>$voucher['voucher_no'],
            "currency_id"=>$voucher['currency_id'],
            "reference_no"=>$voucher['reference_no'],
            "debit_account"=>null,
            "department_id"=>"",
            "group_company"=>$voucher['group_company'],
            "cheque_due_date"=>null,
            "receive_bank_id"=>null,
            "receive_cheque_no"=>null,
            "debit_account_code"=>"",
            "debit_account_name"=>""
        ];

        $currency_name = collect(CurrencyService::currencies())->where('id', $request->currency_id)->first()['name'];
        $conversion_rate = $request->payInfos['cons_rate']??1;
        
        //Payment accounts debit
        $data['items'] = collect($request->payInfos['details'])->map(function($item) use($request, $currency_name, $conversion_rate){
            $item = (object)$item;
            $accInfo = Account::query()->with('accountInfo.controlAccount')->where('id', $item->account_id)->first();

            return self::createVoucherItems([
                "debit"=>$item->amount_bdt,
                "dr_bd"=>$item->amount_bdt,
                "dr_fc"=>($item->amount),
                "item_type"=>"debit",
                "narration"=>$item->check_lc_no,
                "ledger_id"=>(@$accInfo->id??null),
                "ledger_name"=>(@$accInfo->name??null),
                "account_code"=>(@$accInfo->code??null),
                "account_id"=>(@$accInfo->accountInfo->control_account_id??null),
                "account_name"=>(@$accInfo->accountInfo->controlAccount->name??null),
                "currency_id"=>$request->currency_id,
                "currency_name"=>$currency_name,
                "conversion_rate"=>$conversion_rate
            ]);
        });

        //Discount allowed debit
        if($request->payInfos['discount_received']>0){
            $accInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[7]['code'])->first();
            $consRate = $request->payInfos['cons_rate']??1;
            $discount = $request->payInfos['discount_received'];

            $data['items'][] = self::createVoucherItems([
                "debit"=>$discount*$consRate,
                "dr_bd"=>$discount*$consRate,
                "dr_fc"=>$discount,
                "item_type"=>"debit",
                "narration"=>'Discount Allowed',
                "ledger_name"=>self::$staticAccounts[7]['name'],
                "account_code"=>self::$staticAccounts[7]['code'],
                "ledger_id"=>(@$accInfo->id??null),
                "account_id"=>(@$accInfo->accountInfo->control_account_id??null),
                "account_name"=>(@$accInfo->accountInfo->controlAccount->name??null),
                "currency_id"=>$request->currency_id,
                "currency_name"=>$currency_name,
                "conversion_rate"=>$consRate
            ]);
        }

        // Exchange Gain/Loss debit/credit
        if($request->payInfos['exchange_gain_loss']<>0){
            // $consRate = $request->payInfos['cons_rate']??1;
            $gain_loss_bdt = abs($request->payInfos['exchange_gain_loss']);

            if($request->payInfos['exchange_gain_loss']<0){ //loss debit
                $accInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[4]['code'])->first();

                $data['items'][] = self::createVoucherItems([
                    "debit"=>$gain_loss_bdt,
                    "dr_bd"=>$gain_loss_bdt,
                    "dr_fc"=>($gain_loss_bdt/$conversion_rate),
                    "item_type"=>"debit",
                    "narration"=>'Foreign Exchange Loss',
                    "ledger_name"=>self::$staticAccounts[4]['name'],
                    "account_code"=>self::$staticAccounts[4]['code'],
                    "ledger_id"=>(@$accInfo->id??null),
                    "account_id"=>(@$accInfo->accountInfo->control_account_id??null),
                    "account_name"=>(@$accInfo->accountInfo->controlAccount->name??null),
                    "currency_id"=>$request->currency_id,
                    "currency_name"=>$currency_name,
                    "conversion_rate"=>$conversion_rate
                ]);

            }else{  //Gain credit
                $accInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[5]['code'])->first();
                $data['items'][] = self::createVoucherItems([
                    "credit"=>$gain_loss_bdt,
                    "cr_bd"=>$gain_loss_bdt,
                    "cr_fc"=>($gain_loss_bdt/$conversion_rate),
                    "item_type"=>"credit",
                    "narration"=>'Foreign Exchange Gain',
                    "ledger_name"=>self::$staticAccounts[5]['name'],
                    "account_code"=>self::$staticAccounts[5]['code'],
                    "ledger_id"=>(@$accInfo->id??null),
                    "account_id"=>(@$accInfo->accountInfo->control_account_id??null),
                    "account_name"=>(@$accInfo->accountInfo->controlAccount->name??null),
                    "currency_id"=>$request->currency_id,
                    "currency_name"=>$currency_name,
                    "conversion_rate"=>$conversion_rate
                ]);
            }
        }

        //Customer credit
        $customer = Buyer::find($request->customer_id);
        $accountInfo = Account::query()->with('accountInfo.controlAccount')->where('id', $customer->ledger_account_id)->first();

        $total_received_amt_bdt  = collect($request->details)->map(function($item){
            return ($item['cons_rate'] * floatval($item['received_amount']));
        })->sum();

        $total_received_amt_fc = ($total_received_amt_bdt/$conversion_rate);

        $data['items'][] = self::createVoucherItems([
            "credit"=>$total_received_amt_bdt,
            "cr_bd"=>$total_received_amt_bdt,
            "cr_fc"=>$total_received_amt_fc,
            "item_type"=>"credit",
            "ledger_id"=>(@$accountInfo->id??null),
            "ledger_name"=>(@$accountInfo->name??null),
            "account_code"=>(@$accountInfo->code??null),
            "account_id"=>(@$accountInfo->accountInfo->control_account_id??null),
            "account_name"=>(@$accountInfo->accountInfo->controlAccount->name??null),
            "currency_id"=>$request->currency_id,
            "currency_name"=>$currency_name,
            "conversion_rate"=>$conversion_rate
        ]);
       
         // Total Calculation
         $data["total_debit"] = collect($data['items'])->sum('dr_bd');
         $data["total_credit"] = collect($data['items'])->sum('cr_bd');
 
         $data["total_debit_fc"] = ($request->currency_id!='1'?round(($data["total_debit"]/$conversion_rate),2):0);
         $data["total_credit_fc"] = ($request->currency_id!='1'?round(($data["total_credit"]/$conversion_rate), 2):0);

        return json_encode($data);

    }


    //Customer Bill receive jv post
    public static function customerBillReceiveJVPost($request)
    {
        $voucher = new Voucher();
        $type = $voucher::CREDIT_VOUCHER;
        $type_name = $voucher::VOUCHER_TYPE[$type];
        $voucher_no = $voucher::generateVoucherNo($type_name); 
        $voucher = [
            'voucher_no'=>$voucher_no,
            'type_id' => $type ,
            'trn_date'=>$request->payInfos['received_date'],
            'reference_no'=> collect($request->bill_nos)->implode(', '),
            'group_company'=>$request->group_id,
            'factory_id'=>$request->company_id,
            'project_id'=>$request->project_id,
            'currency_id'=>$request->currency_id,
            'paymode'=>1,
            'amount'=>$request->totalAmountInBdt,
            'status_id'=>0
        ];
        
        $voucher['details'] = self::customerBillReceiveVoucherDetails($request, $voucher);
        return $voucher;
    }


    /**
     * =======================
     * Customer Create Invoice
     * =======================
     */
    // Voucher Details
    private static function customerCreateInvoiceVoucherDetails($request, $voucher){

        $data = [
            "from"=>"",
            "bank_id"=>null,
            "paymode"=>$voucher['paymode']??null,
            "type_id"=>$voucher['type_id'],
            "unit_id"=>"",
            "trn_date"=>$voucher['trn_date'],
            "cheque_no"=>null,
            "factory_id"=>$voucher['factory_id'],
            "project_id"=>$voucher['project_id'],
            "voucher_no"=>$voucher['voucher_no'],
            "currency_id"=>$voucher['currency_id'],
            "reference_no"=>$voucher['reference_no'],
            "debit_account"=>null,
            "department_id"=>"",
            "group_company"=>$voucher['group_company'],
            "cheque_due_date"=>null,
            "receive_bank_id"=>null,
            "receive_cheque_no"=>null,
            "debit_account_code"=>"",
            "debit_account_name"=>""
        ];

        $currency_name = collect(CurrencyService::currencies())->where('id', $request->currency_id)->first()['name'];
        $conversion_rate = floatval($request->cons_rate);
        $customer = Buyer::find($request->customer_id);
        

         //Dyeing Wise Income static code credit
         $accInfo = Account::query()->with('accountInfo.controlAccount')->where('code', self::$staticAccounts[3]['code'])->first();
         $data['items'][] = self::createVoucherItems([
             "credit"=>$voucher['amount'],
             "cr_bd"=>$voucher['amount'],
             "cr_fc"=>($voucher['amount']/$conversion_rate),
             "item_type"=>"credit",
             "ledger_id"=>(@$accInfo->id??null),
             "ledger_name"=>self::$staticAccounts[3]['name'],
             "account_code"=>self::$staticAccounts[3]['code'],
             "account_id"=>(@$accInfo->accountInfo->control_account_id??null),
             "account_name"=>(@$accInfo->accountInfo->controlAccount->name??null),
             "currency_id"=>$request->currency_id,
             "currency_name"=>$currency_name,
             "conversion_rate"=>$conversion_rate
         ]);


        //Customer debit
        $accountInfo = Account::query()->with('accountInfo.controlAccount')->where('id', $customer->ledger_account_id)->first();
        $data['items'][] = self::createVoucherItems([
            "debit"=>$voucher['amount'],
            "dr_bd"=>$voucher['amount'],
            "dr_fc"=>($voucher['amount']/$conversion_rate),
            "item_type"=>"debit",
            "ledger_id"=>(@$accountInfo->id??null),
            "ledger_name"=>(@$accountInfo->name??null),
            "account_code"=>(@$accountInfo->code??null),
            "account_id"=>(@$accountInfo->accountInfo->control_account_id??null),
            "account_name"=>(@$accountInfo->accountInfo->controlAccount->name??null),
            "currency_id"=>$request->currency_id,
            "currency_name"=>$currency_name,
            "conversion_rate"=>$conversion_rate
        ]);


         // Total Calculation
         $data["total_debit"] = collect($data['items'])->sum('dr_bd');
         $data["total_credit"] = collect($data['items'])->sum('cr_bd');
 
         $data["total_debit_fc"] = ($request->currency_id!='1'?round(($data["total_debit"]/$conversion_rate),2):0);
         $data["total_credit_fc"] = ($request->currency_id!='1'?round(($data["total_credit"]/$conversion_rate), 2):0);

        return json_encode($data);

    }
    //Customer Bill receive jv post
    public static function customerCreateInvoiceJVPost($request)
    {
        $voucher = new Voucher();
        $type = $voucher::JOURNAL_VOUCHER;
        $type_name = $voucher::VOUCHER_TYPE[$type];
        $voucher_no = $voucher::generateVoucherNo($type_name); 
        $amount = collect($request->details)->sum('total_value')- floatval($request->discount);
        $voucher = [
            'voucher_no'=>$voucher_no,
            'type_id' => $type ,
            'trn_date'=>$request->bill_date,
            'reference_no'=> $request->bill_no,
            'group_company'=>$request->group_id,
            'factory_id'=>$request->company_id,
            'project_id'=>$request->project_id,
            'currency_id'=>$request->currency_id,
            'paymode'=>1,
            'amount'=>$amount,
            'status_id'=>0
        ];
        
        $voucher['details'] = self::customerCreateInvoiceVoucherDetails($request, $voucher);
        return $voucher;
    }


}