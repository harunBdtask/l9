<div>
    <div>
        <table>
            <tr>
                <td colspan="2" class="text-left" style="background-color: lightblue"><b>{{factoryName()}}</b></td>
            </tr>
            <tr>
                <td colspan="2" class="text-left" style="background-color: lightblue"><b>{{factoryAddress()}}</b></td>
            </tr>
        </table>
    </div>
    <div>
        <table>
            <tr>
                <td colspan="2" style="background-color: lightblue"><h3>Receipt and Payment Statement</h3></td>
            </tr>
        </table>
    </div>
    <div>
        <div class="row">
            <div class="col-md-12">
                <table class="reportTable">
                    <thead>
                    <tr style="border:1px solid black; background-color: springgreen">
                        <td style="border:1px solid black; text-align: left;background-color: lightblue;"><b>Description</b></td>
                        <td style="border:1px solid black; text-align: right;background-color: lightblue;"><b>Amount</b></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="border:1px solid black; text-align: left;background-color: lightgray;"><b>Opening Balance</b></td>
                        <td style="border:1px solid black; text-align: right;background-color: lightgray;"><b>{{ number_format(collect($balances)->sum('opening_balance'), 2) }}</b></td>
                    </tr>
                    @foreach($balances as $balance)
                        <tr>
                            <td style="border:1px solid black; text-align: left">{{ $balance['name'] }}</td>
                            <td style="border:1px solid black; text-align: right">{{ number_format($balance['opening_balance'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="border:1px solid black; text-align: left;background-color: lightgray;"><b>Total Received</b></td>
                        <td style="border:1px solid black; text-align: right;background-color: lightgray;"><b>{{ number_format(collect($received)->flatten(1)->sum('amount'), 2) }}</b></td>
                    </tr>
                    @foreach($received as $receive)
                        <tr>
                            <td style="border:1px solid black; text-align: left">{{ $receive->first()['name'] }}</td>
                            <td style="border:1px solid black; text-align: right">{{ number_format($receive->sum('amount'), 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="border:1px solid black; text-align: left;background-color: lightgray;"><b>Total Payments</b></td>
                        <td style="border:1px solid black; text-align: right;background-color: lightgray;"><b>{{ number_format(collect($payments)->flatten(1)->sum('amount'), 2) }}</b></td>
                    </tr>
                    @foreach($payments as $payment)
                        <tr>
                            <td style="border:1px solid black; text-align: left">{{ $payment->first()['name'] }}</td>
                            <td style="border:1px solid black; text-align: right">{{ number_format($payment->sum('amount'), 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="border:1px solid black; text-align: left;background-color: lightgray;"><b>Closing Balance</b></td>
                        <td style="border:1px solid black; text-align: right;background-color: lightgray;"><b>{{ number_format(collect($balances)->sum('closing_balance'), 2) }}</b></td>
                    </tr>
                    @foreach($balances as $balance)
                        <tr>
                            <td style="border:1px solid black; text-align: left">{{ $balance['name'] }}</td>
                            <td style="border:1px solid black; text-align: right">{{ number_format($balance['closing_balance'], 2) }}</td>
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
                            <td style="border:1px solid black; text-align: left;color: red;background-color: lightgray;"><b>Difference</b></td>
                            <td style="border:1px solid black; text-align: right;color: red;background-color: lightgray;"><b>{{ number_format($difference, 2) }}</b></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
