<table class="reportTable">
    <thead>
    <tr style="background-color: springgreen">
        <th style="text-align: center">Project</th>
        <th style="text-align: center">Cash Books</th>
        <th style="text-align: center">Opening Balance</th>
        <th style="text-align: center">Total Debit</th>
        <th style="text-align: center">Total Credit</th>
        <th style="text-align: center">Closing Balance</th>
    </tr>
    </thead>
    <tbody>
    @php
        $grandTotalOpeningBalance = 0;
        $grandTotalDebitBalance = 0;
        $grandTotalCreditBalance = 0;
        $grandTotalClosingBalance = 0;
    @endphp
    @foreach($reportData as $projects)
        @php
            $totalAccounts = count($projects);
            $index = 0;
        @endphp
        @foreach($projects as $journal)
            @if($index === 0)
                <tr>
                    <td style="text-align: left" rowspan="{{ $totalAccounts }}">{{ $journal['project_name'] }}</td>
                    <td style="text-align: left">{{ $journal['account_name'] }}</td>
                    <td style="text-align: right">{{ number_format($journal['opening_balance'], 2) }}</td>
                    <td style="text-align: right">{{ number_format($journal['total_debit_amount'], 2) }}</td>
                    <td style="text-align: right">{{ number_format($journal['total_credit_amount'], 2) }}</td>
                    <td style="text-align: right">{{ number_format($journal['closing_balance'], 2) }}</td>
                </tr>
            @else
                <tr>
                    <td style="text-align: left">{{ $journal['account_name'] }}</td>
                    <td style="text-align: right">{{ number_format($journal['opening_balance'], 2) }}</td>
                    <td style="text-align: right">{{ number_format($journal['total_debit_amount'], 2) }}</td>
                    <td style="text-align: right">{{ number_format($journal['total_credit_amount'], 2) }}</td>
                    <td style="text-align: right">{{ number_format($journal['closing_balance'], 2) }}</td>
                </tr>
            @endif
            @php
                $index++;
                $grandTotalOpeningBalance += $journal['opening_balance'];
                $grandTotalDebitBalance += $journal['total_debit_amount'];
                $grandTotalCreditBalance += $journal['total_credit_amount'];
                $grandTotalClosingBalance += $journal['closing_balance'];
            @endphp
        @endforeach
        <tr>
            <td style="text-align: right" colspan="2"><strong>SUB TOTAL</strong></td>
            <td style="text-align: right">
                <strong>{{ number_format($projects->sum('opening_balance'), 2) }}</strong>
            </td>
            <td style="text-align: right">
                <strong>{{ number_format($projects->sum('total_debit_amount'), 2) }}</strong>
            </td>
            <td style="text-align: right">
                <strong>{{ number_format($projects->sum('total_credit_amount'), 2) }}</strong>
            </td>
            <td style="text-align: right">
                <strong>{{ number_format($projects->sum('closing_balance'), 2) }}</strong>
            </td>
        </tr>
    @endforeach
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
    @foreach($accountSubTotal as $subTotal)
        <tr>
            <td style="text-align: right" colspan="2"><strong>{{ $subTotal['name'] }}</strong></td>
            <td style="text-align: right"><strong>{{ number_format($subTotal['opening_balance'], 2) }}</strong></td>
            <td style="text-align: right"><strong>{{ number_format($subTotal['total_debit_balance'], 2) }}</strong></td>
            <td style="text-align: right"><strong>{{ number_format($subTotal['total_credit_balance'], 2) }}</strong>
            </td>
            <td style="text-align: right"><strong>{{ number_format($subTotal['closing_balance'], 2) }}</strong></td>
        </tr>
    @endforeach
    <tr>
        <td style="text-align: right" colspan="2"><strong>GRAND TOTAL</strong></td>
        <td style="text-align: right"><strong>{{ number_format($grandTotalOpeningBalance, 2) }}</strong></td>
        <td style="text-align: right"><strong>{{ number_format($grandTotalDebitBalance, 2) }}</strong></td>
        <td style="text-align: right"><strong>{{ number_format($grandTotalCreditBalance, 2) }}</strong></td>
        <td style="text-align: right"><strong>{{ number_format($grandTotalClosingBalance, 2) }}</strong></td>
    </tr>
    </tbody>
</table>
