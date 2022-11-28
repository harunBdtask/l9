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
                    @forelse($account->journalEntries as $index=>$journalEntry)
                        @php
                            $account_code =  '';
                            $account_name =  '';
                            $narration =  '';
                            if ($journalEntry->trn_type == 'dr') {
                            $balance += $journalEntry->trn_amount;
                            $fcBalance += $journalEntry->fc;
                            } else {
                            $balance -= $journalEntry->trn_amount;
                            $fcBalance -= $journalEntry->fc;
                            }
                            $currency =  collect(\SkylarkSoft\GoRMG\Finance\Services\CurrencyService::currencies())->where('id', $journalEntry->currency_id)->first()['name'] ?? null;
                        @endphp
                        @foreach($journalEntry->voucher->details->items as $key=>$item)
                            @if(request('account_id') != $item->account_id)
                                @php
                                    $account_code =  $item->account_code;
                                    $account_name =  $item->account_name;
                                    $narration =  $item->narration;
                                    break;
                                @endphp
                            @endif
                        @endforeach

                        <tr>
                            <td style="border: 1px solid black;" class='text-left'>
                                {{ $journalEntry->trn_date->toFormattedDateString() }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($journalEntry->unit_id) ? $journalEntry->unit->unit : '' }}</td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($journalEntry->department_id) ? $journalEntry->department->department : '' }}</td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($journalEntry->cost_center_id) ? $journalEntry->cost_center->cost_center : '' }}</td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($account_code) ? (string)$account_code : '' }}</td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($account_name) ? $account_name : '' }}</td>
                            <td style="border: 1px solid black;" class="text-right">{{isset($narration) ? $narration : '' }}</td>
                            <td style="border: 1px solid black;" class='text-left'>
                                {{$journalEntry->voucher->reference_no ?? ''}}
                            </td>
                            <td style="border: 1px solid black;" class='text-left'>
                                {{$journalEntry->voucher_no}}
                            </td>

                            @if (request('currency_type_id') == 2)
                                <td style="border: 1px solid black;">
                                    {{$currency . '@'. $journalEntry->conversion_rate}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'dr' ? round($journalEntry->fc, 2) : '' }}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'cr' ? round($journalEntry->fc, 2) : ''}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    @if($loop->last)
                                        @if($fcBalance >= 0)
                                            <strong>{{ round(abs($fcBalance), 2).' Dr' }}</strong>
                                        @else
                                            <strong>{{ round(abs($fcBalance), 2).' Cr' }}</strong>
                                        @endif
                                    @else
                                        @if($fcBalance >= 0)
                                            {{ round(abs($fcBalance), 2).' Dr' }}
                                        @else
                                            {{ round(abs($fcBalance), 2).' Cr' }}
                                        @endif
                                    @endif
                                </td>
                            @elseif (request('currency_type_id') == 1)
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'dr' ? round($journalEntry->trn_amount, 2) : '' }}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'cr' ? round($journalEntry->trn_amount, 2) : ''}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    @if($loop->last)
                                        @if($balance >= 0)
                                            <strong>{{ round(abs($balance), 2).' Dr' }}</strong>
                                        @else
                                            <strong>{{ round(abs($balance), 2).' Cr' }}</strong>
                                        @endif
                                    @else
                                        @if($balance >= 0)
                                            {{ round(abs($balance), 2).' Dr' }}
                                        @else
                                            {{ round(abs($balance), 2).' Cr' }}
                                        @endif
                                    @endif
                                </td>
                            @else
                                <td style="border: 1px solid black;">
                                    {{$currency . '@'. $journalEntry->conversion_rate}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'dr' ? round($journalEntry->fc, 2) : '' }}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'cr' ? round($journalEntry->fc, 2) : ''}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    @if($loop->last)
                                        @if($fcBalance >= 0)
                                            <strong>{{ round(abs($fcBalance), 2).' Dr' }}</strong>
                                        @else
                                            <strong>{{ round(abs($fcBalance), 2).' Cr' }}</strong>
                                        @endif
                                    @else
                                        @if($fcBalance >= 0)
                                            {{ round(abs($fcBalance), 2).' Dr' }}
                                        @else
                                            {{ round(abs($fcBalance), 2).' Cr' }}
                                        @endif
                                    @endif
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'dr' ? round($journalEntry->trn_amount, 2) : '' }}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    {{ $journalEntry->trn_type == 'cr' ? round($journalEntry->trn_amount, 2) : ''}}
                                </td>
                                <td style="border: 1px solid black;" class="text-right">
                                    @if($loop->last)
                                        @if($balance >= 0)
                                            <strong>{{ round(abs($balance), 2).' Dr' }}</strong>
                                        @else
                                            <strong>{{ round(abs($balance), 2).' Cr' }}</strong>
                                        @endif
                                    @else
                                        @if($balance >= 0)
                                            {{ round(abs($balance), 2).' Dr' }}
                                        @else
                                            {{ round(abs($balance), 2).' Cr' }}
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
                        @if (request('currency_type_id') == 2)
                            <td style="border: 1px solid black;" class='text-center' colspan="10"><strong>TOTAL</strong></td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    round($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'dr' ? $item->fc : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    round($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'cr' ? $item->fc : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right"></td>
                        @elseif (request('currency_type_id') == 1)
                            <td style="border: 1px solid black;" class='text-center' colspan="9"><strong>TOTAL</strong></td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    round($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'dr' ? $item->trn_amount : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    round($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'cr' ? $item->trn_amount : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right"></td>
                        @else
                            <td style="border: 1px solid black;" class='text-center' colspan="10"><strong>TOTAL</strong></td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    round($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'dr' ? $item->fc : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    round($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'cr' ? $item->fc : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right"></td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    round($account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'dr' ? $item->trn_amount : 0;
                                    }), 2)
                                }}
                            </td>
                            <td style="border: 1px solid black;" class="text-right">
                                {{
                                    round($account->journalEntries->sum(function($item) {
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
