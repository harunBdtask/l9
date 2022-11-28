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
            <div class="parentTableFixed" style="overflow: auto">
                <table class="reportTable fixTable" style="width: 100%">
                    <thead class="thead-light">
                    <tr>
                        <th rowspan="2" class='text-center'>TRAN. DATE</th>
                        <th rowspan="2" class='text-center'>PROJECT</th>
                        <th rowspan="2" class='text-center'>UNIT</th>
                        <th rowspan="2" class='text-center'>DEPT.</th>
                        <th rowspan="2" class='text-center'>COST CENTER</th>
                        <th rowspan="2" class='text-center'>ACC. CODE</th>
                        <th rowspan="2" class='text-center'>ACC. HEAD</th>
                        <th rowspan="2" class="text-center" colspan="2">PARTICULARS</th>
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
                            <th class="text-center">DEBIT</th>
                            <th class="text-center">CREDIT</th>
                            <th class="text-center">BALANCE</th>
                        @elseif (request('currency_type_id') == 1)
                            <th class="text-center">DEBIT</th>
                            <th class="text-center">CREDIT</th>
                            <th class="text-center">BALANCE</th>
                        @else
                            <th class="text-center">DEBIT</th>
                            <th class="text-center">CREDIT</th>
                            <th class="text-center">BALANCE</th>
                            <th class="text-center">DEBIT</th>
                            <th class="text-center">CREDIT</th>
                            <th class="text-center">BALANCE</th>
                        @endif
                    </tr>
                    </thead>
                    @if(isset($account))
                        <tbody>
                        <tr>
                            <td colspan="1" class='text-left'>
                                @php
                                    $date = \Carbon\Carbon::today();
                                    if (request()->has('start_date')){
                                        $date = \Carbon\Carbon::parse(request('start_date'));
                                    }
                                @endphp
                                {{ $date->toFormattedDateString() }}
                            </td>
                            @if (request('currency_type_id') == 2)
                                <td class='text-center' colspan="13"><strong>Balance Forward</strong></td>
                                <td class="text-right">
                                    @if($balance >= 0)
                                        <strong>{{ BdtNumFormat(abs($openingFcBalance), 2).' Dr' }}</strong>
                                    @else
                                        <strong>{{ BdtNumFormat(abs($openingFcBalance), 2).' Cr' }}</strong>
                                    @endif
                                </td>
                            @elseif (request('currency_type_id') == 1)
                                <td class='text-center' colspan="12"><strong>Balance Forward</strong></td>
                                <td class="text-right">
                                    @if($balance >= 0)
                                        <strong>{{ BdtNumFormat(abs($openingBalance), 2).' Dr' }}</strong>
                                    @else
                                        <strong>{{ BdtNumFormat(abs($openingBalance), 2).' Cr' }}</strong>
                                    @endif
                                </td>
                            @else
                                <td class='text-center' colspan="13"><strong>Balance Forward</strong></td>
                                <td class="text-right">
                                    @if($balance >= 0)
                                        <strong>{{ BdtNumFormat(abs($openingFcBalance), 2).' Dr' }}</strong>
                                    @else
                                        <strong>{{ BdtNumFormat(abs($openingFcBalance), 2).' Cr' }}</strong>
                                    @endif
                                </td>
                                <td colspan=3" class="text-right">
                                    @if($balance >= 0)
                                        <strong>{{ BdtNumFormat(abs($openingBalance), 2).' Dr' }}</strong>
                                    @else
                                        <strong>{{ BdtNumFormat(abs($openingBalance), 2).' Cr' }}</strong>
                                    @endif
                                </td>
                            @endif
                        </tr>

                        @if((float)$openingBalance === 0.00)
                            @if (isset($account->journalEntries) && !empty($account->journalEntries))
                                @forelse($account->journalEntries as $index=>$journalEntry)
                                    @php
                                        if ($journalEntry->trn_type == 'dr') {
                                            $balance += $journalEntry->trn_amount;
                                            $fcBalance += $journalEntry->fc;
                                        } else {
                                            $balance -= $journalEntry->trn_amount;
                                            $fcBalance -= $journalEntry->fc;
                                        }
                                        $currency =  collect(\SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService::currencies())
                                                                        ->where('id', $journalEntry->currency_id)->first()['name'] ?? null;
                                        $count = sizeof($journalEntry->voucher->details->items);
                                    @endphp
                                    <tr>
                                        <td class='text-left'>
                                            {{ $journalEntry->trn_date->toFormattedDateString() }}
                                        </td>
                                        <td class="text-right">{{isset($journalEntry->project_id) ? $journalEntry->project->project : '' }}</td>
                                        <td class="text-right">{{isset($journalEntry->unit_id) ? $journalEntry->unit->unit : '' }}</td>
                                        <td class="text-right">{{isset($journalEntry->department_id) ? $journalEntry->department->department : '' }}</td>
                                        <td class="text-right">{{isset($journalEntry->cost_center_id) ? $journalEntry->cost_center->cost_center : '' }}</td>
                                        @if($count <= 2)
                                            <td class="text-left">
                                                @if(count($journalEntry->voucher->details->items)==1)
                                                    <p class="{{ $count > 2 ? 'single-item' : ''  }}">
                                                        {{  $journalEntry->voucher->details->type_id==1?$journalEntry->voucher->details->credit_account_code:$journalEntry->voucher->details->debit_account_code }}
                                                    </p>
                                                @else
                                                    @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                        @if(request('account_id') != $item->account_id)
                                                            <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->account_code }}</p>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="text-left">
                                                @if(count($journalEntry->voucher->details->items)==1)
                                                    <p class="{{ $count > 2 ? 'single-item' : ''  }}">
                                                        {{  $journalEntry->voucher->details->type_id==1?$journalEntry->voucher->details->credit_account_name:$journalEntry->voucher->details->debit_account_name }}
                                                    </p>
                                                @else
                                                    @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                        @if(request('account_id') != $item->account_id)
                                                            <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->account_name  }}</p>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="text-left">
                                                @if(count($journalEntry->voucher->details->items)==1)
                                                    <p class="{{ $count > 2 ? 'single-item' : ''  }}">
                                                        {{  $journalEntry->voucher->details->items[0]->narration??'' }}
                                                    </p>
                                                @else
                                                    @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                        @if(request('account_id') != $item->account_id)
                                                            <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->narration ?? ''  }}</p>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($count > 2)
                                                    @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                        @if(request('account_id') != $item->account_id)
                                                            @if($item->debit > 0)
                                                                <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ BdtNumFormat($item->debit).' dr'  }}</p>
                                                            @elseif($item->credit > 0)
                                                                <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ BdtNumFormat($item->credit).' cr'  }}</p>
                                                            @else
                                                                <p class="{{ $count > 2 ? 'single-item' : ''  }}">0</p>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        @else
                                            <td class="text-left" colspan="4">
                                                <b>Details are as below:</b>
                                            </td>
                                        @endif
                                        <td class='text-left'>
                                            {{$journalEntry->voucher->reference_no ?? ''}} <br>
                                            {{$journalEntry->voucher->bill_no ?? ''}}
                                        </td>
                                        <td class='text-left'>
                                            @<a href={{ url('basic-finance/vouchers/'.$journalEntry->voucher_id) }} target="_blank"><b>{{ $journalEntry->voucher_no}}</b></a>
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
                                                        <strong>{{ BdtNumFormat(abs($balance)).' Dr' }}</strong>
                                                    @else
                                                        <strong>{{ BdtNumFormat(abs($balance)).' Cr' }}</strong>
                                                    @endif
                                                @else
                                                    @if($balance >= 0)
                                                        {{ BdtNumFormat(abs($balance)).' Dr' }}
                                                    @else
                                                        {{ BdtNumFormat(abs($balance)).' Cr' }}
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                    @if($count > 2)
                                        <tr>
                                            <td class='text-left'>

                                            </td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-left">
                                                @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                    @if(request('account_id') != $item->account_id)
                                                        <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->account_code }}</p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="text-left">
                                                @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                    @if(request('account_id') != $item->account_id)
                                                        <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->account_name  }}</p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="text-left">
                                                @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                    @if(request('account_id') != $item->account_id)
                                                        <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->narration ?? ''  }}</p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="text-right">
                                                @if($count > 2)
                                                    @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                        @if(request('account_id') != $item->account_id)
                                                            @if(@$item->debit > 0)
                                                                <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ BdtNumFormat($item->debit).' dr'  }}</p>
                                                            @elseif(@$item->credit > 0)
                                                                <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ BdtNumFormat($item->credit).' cr'  }}</p>
                                                            @else
                                                                <p class="{{ $count > 2 ? 'single-item' : ''  }}">0</p>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class='text-left'>

                                            </td>
                                            <td class='text-left'>

                                            </td>

                                            @if (request('currency_type_id') == 2)
                                                <td>

                                                </td>
                                                <td class="text-right">

                                                </td>
                                                <td class="text-right">

                                                </td>
                                                <td class="text-right">

                                                </td>
                                            @elseif (request('currency_type_id') == 1)
                                                <td class="text-right">

                                                </td>
                                                <td class="text-right">

                                                </td>
                                                <td class="text-right">

                                                </td>
                                            @else
                                                <td>

                                                </td>
                                                <td class="text-right">

                                                </td>
                                                <td class="text-right">

                                                </td>
                                                <td class="text-right">

                                                </td>
                                                <td class="text-right">

                                                </td>
                                                <td class="text-right">

                                                </td>
                                                <td class="text-right">

                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="18" class="text-center text-danger">No transaction</td>
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
                                        <td class='text-center' colspan="12"><strong>TOTAL</strong></td>
                                        <td class="text-right">
                                            {{ BdtNumFormat($totalFcDebitBalance, 2) }}
                                        </td>
                                        <td class="text-right">
                                            {{ BdtNumFormat($totalFcCreditBalance, 2) }}
                                        </td>
                                        <td class="text-right"></td>
                                    @elseif (request('currency_type_id') == 1)
                                        <td class='text-center' colspan="11"><strong>TOTAL</strong></td>
                                        <td class="text-right">
                                            {{ BdtNumFormat($totalDebitBalance, 2) }}
                                        </td>
                                        <td class="text-right">
                                            {{ BdtNumFormat($totalCreditBalance, 2) }}
                                        </td>
                                        <td class="text-right"></td>
                                    @else
                                        <td class='text-center' colspan="12"><strong>TOTAL</strong></td>
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
                                        <td class='text-center' colspan="12"><strong>Closing Balance</strong></td>
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
                                        <td class='text-center' colspan="11"><strong>Closing Balance</strong></td>
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
                                        <td class='text-center' colspan="12"><strong>Closing Balance</strong></td>
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
                                        <td class='text-center' colspan="12"><strong>Grand Total Balance</strong></td>
                                        <td class="text-right">
                                            <strong>{{ BdtNumFormat($grandTotalFcDebitBalance, 2) }}</strong>
                                        </td>
                                        <td class="text-right">
                                            <strong>{{ BdtNumFormat($grandTotalFcCreditBalance, 2) }}</strong>
                                        </td>
                                        <td class="text-right"></td>
                                    @elseif (request('currency_type_id') == 1)
                                        <td class='text-center' colspan="11"><strong>Grand Total Balance</strong></td>
                                        <td class="text-right">
                                            <strong>{{ BdtNumFormat(abs($grandTotalDebitBalance), 2) }}</strong>
                                        </td>
                                        <td class="text-right">
                                            <strong>{{ BdtNumFormat(abs($grandTotalCreditBalance), 2) }}</strong>
                                        </td>
                                        <td class="text-right"></td>
                                    @else
                                        <td class='text-center' colspan="12"><strong>Grand Total Balance</strong></td>
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
                        </tbody>
                    @endif
                    @else
                        @if (isset($account->journalEntries) && !empty($account->journalEntries))
                            @forelse($account->journalEntries as $index=>$journalEntry)
                                @php
                                    if ($journalEntry->trn_type == 'dr') {
                                    $balance += $journalEntry->trn_amount;
                                    $fcBalance += $journalEntry->fc;
                                    } else {
                                    $balance -= $journalEntry->trn_amount;
                                    $fcBalance -= $journalEntry->fc;
                                    }
                                    $currency =  collect(\SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService::currencies())
                                                                    ->where('id', $journalEntry->currency_id)->first()['name'] ?? null;
                                    $count = sizeof($journalEntry->voucher->details->items);
                                @endphp
                                <tr>
                                    <td class='text-left'>
                                        {{ $journalEntry->trn_date->toFormattedDateString() }}
                                    </td>
                                    <td class="text-right">{{isset($journalEntry->project_id) ? $journalEntry->project->project : '' }}</td>
                                    <td class="text-right">{{isset($journalEntry->unit_id) ? $journalEntry->unit->unit : '' }}</td>
                                    <td class="text-right">{{isset($journalEntry->department_id) ? $journalEntry->department->department : '' }}</td>
                                    <td class="text-right">{{isset($journalEntry->cost_center_id) ? $journalEntry->cost_center->cost_center : '' }}</td>
                                    @if($count <= 2)
                                        <td class="text-left">
                                            @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                @if(request('account_id') != $item->account_id)
                                                    <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->account_code }}</p>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-left">
                                            @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                @if(request('account_id') != $item->account_id)
                                                    <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->account_name  }}</p>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-left">
                                            @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                @if(request('account_id') != $item->account_id)
                                                    <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->narration ?? ''  }}</p>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            @if($count > 2)
                                                @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                    @if(request('account_id') != $item->account_id)
                                                        @if(@$item->debit > 0)
                                                            <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ BdtNumFormat($item->debit).' dr'  }}</p>
                                                        @elseif(@$item->credit > 0)
                                                            <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ BdtNumFormat($item->credit).' cr'  }}</p>
                                                        @else
                                                            <p class="{{ $count > 2 ? 'single-item' : ''  }}">0</p>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    @else
                                        <td class="text-left" colspan="4">
                                            <b>Details are as below:</b>
                                        </td>
                                    @endif
                                    <td class='text-left'>
                                        {{$journalEntry->voucher->reference_no ?? ''}} <br>
                                        {{$journalEntry->voucher->bill_no ?? ''}}
                                    </td>
                                    <td class='text-left'>
                                        <a href={{ url('basic-finance/vouchers/'.$journalEntry->voucher_id) }} target="_blank"><b>{{ $journalEntry->voucher_no}}</b></a>
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
                                @if($count > 2)
                                    <tr>
                                        <td class='text-left'>

                                        </td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-left">
                                            @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                @if(request('account_id') != $item->account_id)
                                                    <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->account_code }}</p>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-left">
                                            @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                @if(request('account_id') != $item->account_id)
                                                    <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->account_name  }}</p>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-left">
                                            @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                @if(request('account_id') != $item->account_id)
                                                    <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ $item->narration ?? ''  }}</p>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            @if($count > 2)
                                                @foreach($journalEntry->voucher->details->items as $key=>$item)
                                                    @if(request('account_id') != $item->account_id)
                                                        @if(@$item->debit > 0)
                                                            <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ BdtNumFormat($item->debit).' dr'  }}</p>
                                                        @elseif(@$item->credit > 0)
                                                            <p class="{{ $count > 2 ? 'single-item' : ''  }}">{{ BdtNumFormat($item->credit).' cr'  }}</p>
                                                        @else
                                                            <p class="{{ $count > 2 ? 'single-item' : ''  }}">0</p>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class='text-left'>

                                        </td>
                                        <td class='text-left'>

                                        </td>

                                        @if (request('currency_type_id') == 2)
                                            <td>

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                        @elseif (request('currency_type_id') == 1)
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                        @else
                                            <td>

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="18" class="text-center text-danger">No transaction</td>
                                </tr>
                            @endforelse
                            <tr>
                                @php
                                    $totalDebitFcBalance = $account->journalEntries->sum(function($item) {
                                        return $item->trn_type == 'dr' ? $item->fc : 0;
                                    });

                                    $totalCreditFcBalance = $account->journalEntries->sum(function($item) {
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
                                    <td class='text-center' colspan="12"><strong>TOTAL</strong></td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalDebitFcBalance, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalCreditFcBalance, 2) }}
                                    </td>
                                    <td class="text-right"></td>
                                @elseif (request('currency_type_id') == 1)
                                    <td class='text-center' colspan="11"><strong>TOTAL</strong></td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalDebitBalance, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalCreditBalance, 2) }}
                                    </td>
                                    <td class="text-right"></td>
                                @else
                                    <td class='text-center' colspan="12"><strong>TOTAL</strong></td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalDebitFcBalance, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($totalCreditFcBalance, 2) }}
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
                                    <td class='text-center' colspan="12"><strong>Closing Balance</strong></td>
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
                                    <td class='text-center' colspan="11"><strong>Closing Balance</strong></td>
                                    <td class="text-right">
                                        @if($balance < 0)
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
                                    <td class='text-center' colspan="12"><strong>Closing Balance</strong></td>
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
                                    <td></td>
                                    <td class="text-right">
                                        @if($balance < 0)
                                            <strong>{{ BdtNumFormat(abs($balance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($balance >= 0)
                                            <strong>{{ BdtNumFormat(abs($balance), 2) }}</strong>
                                        @endif
                                    </td>
                                    <td></td>
                                @endif
                            </tr>
                            <tr>
                                @php
                                    $grandTotalDebitFcBalance = $totalDebitFcBalance;
                                    $grandTotalDebitFcBalance += $fcBalance < 0 ? abs($fcBalance) : 0;
                                    $grandTotalDebitFcBalance += $fcBalance >= 0 ? $openingFcBalance : 0;

                                    $grandTotalCreditFcBalance = $totalCreditFcBalance;
                                    $grandTotalCreditFcBalance += $fcBalance >= 0 ? abs($fcBalance) : 0;
                                    $grandTotalCreditFcBalance += $fcBalance < 0 ? $openingFcBalance : 0;

                                    $grandTotalDebitBalance = $totalDebitBalance;
                                    $grandTotalDebitBalance += $balance < 0 ? abs($balance) : 0;
                                    $grandTotalDebitBalance += $balance >= 0 ? $openingBalance : 0;

                                    $grandTotalCreditBalance = $totalCreditBalance;
                                    $grandTotalCreditBalance += $balance >= 0 ? abs($balance) : 0;
                                    $grandTotalCreditBalance += $balance < 0 ? $openingBalance : 0;
                                @endphp
                                @if (request('currency_type_id') == 2)
                                    <td class='text-center' colspan="12"><strong>Grand Total Balance</strong></td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($grandTotalDebitFcBalance, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($grandTotalCreditFcBalance, 2) }}
                                    </td>
                                    <td class="text-right"></td>
                                @elseif (request('currency_type_id') == 1)
                                    <td class='text-center' colspan="11"><strong>Grand Total Balance</strong></td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($grandTotalDebitBalance, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ BdtNumFormat($grandTotalCreditBalance, 2) }}
                                    </td>
                                    <td class="text-right"></td>
                                @else
                                    <td class='text-center' colspan="12"><strong>Grand Total Balance</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ BdtNumFormat($grandTotalDebitFcBalance, 2) }}</strong></td>
                                    <td class="text-right">
                                        <strong>{{ BdtNumFormat($grandTotalCreditFcBalance, 2) }}</strong></td>
                                    <td></td>
                                    <td class="text-right">
                                        <strong>{{ BdtNumFormat($grandTotalDebitBalance, 2) }}</strong></td>
                                    <td class="text-right">
                                        <strong>{{ BdtNumFormat($grandTotalCreditBalance, 2) }}</strong></td>
                                    <td></td>
                                @endif
                            </tr>
                            </tbody>
                        @endif
                    @endif
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
</div>

