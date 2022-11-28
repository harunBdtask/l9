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
            <td colspan="{{$span}}" style="background-color: lightblue"><h3>Provisional Ledger Report</h3></td>
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
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>UNIT</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>DEPT.</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>COST CENTER</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>ACC. CODE</td>
                    <td style="border: 1px solid black;" rowspan="2" class='text-center'>ACC. HEAD</td>
                    <td style="border: 1px solid black;" rowspan="2" class="text-center">PARTICULARS</td>
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
                            {{-- Balance Forward start --}}
                            <tr>
                            <td class='text-center'>
                                @php
                                    $date = \Carbon\Carbon::today();
                                    if (request()->has('start_date')){
                                        $date = \Carbon\Carbon::parse(request('start_date'));
                                    }
                                @endphp
                                {{ $date->toFormattedDateString() }}
                            </td>
                            @if (request('currency_type_id') == 2)
                                <td class='text-center' colspan="11"><strong>Balance Forward</strong></td>
                                <td class="text-right">
                                    @if($balance >= 0)
                                        <strong>{{ BdtNumFormat(abs($openingFcBalance), 2).' Dr' }}</strong>
                                    @else
                                        <strong>{{ BdtNumFormat(abs($openingFcBalance), 2).' Cr' }}</strong>
                                    @endif
                                </td>
                            @elseif (request('currency_type_id') == 1)
                                <td class='text-center' colspan="10"><strong>Balance Forward</strong></td>
                                <td class="text-right">
                                    @if($balance >= 0)
                                        <strong>{{ BdtNumFormat(abs($openingBalance), 2).' Dr' }}</strong>
                                    @else
                                        <strong>{{ BdtNumFormat(abs($openingBalance), 2).' Cr' }}</strong>
                                    @endif
                                </td>
                            @else
                                <td class='text-center' colspan="11"><strong>Balance Forward</strong></td>
                                <td class="text-right">
                                    @if($balance >= 0)
                                        <strong>{{ BdtNumFormat(abs($openingFcBalance), 2).' Dr' }}</strong>
                                    @else
                                        <strong>{{ BdtNumFormat(abs($openingFcBalance), 2).' Cr' }}</strong>
                                    @endif
                                </td>
                                <td colspan="2"></td>
                                <td  class="text-right">
                                    @if($balance >= 0)
                                        <strong>{{ BdtNumFormat(abs($openingBalance), 2).' Dr' }}</strong>
                                    @else
                                        <strong>{{ BdtNumFormat(abs($openingBalance), 2).' Cr' }}</strong>
                                    @endif
                                </td>
                            @endif
                        </tr>
                        {{-- Balance Forward end --}}
                        @if (!empty($provisionalLedgers))
                            @forelse($provisionalLedgers as $index=>$vocherItem)
                                @php
                                    if ($vocherItem->trn_type == 'dr') { // Debit
                                        $balance += $vocherItem->bdtDebit;
                                        $fcBalance += $vocherItem->fcDebit;
                                    } else {
                                        $balance -= $vocherItem->bdtCredit;
                                        $fcBalance -= $vocherItem->fcCredit;
                                    }
                                    $count = sizeof($vocherItem->details->items);
                                @endphp
                                
                                {{-- voucher account_name end --}}
                                <tr>
                                    <td class='text-center'>
                                        {{ $vocherItem->trn_date->toFormattedDateString() }}
                                    </td>
                                    <td class="text-center">{{isset($vocherItem->unit_id) ? $vocherItem->unit->unit : '' }}</td>
                                    <td class="text-center">{{isset($vocherItem->department_name) ? $vocherItem->department_name : '' }}</td>
                                    <td class="text-center">{{isset($vocherItem->const_center_name) ? $vocherItem->const_center_name : '' }}</td>
                                    <td class="text-center">{{isset($vocherItem->account_code) ? $vocherItem->account_code : '' }}</td>
                                    <td class="text-center">{{isset($vocherItem->account_name) ? $vocherItem->account_name : '' }}</td>
                                    <td class="text-center">{{isset($vocherItem->narration) ? $vocherItem->narration : '' }}</td>
                                    <td class='text-center'> {{$vocherItem->reference_no ?? ''}} </td>
                                    <td class='text-center'>

                                        @php $itemsTotal = (in_array($vocherItem->trn_type, [1,2]))?1:2; @endphp
                                        <a href={{ url('/basic-finance/vouchers/'.$vocherItem->id) }} target="_blank"><b>{{ $vocherItem->voucher_no}}</b></a>
                                    </td>
                                    {{-- Foreign --}}
                                    @if (request('currency_type_id') != 1)   
                                        <td>
                                            {{$vocherItem->currency_name . '@'. $vocherItem->conversion_rate}}
                                        </td>
                                        <td class="text-right">
                                            {{ $vocherItem->trn_type == 'dr' ? BdtNumFormat($vocherItem->fcDebit, 2) : '' }}
                                        </td>
                                        <td class="text-right">
                                            {{ $vocherItem->trn_type == 'cr' ? BdtNumFormat($vocherItem->fcCredit, 2) : ''}}
                                        </td>
                                        <td class="text-right">
                                            @if($loop->last)
                                                @if($fcBalance >= 0)
                                                    <strong>{{ BdtNumFormat(abs($fcBalance), 2).' Dr' }}</strong>
                                                @else
                                                    <strong>{{ BdtNumFormat(abs($fcBalance), 2).' Cr' }}</strong>
                                                @endif
                                            @else
                                                @if($fcBalance >= 0)
                                                    {{ BdtNumFormat(abs($fcBalance), 2).' Dr' }}
                                                @else
                                                    {{ BdtNumFormat(abs($fcBalance), 2).' Cr' }}
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                    {{-- Home Curr --}}
                                    @if (request('currency_type_id') != 2)
                                        <td class="text-right">
                                            {{ $vocherItem->trn_type == 'dr' ? BdtNumFormat($vocherItem->bdtDebit, 2) : '' }}
                                        </td>
                                        <td class="text-right">
                                            {{ $vocherItem->trn_type == 'cr' ? BdtNumFormat($vocherItem->bdtCredit, 2) : ''}}
                                        </td>
                                        <td class="text-right">
                                            @if($loop->last)
                                                @if($balance >= 0)
                                                    <strong>{{ BdtNumFormat(abs($balance), 2).' Dr' }}</strong>
                                                @else
                                                    <strong>{{ BdtNumFormat(abs($balance), 2).' Cr' }}</strong>
                                                @endif
                                            @else
                                                @if($balance >= 0)
                                                    {{ BdtNumFormat(abs($balance), 2).' Dr' }}
                                                @else
                                                    {{ BdtNumFormat(abs($balance), 2).' Cr' }}
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="17" class="text-center text-danger">No transaction</td>
                                </tr>
                            @endforelse
                            <tr>
                                @php
                                    $ledgerData = collect($provisionalLedgers)->groupBy('trn_type');
                                    $totalFcDebitBalance = (isset($ledgerData['dr']) ? $ledgerData['dr']->sum('fcDebit'): 0);
                                    $totalFcCreditBalance = (isset($ledgerData['cr']) ? $ledgerData['cr']->sum('fcCredit'): 0);
                                    $totalDebitBalance = (isset($ledgerData['dr']) ? $ledgerData['dr']->sum('bdtDebit'): 0);
                                    $totalCreditBalance = (isset($ledgerData['cr']) ? $ledgerData['cr']->sum('bdtCredit'): 0);


                                @endphp
                                @if (request('currency_type_id') == 2)
                                    <td class='text-center' colspan="10"><strong>TOTAL</strong></td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalFcDebitBalance, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalFcCreditBalance, 2) }}
                                    </td>
                                    <td class="text-right"></td>
                                @elseif (request('currency_type_id') == 1)
                                    <td class='text-center' colspan="9"><strong>TOTAL</strong></td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalDebitBalance, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalCreditBalance, 2) }}
                                    </td>
                                    <td class="text-right"></td>
                                @else
                                    <td class='text-center' colspan="10"><strong>TOTAL</strong></td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalFcDebitBalance, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalFcCreditBalance, 2) }}
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalDebitBalance, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalCreditBalance, 2) }}
                                    </td>
                                    <td class="text-right"></td>
                                @endif
                            </tr>
                            <tr>
                                @if (request('currency_type_id') == 2)
                                    <td class='text-center' colspan="10"><strong>Closing Balance</strong></td>
                                    <td class="text-right">
                                        @if($fcBalance < 0)
                                            <strong>{{ BdtNumFormat(abs($fcBalance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($fcBalance >= 0)
                                            <strong>{{ BdtNumFormat(abs($fcBalance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-right"></td>
                                @elseif (request('currency_type_id') == 1)
                                    <td class='text-center' colspan="9"><strong>Closing Balance</strong></td>
                                    <td class="text-right">
                                        @if($balance <= 0)
                                            <strong>{{ BdtNumFormat(abs($balance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($balance >= 0)
                                            <strong>{{ BdtNumFormat(abs($balance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-right"></td>
                                @else
                                    <td class='text-center' colspan="10"><strong>Closing Balance</strong></td>
                                    <td class='text-right'>
                                        @if($fcBalance < 0)
                                            <strong>{{ BdtNumFormat(abs($fcBalance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td class='text-right'>
                                        @if($fcBalance >= 0)
                                            <strong>{{ BdtNumFormat(abs($fcBalance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td></td>
                                    <td class='text-right'>
                                        @if($balance <= 0)
                                            <strong>{{ BdtNumFormat(abs($balance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td class='text-right'>
                                        @if($balance >= 0)
                                            <strong>{{ BdtNumFormat(abs($balance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td></td>
                                @endif
                            </tr>
                            <tr>
                                @php
                                    $grandTotalFcDebitBalance = $totalFcDebitBalance;
                                    $grandTotalFcDebitBalance += $fcBalance <= 0 ? abs($fcBalance) : 0;
                                    $grandTotalFcDebitBalance += $fcBalance > 0 ? $openingFcBalance : 0;

                                    $grandTotalFcCreditBalance = $totalFcCreditBalance;
                                    $grandTotalFcCreditBalance += $fcBalance > 0 ? abs($fcBalance) : 0;
                                    $grandTotalFcCreditBalance += $fcBalance <= 0 ? $openingFcBalance : 0;

                                    $grandTotalDebitBalance = $totalDebitBalance;
                                    $grandTotalDebitBalance += $balance <= 0 ? abs($balance) : 0;
                                    $grandTotalDebitBalance += $balance > 0 ? $openingBalance : 0;

                                    $grandTotalCreditBalance = $totalCreditBalance;
                                    $grandTotalCreditBalance += $balance > 0 ? abs($balance) : 0;
                                    $grandTotalCreditBalance += $balance <= 0 ? $openingBalance : 0;
                                @endphp
                                @if (request('currency_type_id') == 2)
                                    <td class='text-center' colspan="10"><strong>Grand Total Balance</strong></td>
                                    <td class="text-right">
                                        <strong>{{ BdtNumFormat(abs($grandTotalFcDebitBalance), 2) }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ BdtNumFormat(abs($grandTotalFcCreditBalance), 2) }}</strong>
                                    </td>
                                    <td class="text-right"></td>
                                @elseif (request('currency_type_id') == 1)
                                    <td class='text-center' colspan="9"><strong>Grand Total Balance</strong></td>
                                    <td class="text-right">
                                        <strong>{{ BdtNumFormat(abs($grandTotalDebitBalance), 2) }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ BdtNumFormat(abs($grandTotalCreditBalance), 2) }}</strong>
                                    </td>
                                    <td class="text-right"></td>
                                @else
                                    <td class='text-center' colspan="10"><strong>Grand Total Balance</strong></td>
                                    <td class='text-right'>
                                        <strong>{{ BdtNumFormat(abs($grandTotalFcDebitBalance), 2) }}</strong>
                                    </td>
                                    <td class='text-right'>
                                        <strong>{{ BdtNumFormat(abs($grandTotalFcCreditBalance), 2) }}</strong>
                                    </td>
                                    <td></td>
                                    <td class='text-right'>
                                        <strong>{{ BdtNumFormat(abs($grandTotalDebitBalance), 2) }}</strong>
                                    </td>
                                    <td class='text-right'>
                                        <strong>{{ BdtNumFormat(abs($grandTotalCreditBalance), 2) }}</strong>
                                    </td>
                                    <td></td>
                                @endif
                            </tr>

                        @endif
                    </tbody>
                @else
                    <tbody>
                    <tr>
                        <td colspan="17" class="text-center text-danger">No Account Data Available</td>
                    </tr>
                    </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
