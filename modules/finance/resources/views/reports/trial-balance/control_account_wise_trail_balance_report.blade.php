<table class="reportTable">
    <thead style="background-color: #ffe598;">
    <tr>
        <th rowspan="2">AC TYPE</th>
        <th rowspan="2">PARENT ACCOUNT</th>
        <th rowspan="2">GROUP ACCOUNT</th>
        <th rowspan="2">CONTROL ACCOUNT</th>
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
                                @foreach($controlAccounts as $controlAccount)
                                    @php
                                        $totalOpeningBalanceDebit += $controlAccount['opening_balance_debit'];
                                        $totalOpeningBalanceCredit += $controlAccount['opening_balance_credit'];
                                        $totalTransactionDebit += $controlAccount['transaction_debit'];
                                        $totalTransactionCredit += $controlAccount['transaction_credit'];
                                        $totalBalanceDebit += $controlAccount['balance_debit'];
                                        $totalBalanceCredit += $controlAccount['balance_credit'];
                                    @endphp
                                    <tr>
                                        @if ($index === 0)
                                            <td rowspan="{{ collect($parentAccounts)->collapse()->collapse()->count() }}">
                                                <b>{{ $controlAccount['account_type'] }}</b>
                                            </td>
                                        @endif
                                        @if ($parentIndex === 0)
                                            <td rowspan="{{ collect($groupAccounts)->collapse()->count() }}">
                                                <b>{{ $controlAccount['parent_account_name'] }}</b>
                                            </td>
                                        @endif
                                        @if ($groupIndex === 0)
                                            <td rowspan="{{ count($controlAccounts) }}">
                                                <b>{{ $controlAccount['group_account_name'] }}</b>
                                            </td>
                                        @endif
                                        <td><b>{{ $controlAccount['account_name'] }}</b></td>
                                        <td class="text-right">{{ $controlAccount['opening_balance_debit'] }}</td>
                                        <td class="text-right">{{ $controlAccount['opening_balance_credit'] }}</td>
                                        <td class="text-right">{{ $controlAccount['transaction_debit'] }}</td>
                                        <td class="text-right">{{ $controlAccount['transaction_credit'] }}</td>
                                        <td class="text-right">{{ $controlAccount['balance_debit'] }}</td>
                                        <td class="text-right">{{ $controlAccount['balance_credit'] }}</td>
                                    </tr>
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
    </tbody>
    <tfoot>
    <tr style="background-color: lavender">
        <th class="text-center" colspan="4"><i>Total</i></th>
        <th class="text-right">{{ $totalOpeningBalanceDebit }}</th>
        <th class="text-right">{{ $totalOpeningBalanceCredit }}</th>
        <th class="text-right">{{ $totalTransactionDebit }}</th>
        <th class="text-right">{{ $totalTransactionCredit }}</th>
        <th class="text-right">{{ $totalBalanceDebit }}</th>
        <th class="text-right">{{ $totalBalanceCredit }}</th>
    </tr>
    </tfoot>
</table>
