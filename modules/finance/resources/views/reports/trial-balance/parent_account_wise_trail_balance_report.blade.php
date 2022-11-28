<table class="reportTable">
    <thead style="background-color: #ffe598;">
    <tr>
        <th rowspan="2">AC TYPE</th>
        <th rowspan="2">PARENT ACCOUNT</th>
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
    @if(isset($data))
        @php
            $totalOpeningBalanceDebit = 0;
            $totalOpeningBalanceCredit = 0;
            $totalTransactionDebit = 0;
            $totalTransactionCredit = 0;
            $totalBalanceDebit = 0;
            $totalBalanceCredit = 0;
        @endphp
        @foreach($data as $reportData)
            @php
                $parentAccounts = collect($reportData);
                $index = 0;
            @endphp
            @if (count($parentAccounts))
                @foreach($parentAccounts as $parentAccount)
                    @php
                        $totalOpeningBalanceDebit += $parentAccount['opening_balance_debit'];
                        $totalOpeningBalanceCredit += $parentAccount['opening_balance_credit'];
                        $totalTransactionDebit += $parentAccount['transaction_debit'];
                        $totalTransactionCredit += $parentAccount['transaction_credit'];
                        $totalBalanceDebit += $parentAccount['balance_debit'];
                        $totalBalanceCredit += $parentAccount['balance_credit'];
                    @endphp
                    @if($index === 0)
                        <tr>
                            <td rowspan="{{ count($parentAccounts) }}"><b>{{ $parentAccount['account_type'] }}</b></td>
                            <td><b>{{ $parentAccount['account_name'] }}</b></td>
                            <td class="text-right">{{ $parentAccount['opening_balance_debit'] }}</td>
                            <td class="text-right">{{ $parentAccount['opening_balance_credit'] }}</td>
                            <td class="text-right">{{ $parentAccount['transaction_debit'] }}</td>
                            <td class="text-right">{{ $parentAccount['transaction_credit'] }}</td>
                            <td class="text-right">{{ $parentAccount['balance_debit'] }}</td>
                            <td class="text-right">{{ $parentAccount['balance_credit'] }}</td>
                        </tr>
                    @else
                        <tr>
                            <td><b>{{ $parentAccount['account_name'] }}</b></td>
                            <td class="text-right">{{ $parentAccount['opening_balance_debit'] }}</td>
                            <td class="text-right">{{ $parentAccount['opening_balance_credit'] }}</td>
                            <td class="text-right">{{ $parentAccount['transaction_debit'] }}</td>
                            <td class="text-right">{{ $parentAccount['transaction_credit'] }}</td>
                            <td class="text-right">{{ $parentAccount['balance_debit'] }}</td>
                            <td class="text-right">{{ $parentAccount['balance_credit'] }}</td>
                        </tr>
                    @endif
                    @php($index++)
                @endforeach
            @endif
        @endforeach
    @endif
    </tbody>
    <tfoot>
    <tr style="background-color: lavender">
        <th class="text-center" colspan="2"><i>Total</i></th>
        <th class="text-right">{{ $totalOpeningBalanceDebit }}</th>
        <th class="text-right">{{ $totalOpeningBalanceCredit }}</th>
        <th class="text-right">{{ $totalTransactionDebit }}</th>
        <th class="text-right">{{ $totalTransactionCredit }}</th>
        <th class="text-right">{{ $totalBalanceDebit }}</th>
        <th class="text-right">{{ $totalBalanceCredit }}</th>
    </tr>
    </tfoot>
</table>
