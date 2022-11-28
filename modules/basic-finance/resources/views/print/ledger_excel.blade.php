<div>
    <table>
        @php
            if(($currencyTypeId == 1) || ($currencyTypeId == 2)){
                $span = 14;
            }else{
                $span = 17;
            }
        @endphp
        <tr>
            <td colspan="{{$span}}" class="text-left" style="background-color: lightblue"><b>{{factoryName()}}</b></td>
        </tr>
        <tr>
            <td colspan="{{$span}}" class="text-left" style="background-color: lightblue"><b>{{factoryAddress()}}</b></td>
        </tr>
    </table>
</div>
<div>
    <table>
        <tr>
            <td colspan="{{$span}}" style="background-color: lightblue"><h3>Ledger Report</h3></td>
        </tr>
    </table>
</div>
<div>
    <div class="row">
        <div class="col-md-12">
            <table style="margin-bottom: 5px;" class="borderless">
                <tr>
                    <td style="width:150px;"><b>Account Head:</b></td>
                    <td>{{$account->name ?? ''}}</td>
                </tr>
                <tr>
                    <td style="width:102px;"><b>Date:</b></td>
                    <td>{{Carbon\carbon::parse($start_date)->format('F d, Y')}}
                        - {{Carbon\carbon::parse($end_date)->format('F d, Y')}}
                    </td>
                </tr>
            </table>
            <table class="reportTable">
                <thead class="thead-light">
                <tr>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>TRAN. DATE</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>PROJECT</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>UNIT</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>DEPT.</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>COST CENTER</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>ACC. CODE</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>ACC. HEAD</td>
                    <td style="border: 1px solid black;" rowspan="2" class="text-center" colspan="2">PARTICULARS</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>REF. NO</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>VOUCHER NO</td>
                    @if (request('currency_type_id') == 2)
                        <td style="border: 1px solid black;" rowspan="2" class='text-center'>CONV. RATE</td>
                        <td style="border: 1px solid black;" colspan="3" class="text-center">FOREIGN CURRENCY</td>
                    @elseif (request('currency_type_id') == 1)
                        <td style="border: 1px solid black;" colspan="3" class="text-center">HOME CURRENCY [BDT]</td>
                    @else
                        <td style="border: 1px solid black;" rowspan="2" class='text-center'>CONV. RATE</td>
                        <td style="border: 1px solid black;" colspan="3" class="text-center">FOREIGN CURRENCY</td>
                        <td style="border: 1px solid black;" colspan="3" class="text-center">HOME CURRENCY [BDT]</td>
                    @endif
                </tr>
                <tr>
                    @if (request('currency_type_id') == 2)
                        <td style="border: 1px solid black;" class="text-center">DEBIT</td>
                        <td style="border: 1px solid black;" class="text-center">CREDIT</td>
                        <td style="border: 1px solid black;" class="text-center">BALANCE</td>
                    @elseif (request('currency_type_id') == 1)
                        <td style="border: 1px solid black;" class="text-center">DEBIT</td>
                        <td style="border: 1px solid black;" class="text-center">CREDIT</td>
                        <td style="border: 1px solid black;" class="text-center">BALANCE</td>
                    @else
                        <td style="border: 1px solid black;" class="text-center">DEBIT</td>
                        <td style="border: 1px solid black;" class="text-center">CREDIT</td>
                        <td style="border: 1px solid black;" class="text-center">BALANCE</td>
                        <td style="border: 1px solid black;" class="text-center">DEBIT</td>
                        <td style="border: 1px solid black;" class="text-center">CREDIT</td>
                        <td style="border: 1px solid black;" class="text-center">BALANCE</td>
                    @endif
                </tr>
                </thead>
                @if(isset($account))
                    <tbody>
                    <tr>
                        <td style="border: 1px solid black;" colspan="1" class='text-left'>
                            @php
                                $date = \Carbon\Carbon::today();
                                if (request()->has('start_date')){
                                    $date = \Carbon\Carbon::parse(request('start_date'));
                                }
                            @endphp
                            {{ $date->toFormattedDateString() }}
                        </td>
                        @if (request('currency_type_id') == 2)
                            <td style="border: 1px solid black;" class='text-center' colspan="13"><strong>Balance Forward</strong></td>
                            <td style="border: 1px solid black;" class="text-right">
                                @if($balance >= 0)
                                    <strong>{{ number_format(abs($openingFcBalance), 2).' Dr' }}</strong>
                                @else
                                    <strong>{{ number_format(abs($openingFcBalance), 2).' Cr' }}</strong>
                                @endif
                            </td>
                        @elseif (request('currency_type_id') == 1)
                            <td style="border: 1px solid black;" class='text-center' colspan="12"><strong>Balance Forward</strong></td>
                            <td style="border: 1px solid black;" class="text-right">
                                @if($balance >= 0)
                                    <strong>{{ number_format(abs($openingBalance), 2).' Dr' }}</strong>
                                @else
                                    <strong>{{ number_format(abs($openingBalance), 2).' Cr' }}</strong>
                                @endif
                            </td>
                        @else
                            <td style="border: 1px solid black;" class='text-center' colspan="13"><strong>Balance Forward</strong></td>
                            <td style="border: 1px solid black;" class="text-right">
                                @if($balance >= 0)
                                    <strong>{{ number_format(abs($openingFcBalance), 2).' Dr' }}</strong>
                                @else
                                    <strong>{{ number_format(abs($openingFcBalance), 2).' Cr' }}</strong>
                                @endif
                            </td>
                            <td style="border: 1px solid black;" colspan=3" class="text-right">
                                @if($balance >= 0)
                                    <strong>{{ number_format(abs($openingBalance), 2).' Dr' }}</strong>
                                @else
                                    <strong>{{ number_format(abs($openingBalance), 2).' Cr' }}</strong>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @forelse($account->journalEntries as $index=>$journalEntry)
                        @php
                            if ($journalEntry->trn_type == 'dr') {
                            $balance += $journalEntry->trn_amount;
                            $fcBalance += $journalEntry->fc;
                            } else {
                            $balance -= $journalEntry->trn_amount;
                            $fcBalance -= $journalEntry->fc;
                            }
                            $currency =  collect(\SkylarkSoft\GoRMG\Finance\Services\CurrencyService::currencies())
                                                            ->where('id', $journalEntry->currency_id)->first()['name'] ?? null;
                        @endphp
                        <tr>
                            <td style="border: 1px solid black;" class='text-left'>
                                {{ $journalEntry->trn_date->toFormattedDateString() }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($journalEntry->project_id) ? $journalEntry->project->project : '' }}</td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($journalEntry->unit_id) ? $journalEntry->unit->unit : '' }}</td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($journalEntry->department_id) ? $journalEntry->department->department : '' }}</td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($journalEntry->cost_center_id) ? $journalEntry->cost_center->cost_center : '' }}</td>
{{--                            <td style="border: 1px solid black;" class="text-left">--}}
{{--                                @php--}}
{{--                                    if(sizeof($accountCodesArray[$index]) <= 1){--}}
{{--                                        foreach($accountCodesArray[$index] as $accountCode){--}}
{{--                                                echo $accountCode;--}}
{{--                                            }--}}
{{--                                    }--}}
{{--                                @endphp--}}
{{--                            </td>--}}
{{--                            <td style="border: 1px solid black;" class="text-left">--}}
{{--                                @php--}}
{{--                                    if(sizeof($accountHeadsArray[$index])>1){--}}
{{--                                            echo '<b>Details are as below:</b>';--}}
{{--                                        }--}}
{{--                                    elseif(sizeof($accountHeadsArray[$index])<=1){--}}
{{--                                           foreach ($accountHeadsArray[$index] as $accHead){--}}
{{--                                               echo isset($accHead) ? implode('<br>', str_replace("&","&amp;",array($accHead))) : '';--}}
{{--                                           }--}}
{{--                                        }--}}
{{--                                @endphp--}}
{{--                            </td>--}}
{{--                            <td class="text-left" style=" border:1px solid black; border-right: 1px solid transparent !important;">--}}
{{--                                @php--}}
{{--                                    if(sizeof($accountParticularsArray[$index])<=1){--}}
{{--                                        echo isset($accountParticularsArray[$index]) ? implode('<br>', str_replace("&","&amp;",$accountParticularsArray[$index])) : '';--}}
{{--                                    }--}}
{{--                                @endphp--}}
{{--                            </td>--}}
                            <td style="border: 1px solid black;" class="text-right"></td>
                            <td style="border: 1px solid black;" class='text-left'>
                                {{$journalEntry->voucher->reference_no ?? ''}} <br>
                                {{$journalEntry->voucher->bill_no ?? ''}}
                            </td>
                            <td style="border: 1px solid black;" class='text-left'>
                                {{$journalEntry->voucher_no}}
                            </td>

                            @if (request('currency_type_id') == 2)
                                <td style="border: 1px solid black;">
                                    {{$currency . '@'. $journalEntry->conversion_rate}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'dr' ? number_format($journalEntry->fc, 2) : '' }}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'cr' ? number_format($journalEntry->fc, 2) : ''}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    @if($loop->last)
                                        @if($fcBalance >= 0)
                                            <strong>{{ number_format(abs($fcBalance), 2).' Dr' }}</strong>
                                        @else
                                            <strong>{{ number_format(abs($fcBalance), 2).' Cr' }}</strong>
                                        @endif
                                    @else
                                        @if($fcBalance >= 0)
                                            {{ number_format(abs($fcBalance), 2).' Dr' }}
                                        @else
                                            {{ number_format(abs($fcBalance), 2).' Cr' }}
                                        @endif
                                    @endif
                                </td>
                            @elseif (request('currency_type_id') == 1)
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'dr' ? number_format($journalEntry->trn_amount, 2) : '' }}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'cr' ? number_format($journalEntry->trn_amount, 2) : ''}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    @if($loop->last)
                                        @if($balance >= 0)
                                            <strong>{{ number_format(abs($balance), 2).' Dr' }}</strong>
                                        @else
                                            <strong>{{ number_format(abs($balance), 2).' Cr' }}</strong>
                                        @endif
                                    @else
                                        @if($balance >= 0)
                                            {{ number_format(abs($balance), 2).' Dr' }}
                                        @else
                                            {{ number_format(abs($balance), 2).' Cr' }}
                                        @endif
                                    @endif
                                </td>
                            @else
                                <td style="border: 1px solid black;">
                                    {{$currency . '@'. $journalEntry->conversion_rate}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'dr' ? number_format($journalEntry->fc, 2) : '' }}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'cr' ? number_format($journalEntry->fc, 2) : ''}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    @if($loop->last)
                                        @if($fcBalance >= 0)
                                            <strong>{{ number_format(abs($fcBalance), 2).' Dr' }}</strong>
                                        @else
                                            <strong>{{ number_format(abs($fcBalance), 2).' Cr' }}</strong>
                                        @endif
                                    @else
                                        @if($fcBalance >= 0)
                                            {{ number_format(abs($fcBalance), 2).' Dr' }}
                                        @else
                                            {{ number_format(abs($fcBalance), 2).' Cr' }}
                                        @endif
                                    @endif
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'dr' ? number_format($journalEntry->trn_amount, 2) : '' }}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'cr' ? number_format($journalEntry->trn_amount, 2) : ''}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    @if($loop->last)
                                        @if($balance >= 0)
                                            <strong>{{ number_format(abs($balance), 2).' Dr' }}</strong>
                                        @else
                                            <strong>{{ number_format(abs($balance), 2).' Cr' }}</strong>
                                        @endif
                                    @else
                                        @if($balance >= 0)
                                            {{ number_format(abs($balance), 2).' Dr' }}
                                        @else
                                            {{ number_format(abs($balance), 2).' Cr' }}
                                        @endif
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @if(((int)($journalEntry->voucher->type_id) == 3))
                            @php
                                if(sizeof($allItemsArray[$index])>1){
                                    foreach($allItemsArray[$index] as $item){
                                        echo "<tr>";
                                        echo '<td class="text-left"> </td>';
                                        echo '<td class="text-left"> </td>';
                                        echo '<td class="text-left"> </td>';
                                        echo '<td class="text-left"> </td>';
                                        echo '<td class="text-left"> </td>';
                                        echo '<td class="text-left"><i>'."</i></td>";
                                        echo '<td class="text-left"><i>'.str_replace("&","&amp;",$item->account_name).'('.$item->account_code.")</i></td>";
                                        echo '<td class="text-left" style="border-right: 1px solid transparent !important;"><i>'.str_replace("&","&amp;",$item->narration)."</i></td>";
                                        if(($item->debit)>0){
                                           echo '<td class="text-right"><i>'.$item->debit." Dr</i></td>";
                                        }elseif(($item->credit)>0){
                                            echo '<td class="text-right"><i>'.$item->credit." Cr</i></td>";
                                        }
                                        echo '<td class="text-right"> </td>';
                                        echo '<td class="text-right"> </td>';
                                        if (request('currency_type_id') == 2){
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                        }
                                        elseif (request('currency_type_id') == 1){
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                        }
                                        else{
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                            echo '<td class="text-right"> </td>';
                                        }
                                        echo "</tr>";
                                    }
                                }
                            @endphp
                        @else
{{--                            @php--}}
{{--                                if(count($accountCodesArray[$index])>1){--}}
{{--                                    for($i=0;$i<count($accountCodesArray[$index]);$i++){--}}
{{--                                        echo "<tr>";--}}
{{--                                        echo '<td class="text-left"> </td>';--}}
{{--                                        echo '<td class="text-left"> </td>';--}}
{{--                                        echo '<td class="text-left"> </td>';--}}
{{--                                        echo '<td class="text-left"> </td>';--}}
{{--                                        echo '<td class="text-left"> </td>';--}}
{{--                                        echo '<td class="text-left"><i>'."</i></td>";--}}
{{--                                        echo '<td class="text-left"><i>'.str_replace("&","&amp;",$accountHeadsArray[$index][$i]).'('.$accountCodesArray[$index][$i].")</i></td>";--}}
{{--                                        echo '<td class="text-left" style="border-right: 1px solid transparent !important;"><i>'.str_replace("&","&amp;",$accountParticularsArray[$index][$i])."</i></td>";--}}
{{--                                       if(is_null($debitBalancesArray[$index][$i]))--}}
{{--                                           {--}}
{{--                                               $debitBalancesArray[$index][$i] = 0;--}}
{{--                                           }--}}
{{--                                           elseif( is_null($creditBalancesArray[$index][$i])){--}}
{{--                                               $creditBalancesArray[$index][$i] = 0;--}}
{{--                                           }--}}
{{--                                               if(($debitBalancesArray[$index][$i])>0){--}}
{{--                                                   echo '<td class="text-right"><i>'.$debitBalancesArray[$index][$i]." Dr</i></td>";--}}
{{--                                               }--}}
{{--                                               elseif(($creditBalancesArray[$index][$i])>0)--}}
{{--                                               {--}}
{{--                                                   echo '<td class="text-right"><i>'.$creditBalancesArray[$index][$i]." Cr</i></td>";--}}
{{--                                               }--}}

{{--                                        echo '<td class="text-right"> </td>';--}}
{{--                                        echo '<td class="text-right"> </td>';--}}
{{--                                        if (request('currency_type_id') == 2){--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                        }--}}
{{--                                        elseif (request('currency_type_id') == 1){--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                        }--}}
{{--                                        else{--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                            echo '<td class="text-right"> </td>';--}}
{{--                                        }--}}
{{--                                        echo "</tr>";--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            @endphp--}}
                        @endif
                    @empty
                        <tr>
                            <td colspan="18" class="text-center text-danger">No transaction</td>
                        </tr>
                    @endforelse
                    <tr>
                        @if (request('currency_type_id') == 2)
                            <td style="border: 1px solid black;" class='text-center' colspan="12"><strong>TOTAL</strong></td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    number_format($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'dr' ? $item->fc : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    number_format($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'cr' ? $item->fc : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right"></td>
                        @elseif (request('currency_type_id') == 1)
                            <td style="border: 1px solid black;" class='text-center' colspan="11"><strong>TOTAL</strong></td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    number_format($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'dr' ? $item->trn_amount : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    number_format($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'cr' ? $item->trn_amount : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right"></td>
                        @else
                            <td style="border: 1px solid black;" class='text-center' colspan="12"><strong>TOTAL</strong></td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    number_format($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'dr' ? $item->fc : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    number_format($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'cr' ? $item->fc : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right"></td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    number_format($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'dr' ? $item->trn_amount : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    number_format($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'cr' ? $item->trn_amount : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right"></td>
                        @endif
                    </tr>
                    </tbody>
                @else
                    <tbody>
                    <tr>
                        <td style="border: 1px solid black;" colspan="17" class="text-center text-danger">No Account Data Available</td>
                    </tr>
                    </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
