<style>
    .single-item {
        border-bottom: 1px solid #000;
    }
</style>
<div class="row">
    <div class="col-md-12">
        {!! $header !!}
        <table style="margin-bottom: 5px;" class="borderless">
            <tr>
                <td style="width:150px;border: 1px solid transparent !important;"><b>Account Head:</b></td>
                <td style="border: 1px solid transparent !important;">{{$account->name ?? ''}}</td>
            </tr>
        </table>
        <div class="table-responsive">
            @if(request()->get('type') != 'pdf')
            <div class="parentTableFixed" style="overflow: auto">
            @endif
                <table class="reportTable fixTable" style="width: 100%">
                    <thead class="thead-light">
                    <tr>
                        <th rowspan="2" class='text-center'>TRAN. DATE</th>
                        <th rowspan="2" class='text-center'>UNIT</th>
                        <th rowspan="2" class='text-center'>DEPT.</th>
                        <th rowspan="2" class='text-center'>COST CENTER</th>
                        <th rowspan="2" class='text-center'>ACC. CODE</th>
                        <th rowspan="2" class='text-left'>ACC. HEAD</th>
                        <th rowspan="2" class="text-center">PARTICULARS</th>
                        <th rowspan="2" class='text-center'>REF. NO</th>
                        <th rowspan="2" class='text-center'>VOUCHER NO</th>
                        @if (request('currency_type_id') == 2)
                            <th rowspan="2" class='text-center'>CONV. RATE</th>
                            <th colspan="3" class="text-center">FOREIGN CURRENCY</th>
                        @elseif (request('currency_type_id') == 1)
                            <th colspan="3" class="text-center">HOME CURRENCY [BDT]</th>
                        @else
                            <th rowspan="2" class='text-center'>CONV. RATE</th>
                            <th colspan="3" class="text-center">FOREIGN CURRENCY</th>
                            <th colspan="3" class="text-center">HOME CURRENCY [BDT]</th>
                        @endif
                    </tr>
                    <tr>
                        @if (request('currency_type_id') == 2)
                            <th class="text-right">DEBIT</th>
                            <th class="text-right">CREDIT</th>
                            <th class="text-right">BALANCE</th>
                        @elseif (request('currency_type_id') == 1)
                            <th class="text-right">DEBIT</th>
                            <th class="text-right">CREDIT</th>
                            <th class="text-right">BALANCE</th>
                        @else
                            <th class="text-right">DEBIT</th>
                            <th class="text-right">CREDIT</th>
                            <th class="text-right">BALANCE</th>
                            <th class="text-right">DEBIT</th>
                            <th class="text-right">CREDIT</th>
                            <th class="text-right">BALANCE</th>
                        @endif
                    </tr>
                    </thead>
                    @if(isset($account))
                        <tbody>
                            {{-- Balance Forward start --}}
                            <tr>
                                <td class='text-center'>
                                    @php
                                        if (request()->has('start_date')){
                                            $date = \Carbon\Carbon::parse(request('start_date'));
                                        }else{
                                            $date = $start_date ? \Carbon\Carbon::parse($start_date) : \Carbon\Carbon::today();
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
                            @if (!empty($account->journalEntries))
                                @forelse($account->journalEntries->sortBy('trn_date') as $index=>$journalEntry)
                                    @php
                                        if ($journalEntry->trn_type == 'dr') {
                                        $balance += $journalEntry->trn_amount;
                                        $fcBalance += $journalEntry->fc;
                                        } else {
                                        $balance -= $journalEntry->trn_amount;
                                        $fcBalance -= $journalEntry->fc;
                                        }
                                        $currency =  collect(\SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService::currencies())->where('id', $journalEntry->currency_id)->first()['name'] ?? null;
                                        $count = sizeof($journalEntry->voucher->details->items);
                                        $account_code = '';
                                        $account_name = '';
                                        $narration = '';
                                    @endphp
                                    {{-- voucher account_name start --}}
                                    @if ($journalEntry->voucher->details->type_id == 1 && request('account_id') != $journalEntry->voucher->details->credit_account)
                                        @php
                                            $account_code =  $journalEntry->voucher->details->credit_account_code;
                                            $account_name =  $journalEntry->voucher->details->credit_account_name;
                                        @endphp
                                    @elseif ($journalEntry->voucher->details->type_id == 2 && request('account_id') != $journalEntry->voucher->details->debit_account)
                                        @php
                                            $account_code =  $journalEntry->voucher->details->debit_account_code;
                                            $account_name =  $journalEntry->voucher->details->debit_account_name;
                                        @endphp
                                    @else
                                        @foreach($journalEntry->voucher->details->items as $key=>$item)
                                            @if(request('account_id') != $item->account_id && $journalEntry->trn_type == 'dr' && $item->credit > 0)
                                                @php
                                                    $account_code =  $item->account_code;
                                                    $account_name =  $item->account_name;
                                                    $narration =  $item->narration;
                                                    break;
                                                @endphp
                                            @endif
                                            @if(request('account_id') != $item->account_id && $journalEntry->trn_type == 'cr' && $item->debit > 0)
                                                @php
                                                    $account_code =  $item->account_code;
                                                    $account_name =  $item->account_name;
                                                    $narration =  $item->narration;
                                                    break;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    {{-- voucher account_name end --}}
                                    <tr>
                                        <td class='text-center'>
                                            {{ $journalEntry->trn_date->toFormattedDateString() }}
                                        </td>
                                        <td class="text-center">{{isset($journalEntry->unit_id) ? $journalEntry->unit->unit : '' }}</td>
                                        <td class="text-center">{{isset($journalEntry->department_id) ? $journalEntry->department->department : '' }}</td>
                                        <td class="text-center">{{isset($journalEntry->cost_center_id) ? $journalEntry->cost_center->cost_center : '' }}</td>

                                        <td class="text-center">{{isset($account_code) ? $account_code : '' }}</td>
                                        <td class="text-left">{{isset($account_name) ? $account_name : '' }}</td>
                                        <td class="text-center">{{isset($narration) ? $narration : '' }}</td>

                                        <td class='text-center'>
                                            {{$journalEntry->voucher->reference_no ?? ''}}
                                        </td>
                                        <td class='text-center'>
                                            @if($count > 2)
                                            <a href={{ url('/basic-finance/vouchers/'.$journalEntry->voucher_id) }} target="_blank" style="text-decoration:underline;font-size: 14px;" ><b>{{ $journalEntry->voucher_no}}</b></a>

                                            {{-- <a data-id="{{$journalEntry->voucher_no}}" data-account="{{request('account_id')}}" class="voucherInfo btn btn-default btn-sm"><b>{{$journalEntry->voucher_no}}</b></a> --}}
                                            @else
                                            <a href={{ url('/basic-finance/vouchers/'.$journalEntry->voucher_id) }} target="_blank"><b>{{ $journalEntry->voucher_no}}</b></a>


                                            {{-- {{$journalEntry->voucher_no}} --}}
                                            @endif
                                        </td>

                                        @if (request('currency_type_id') == 2)
                                            <td>
                                                {{$currency . '@'. $journalEntry->conversion_rate}}
                                            </td>
                                            <td class="text-right">
                                                {{ $journalEntry->trn_type == 'dr' ? BdtNumFormat($journalEntry->fc, 2) : '' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $journalEntry->trn_type == 'cr' ? BdtNumFormat($journalEntry->fc, 2) : ''}}
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
                                        @elseif (request('currency_type_id') == 1)
                                            <td class="text-right">
                                                {{ $journalEntry->trn_type == 'dr' ? BdtNumFormat($journalEntry->trn_amount, 2) : '' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $journalEntry->trn_type == 'cr' ? BdtNumFormat($journalEntry->trn_amount, 2) : ''}}
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
                                        @else
                                            <td>
                                                {{$currency . '@'. $journalEntry->conversion_rate}}
                                            </td>
                                            <td class="text-right">
                                                {{ $journalEntry->trn_type == 'dr' ? BdtNumFormat($journalEntry->fc, 2) : '' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $journalEntry->trn_type == 'cr' ? BdtNumFormat($journalEntry->fc, 2) : ''}}
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
                                            <td class="text-right">
                                                {{ $journalEntry->trn_type == 'dr' ? BdtNumFormat($journalEntry->trn_amount, 2) : '' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $journalEntry->trn_type == 'cr' ? BdtNumFormat($journalEntry->trn_amount, 2) : ''}}
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
                                        $totalFcDebitBalance = $account->journalEntries->sum(function($item) {
                                            return $item->trn_type == 'dr' ? $item->fc : 0;
                                        });

                                        $totalFcCreditBalance = $account->journalEntries->sum(function($item) {
                                            return $item->trn_type == 'cr' ? $item->fc : 0;
                                        });

                                        $totalDebitBalance = $account->journalEntries->sum(function($item) {
                                            return $item->trn_type == 'dr' ? $item->trn_amount : 0;
                                        });

                                        $totalCreditBalance = $account->journalEntries->sum(function($item) {
                                            return $item->trn_type == 'cr' ? $item->trn_amount : 0;
                                        });
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
                                            <strong>{{ BdtNumFormat($grandTotalFcDebitBalance, 2) }}</strong>
                                        </td>
                                        <td class="text-right">
                                            <strong>{{ BdtNumFormat($grandTotalFcCreditBalance, 2) }}</strong>
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
                                            <strong>{{ BdtNumFormat($grandTotalFcDebitBalance, 2) }}</strong>
                                        </td>
                                        <td class='text-right'>
                                            <strong>{{ BdtNumFormat($grandTotalFcCreditBalance, 2) }}</strong>
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
            @if(request()->get('type') != 'pdf')
            </div>
            @endif
        </div>
    </div>
</div>

