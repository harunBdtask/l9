<div class="row">
    <div class="col-md-12">
        <table class="reportTable">
            <thead>
            <tr style="background-color: springgreen">
                <th style="text-align: left">Description</th>
                <th style="text-align: right">Amount</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th style="text-align: left">Opening Balance</th>
                <th style="text-align: right">{{ number_format(collect($balances)->sum('opening_balance'), 2) }}</th>
            </tr>
            @foreach($balances as $balance)
                <tr>
                    <td style="text-align: left">{{ $balance['name'] }}</td>
                    <td style="text-align: right">{{ number_format($balance['opening_balance'], 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th style="text-align: left">Total Received</th>
                <th style="text-align: right">{{ number_format(collect($received)->flatten(1)->sum('amount'), 2) }}</th>
            </tr>
            @foreach($received as $receive)
                <tr>
                    <td style="text-align: left">{{ $receive->first()['name'] }}</td>
                    <td style="text-align: right">{{ number_format($receive->sum('amount'), 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th style="text-align: left">Total Payments</th>
                <th style="text-align: right">{{ number_format(collect($payments)->flatten(1)->sum('amount'), 2) }}</th>
            </tr>
            @foreach($payments as $payment)
                <tr>
                    <td style="text-align: left">{{ $payment->first()['name'] }}</td>
                    <td style="text-align: right">{{ number_format($payment->sum('amount'), 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th style="text-align: left">Closing Balance</th>
                <th style="text-align: right">{{ number_format(collect($balances)->sum('closing_balance'), 2) }}</th>
            </tr>
            @foreach($balances as $balance)
                <tr>
                    <td style="text-align: left">{{ $balance['name'] }}</td>
                    <td style="text-align: right">{{ number_format($balance['closing_balance'], 2) }}</td>
                </tr>
            @endforeach
            @php
                $totalOpening = collect($balances)->sum('opening_balance');
                $totalReceived = collect($received)->flatten(1)->sum('amount');
                $totalPayments = collect($payments)->flatten(1)->sum('amount');
                $totalClosing = collect($balances)->sum('closing_balance');
                $difference = ($totalOpening + $totalReceived - $totalPayments) - $totalClosing;
            @endphp
            @if ($difference != 0)
                <tr>
                    <th style="text-align: left;color: red">Difference</th>
                    <td style="text-align: right;color: red"><b>{{ number_format($difference, 2) }}</b></td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
