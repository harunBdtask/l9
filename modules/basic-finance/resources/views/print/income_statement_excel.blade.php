@php
    use SkylarkSoft\GoRMG\BasicFinance\Models\Account
@endphp

<div class="page">
    <div>
        <div>
            <table>
                <tr>
                    <td colspan="3" class="text-left" style="background-color: lightblue"><b>{{factoryName()}}</b></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-left" style="background-color: lightblue"><b>{{factoryAddress()}}</b>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <td colspan="3" style="background-color: lightblue"><h3>Income Statement Report</h3></td>
                </tr>
            </table>
        </div>

        <table>
            <thead class="thead-light">
            <tr>
                <td style="background-color:lightgray; border:1px solid black;" class='text-left' nowrap>HEAD OF ACCOUNTS</td>
                <td style="background-color:lightgray; border:1px solid black;" class='text-left' nowrap>ACCOUNT CODE</td>
                <td style="background-color:lightgray; border:1px solid black;" class="text-right" nowrap>AMOUNT</td>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts_by_type as $type => $accounts)
                <tr>
                    <td style="border:1px solid black;" colspan="2" nowrap class='text-left'><strong>{{  strtoupper($type) }}</strong></td>
                    <td></td>
                </tr>
                @foreach($accounts as $account)
                    <tr>
                        <td style="border:1px solid black;" class='text-left'>{{ $account->name }}</td>
                        <td style="border:1px solid black;" class='text-left' nowrap>{{ $account->code }}</td>
                        <td style="border:1px solid black;" class='text-right' nowrap>
                            @if(in_array($account->type_id, [\SkylarkSoft\GoRMG\BasicFinance\Models\Account::REVENUE_OP, \SkylarkSoft\GoRMG\BasicFinance\Models\Account::REVENUE_NOP]))
                                {{ number_format(abs($account->balance), 2) }}
                            @elseif($account->balance < 0)
                                {{ '('.number_format(abs($account->balance), 2).')' }}
                            @else
                                {{ number_format($account->balance, 2) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="border:1px solid black;" colspan="2" nowrap class='text-left'><strong>{{ strtoupper('Total of '.$type) }}</strong></td>
                    <td style="border:1px solid black;" class="text-right" nowrap>
                        <strong>
                            @if(in_array($account->type_id, [\SkylarkSoft\GoRMG\BasicFinance\Models\Account::REVENUE_OP, \SkylarkSoft\GoRMG\BasicFinance\Models\Account::REVENUE_NOP]))
                                {{ number_format(abs($accounts->sum('balance')), 2) }}
                            @elseif($accounts->sum('balance') < 0)
                                {{ '('.number_format(abs($accounts->sum('balance')), 2).')' }}
                            @else
                                {{ number_format($accounts->sum('balance'), 2) }}
                            @endif
                        </strong>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td style="border:1px solid black;" colspan="2" nowrap class='text-left'>
                    <strong>{{ strtoupper('Net Profit/Loss') }}</strong>
                </td>
                <td style="border:1px solid black;" class="text-right" nowrap>
                    <strong>
                        @if($net_profit < 0)
                            {{ '('.number_format(abs($net_profit), 2).')' }}
                        @else
                            {{ number_format($net_profit, 2) }}
                        @endif
                    </strong>
                </td>
            </tr>
            </tbody>
        </table>
        <br>
    </div>
</div>
