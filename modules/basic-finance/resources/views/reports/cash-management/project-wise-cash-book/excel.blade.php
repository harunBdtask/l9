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
                    <td style="text-align: right">{{ $journal['opening_balance'] }}</td>
                    <td style="text-align: right">{{ $journal['total_debit_amount'] }}</td>
                    <td style="text-align: right">{{ $journal['total_credit_amount'] }}</td>
                    <td style="text-align: right">{{ $journal['closing_balance'] }}</td>
                </tr>
            @else
                <tr>
                    <td style="text-align: left">{{ $journal['account_name'] }}</td>
                    <td style="text-align: right">{{ $journal['opening_balance'] }}</td>
                    <td style="text-align: right">{{ $journal['total_debit_amount'] }}</td>
                    <td style="text-align: right">{{ $journal['total_credit_amount'] }}</td>
                    <td style="text-align: right">{{ $journal['closing_balance'] }}</td>
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
            <td style="text-align: right"><strong>{{ $projects->sum('opening_balance') }}</strong></td>
            <td style="text-align: right"><strong>{{ $projects->sum('total_debit_amount') }}</strong></td>
            <td style="text-align: right"><strong>{{ $projects->sum('total_credit_amount') }}</strong></td>
            <td style="text-align: right"><strong>{{ $projects->sum('closing_balance') }}</strong></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
    @foreach($accountSubTotal as $subTotal)
        <tr>
            <td style="text-align: right" colspan="2"><strong>{{ $subTotal['name'] }}</strong></td>
            <td style="text-align: right"><strong>{{ $subTotal['opening_balance'] }}</strong></td>
            <td style="text-align: right"><strong>{{ $subTotal['total_debit_balance'] }}</strong></td>
            <td style="text-align: right"><strong>{{ $subTotal['total_credit_balance'] }}</strong></td>
            <td style="text-align: right"><strong>{{ $subTotal['closing_balance'] }}</strong></td>
        </tr>
    @endforeach
    <tr>
        <td style="text-align: right" colspan="2"><strong>GRAND TOTAL</strong></td>
        <td style="text-align: right"><strong>{{ $grandTotalOpeningBalance }}</strong></td>
        <td style="text-align: right"><strong>{{ $grandTotalDebitBalance }}</strong></td>
        <td style="text-align: right"><strong>{{ $grandTotalCreditBalance }}</strong></td>
        <td style="text-align: right"><strong>{{ $grandTotalClosingBalance }}</strong></td>
    </tr>
    </tbody>
</table>
