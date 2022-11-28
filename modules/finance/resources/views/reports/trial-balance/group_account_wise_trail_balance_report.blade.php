<table class="reportTable">
    <thead style="background-color: #ffe598;">
    <tr>
        <th rowspan="2">AC TYPE</th>
        <th rowspan="2">PARENT ACCOUNT</th>
        <th rowspan="2">GROUP ACCOUNT</th>
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
                        @foreach ($groupAccounts as $groupAccount)
                            @php
                                $totalOpeningBalanceDebit += $groupAccount['opening_balance_debit'];
                                $totalOpeningBalanceCredit += $groupAccount['opening_balance_credit'];
                                $totalTransactionDebit += $groupAccount['transaction_debit'];
                                $totalTransactionCredit += $groupAccount['transaction_credit'];
                                $totalBalanceDebit += $groupAccount['balance_debit'];
                                $totalBalanceCredit += $groupAccount['balance_credit'];
                            @endphp
                            <tr>
                                @if ($index === 0)
                                    <td rowspan="{{ collect($parentAccounts)->collapse()->count() }}">
                                        <b>{{ $groupAccount['account_type'] }}</b>
                                    </td>
                                @endif
                                @if ($parentIndex === 0)
                                    <td rowspan="{{ count($groupAccounts) }}">
                                        <b>{{ $groupAccount['parent_account_name'] }}</b>
                                    </td>
                                @endif
                                <td><b>{{ $groupAccount['account_name'] }}</b></td>
                                <td class="text-right">{{ $groupAccount['opening_balance_debit'] }}</td>
                                <td class="text-right">{{ $groupAccount['opening_balance_credit'] }}</td>
                                <td class="text-right">{{ $groupAccount['transaction_debit'] }}</td>
                                <td class="text-right">{{ $groupAccount['transaction_credit'] }}</td>
                                <td class="text-right">{{ $groupAccount['balance_debit'] }}</td>
                                <td class="text-right">{{ $groupAccount['balance_credit'] }}</td>
                            </tr>
                            @php($parentIndex++)
                            @php($index++)
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
    </tbody>
    <tfoot>
    <tr style="background-color: lavender">
        <th class="text-center" colspan="3"><i>Total</i></th>
        <th class="text-right">{{ $totalOpeningBalanceDebit }}</th>
        <th class="text-right">{{ $totalOpeningBalanceCredit }}</th>
        <th class="text-right">{{ $totalTransactionDebit }}</th>
        <th class="text-right">{{ $totalTransactionCredit }}</th>
        <th class="text-right">{{ $totalBalanceDebit }}</th>
        <th class="text-right">{{ $totalBalanceCredit }}</th>
    </tr>
    </tfoot>
</table>