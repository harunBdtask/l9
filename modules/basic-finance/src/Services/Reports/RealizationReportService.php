<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports;

use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class RealizationReportService
{
    protected static $distribution_title =[];
    protected static $deduction_title =[];
    protected static $bank_charge_title =[];
    protected static function getTitle(){

        return $titles = [
            'Foreign EXP CONT. NO./ Local Sales Contract',
            'L/C No.',
            'Buyer/Party Name',
            'Style No.',
            'PO No.',
            'Invoice No.',
            'Bill ref. No (FDBC/TT-Foreign/LDBC/TT-Local)',
            'Realize Date',
            'Currency',
            'Con. Rate',
            'Invoice Value',
            'Realized Value',
            'Short Realization'
        ];

    }
    public function getReport($list = [])
    {
        
        if(!empty($list)){
            $data['list'] = collect($list)->map(function($item){

                

                $thisItem['sales_contract'] = collect($item->sc_number)->implode(', ');
                $thisItem['lc_no'] = collect($item->lc_number)->implode(', ');
                $thisItem['buyer'] = $item->buyers?collect(Buyer::whereIn('id',$item->buyers)->pluck('name'))->implode(', '):null;
                $thisItem['style_no'] = collect($item->styles)->implode(', ');;
                $thisItem['po_no'] = collect($item->po_numbers)->implode(', ');;
                $thisItem['invoice_no'] = collect($item->invoice_number)->implode(', ');
                $thisItem['bill_ref_no'] = $item->realization_number;
                $thisItem['realize_date'] = date('d M Y', strtotime($item->realization_date));
                $thisItem['currency_id'] = $item->currency_id;
                $thisItem['currency_name'] = $item->currency->currency_name;
                $thisItem['con_rate'] = $item->realization_rate;
                $thisItem['invoice_value'] = $item->total_value['amount_usd'];
                $thisItem['realized_value'] = $item->realized_value['amount_usd'];
                $thisItem['short_realization'] = $item->short_realization['amount_usd'];
                $thisItem['distribution'] = collect($item->distribution)->map(function($val){

                    self::$distribution_title[$val['bf_account']['code']] = $val['bf_account']['name']??null;
                    return [
                        'value' => $val['amount_usd']??0,
                        'title'=>$val['bf_account']['name']??null,
                        'code'=>$val['bf_account']['code']??null,
                    ];
                });

                $thisItem['deduction'] = collect($item->deduction)->map(function($val){
                    self::$deduction_title[$val['bf_account']['code']] = $val['bf_account']['name']??null;
                    return [
                        'value' => $val['amount_usd']??0,
                        'title'=>$val['bf_account']['name']??null,
                        'code'=>$val['bf_account']['code']??null,
                    ];
                });

                $thisItem['foreign_bank_charge'] = collect($item->foreign_bank_charge)->map(function($val){
                    self::$bank_charge_title[$val['bf_account']['code']] = $val['bf_account']['name']??null;
                    // return [$val['bf_account']['code'] => $val['amount_usd']??0];

                    return [
                        'value' => $val['amount_usd']??0,
                        'title'=>$val['bf_account']['name']??null,
                        'code'=>$val['bf_account']['code']??null
                    ];
                });

                return $thisItem;
            });
            $data['title'] = self::getTitle();
            $data['dynamic_titles'] = ['distribution'=> self::$distribution_title, 'deduction' => self::$deduction_title, 'bank_charge' => self::$bank_charge_title];
        }
        return $data;
    }
}