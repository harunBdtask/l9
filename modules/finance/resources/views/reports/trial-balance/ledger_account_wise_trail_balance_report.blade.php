<table class="reportTable">
    <thead style="background-color: #ffe598;">
    <tr>
        <th rowspan="2">AC TYPE</th>
        <th rowspan="2">PARENT ACCOUNT</th>
        <th rowspan="2">GROUP ACCOUNT</th>
        <th rowspan="2">CONTROL ACCOUNT</th>
        <th rowspan="2">LEDGER ACCOUNT</th>
        <th colspan="2">Opening</th>
        <th colspan="2">Transaction</th>
        <th colspan="2">Balance</th>
    </tr>
    <tr>
        <th>Debit [BDT]</th>
        <th>Credit [BDT]</th>
        <th>Debit [BDT]</th>
        <th>Credit [BDT]</th>
        <th>Debit [BDT]</th>
        <th>Credit [BDT]</th>
    </tr>
    </thead>
    <tbody>
    @php
        $totalOpeningBalanceDebit = 0;
        $totalOpeningBalanceCredit = 0;
        $totalTransactionDebit = 0;
        $totalTransactionCredit = 0;
        $totalBalanceDebit = 0;
        $totalBalanceCredit = 0;
    @endphp
    @if(isset($data))
        @foreach($data as $reportData)
            @php
                $parentAccounts = collect($reportData);
                $index = 0;
            @endphp
            @if (count($parentAccounts))
                @foreach($parentAccounts as $groupAccounts)
                    @if (count($groupAccounts))
                        @php
                            $parentIndex = 0;
                        @endphp
                        @foreach ($groupAccounts as $controlAccounts)
                            @if (count($controlAccounts))
                                @php
                                    $groupIndex = 0;
                                @endphp
                                @foreach($controlAccounts as $ledgerAccounts)
                                    @php
                                        $controlIndex = 0;
                                    @endphp
                                    @if (count($ledgerAccounts))
                                        @foreach($ledgerAccounts as $ledgerAccount)
                                            @php
                                                $totalOpeningBalanceDebit += $ledgerAccount['opening_balance_debit'];
                                                $totalOpeningBalanceCredit += $ledgerAccount['opening_balance_credit'];
                                                $totalTransactionDebit += $ledgerAccount['transaction_debit'];
                                                $totalTransactionCredit += $ledgerAccount['transaction_credit'];
                                                $totalBalanceDebit += $ledgerAccount['balance_debit'];
                                                $totalBalanceCredit += $ledgerAccount['balance_credit'];
                                            @endphp
                                            <tr>
                                                @if ($index === 0)
                                                    <td rowspan="{{ collect($parentAccounts)->collapse()->collapse()->collapse()->count() }}">
                                                        <b>{{ $ledgerAccount['account_type'] }}</b>
                                                    </td>
                                                @endif
                                                @if ($parentIndex === 0)
                                                    <td rowspan="{{ collect($groupAccounts)->collapse()->collapse()->count() }}">
                                                        <b>{{ $ledgerAccount['parent_account_name'] }}</b>
                                                    </td>
                                                @endif
                                                @if ($groupIndex === 0)
                                                    <td rowspan="{{ collect($controlAccounts)->collapse()->count() }}">
                                                        <b>{{ $ledgerAccount['group_account_name'] }}</b>
                                                    </td>
                                                @endif
                                                @if ($controlIndex === 0)
                                                    <td rowspan="{{ count($ledgerAccounts) }}">
                                                        <b>{{ $ledgerAccount['control_account_name'] }}</b>
                                                    </td>
                                                @endif
                                                <td><b>{{ $ledgerAccount['account_name'] }}</b></td>
                                                <td class="text-right">{{ $ledgerAccount['opening_balance_debit'] }}</td>
                                                <td class="text-right">{{ $ledgerAccount['opening_balance_credit'] }}</td>
                                                <td class="text-right">{{ $ledgerAccount['transaction_debit'] }}</td>
                                                <td class="text-right">{{ $ledgerAccount['transaction_credit'] }}</td>
                                                <td class="text-right">{{ $ledgerAccount['balance_debit'] }}</td>
                                                <td class="text-right">{{ $ledgerAccount['balance_credit'] }}</td>
                                            </tr>
                                            @php($controlIndex++)
                                            @php($groupIndex++)
                                            @php($parentIndex++)
                                            @php($index++)
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
    </tbody>
    <tfoot>
    <tr style="background-color: lavender">
        <th class="text-center" colspan="5"><i>Total</i></th>
        <th class="text-right">{{ $totalOpeningBalanceDebit }}</th>
        <th class="text-right">{{ $totalOpeningBalanceCredit }}</th>
        <th class="text-right">{{ $totalTransactionDebit }}</th>
        <th class="text-right">{{ $totalTransactionCredit }}</th>
        <th class="text-right">{{ $totalBalanceDebit }}</th>
        <th class="text-right">{{ $totalBalanceCredit }}</th>
    </tr>
    </tfoot>
</table>
