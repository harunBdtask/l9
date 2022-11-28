<div class="row">
    <div class="col-md-12">
        <table class="reportTable">
            <thead class="thead-light">
            <tr>
                <th class='text-left' nowrap>HEAD OF ACCOUNTS</th>
                <th class='text-left' nowrap>ACCOUNT CODE</th>
                <th class="text-right" nowrap>AMOUNT</th>
            </tr>
            </thead>
            <tbody>

            @foreach($accounts_by_type as $type => $accounts)
                <tr>
                    <td colspan="2" nowrap class='text-left'><strong>{{  strtoupper($type) }}</strong></td>
                    <td></td>
                </tr>
                @foreach($accounts as $account)
                    <tr>
                        <td class='text-left'>{{ $account->name }}</td>
                        <td class='text-left' nowrap>{{ $account->code }}</td>
                        <td class='text-right' nowrap>
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
                    <td colspan="2" nowrap class='text-left'><strong>{{ strtoupper('Total of '.$type) }}</strong></td>
                    <td class="text-right" nowrap>
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
                <td colspan="2" nowrap class='text-left'>
                    <strong>{{ strtoupper('Net Profit/Loss') }}</strong>
                </td>
                <td class="text-right" nowrap>
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
    </div>
</div>
