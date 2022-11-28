<table class="reportTable">
    <thead style="background-color: #ffe598;">
    <tr>
        <th rowspan="2">AC TYPE</th>
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
                $totalOpeningBalanceDebit += $reportData['opening_balance_debit'];
                $totalOpeningBalanceCredit += $reportData['opening_balance_credit'];
                $totalTransactionDebit += $reportData['transaction_debit'];
                $totalTransactionCredit += $reportData['transaction_credit'];
                $totalBalanceDebit += $reportData['balance_debit'];
                $totalBalanceCredit += $reportData['balance_credit'];
            @endphp
            <tr>
                <td><b>{{ $reportData['account_type'] }}</b></td>
                <td class="text-right">{{ $reportData['opening_balance_debit'] }}</td>
                <td class="text-right">{{ $reportData['opening_balance_credit'] }}</td>
                <td class="text-right">{{ $reportData['transaction_debit'] }}</td>
                <td class="text-right">{{ $reportData['transaction_credit'] }}</td>
                <td class="text-right">{{ $reportData['balance_debit'] }}</td>
                <td class="text-right">{{ $reportData['balance_credit'] }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
    <tfoot>
    <tr style="background-color: lavender">
        <th class="text-center"><i>Total</i></th>
        <th class="text-right">{{ $totalOpeningBalanceDebit }}</th>
        <th class="text-right">{{ $totalOpeningBalanceCredit }}</th>
        <th class="text-right">{{ $totalTransactionDebit }}</th>
        <th class="text-right">{{ $totalTransactionCredit }}</th>
        <th class="text-right">{{ $totalBalanceDebit }}</th>
        <th class="text-right">{{ $totalBalanceCredit }}</th>
    </tr>
    </tfoot>
</table>
