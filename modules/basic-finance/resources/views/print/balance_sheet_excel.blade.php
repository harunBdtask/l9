<div>

    <div class="row">
        <div class="col-md-12">
            <table class="reportTable">
                <thead class="thead-light">
                <tr>
                    <td style="border:1px solid black;" class="text-left"><b>HEAD OF ACCOUNT</b></td>
                    <td style="border:1px solid black;" class="text-right"><b>BALANCE</b></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="border:1px solid black;" colspan="2" class="text-left"><b>ASSET</b></td>
                </tr>
                @foreach($assets as $account)
                    <tr>
                        <td style="border:1px solid black;" class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                        <td style="border:1px solid black;" class="text-right">
                            {{ $account->balance >= 0 ? number_format($account->balance, 2) : '('.number_format(abs($account->balance), 2).')' }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="border:1px solid black;" class="text-left"><b>Total of Asset</b></td>
                    <td style="border:1px solid black;" class="text-right">
                        @php
                            $totalAsset = $assets->sum('balance');
                        @endphp
                        <strong>
                            {{ $totalAsset >= 0 ? number_format($totalAsset, 2) : '('.number_format(abs($totalAsset), 2).')' }}
                        </strong>
                    </td>
                </tr>

                <tr>
                    <td style="border:1px solid black;" class="text-left" colspan="2"><b>LIABILITY</b></td>
                </tr>
                @foreach($liabilities as $account)
                    <tr>
                        <td style="border:1px solid black;" class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                        <td style="border:1px solid black;" class="text-right">
                            {{ $account->balance >= 0 ? '('.number_format(abs($account->balance), 2).')' : number_format(abs($account->balance), 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="border:1px solid black;" class="text-left"><b>Total of Liabililty</b></td>
                    <td style="border:1px solid black;" class="text-right">
                        @php
                            $totalLiability = $liabilities->sum('balance');
                            $totalLiability = $totalLiability > 0 ? (-1*$totalLiability) : abs($totalLiability);
                        @endphp
                        <strong>
                            {{ $totalLiability >= 0 ? number_format($totalLiability, 2) : '('.number_format(abs($totalLiability), 2).')' }}
                        </strong>
                    </td>
                </tr>

                <tr>
                    <td style="border:1px solid black;" colspan="2" class="text-left"><b>EQUITY</b></td>
                </tr>
                @foreach($equities as $account)
                    <tr>
                        <td style="border:1px solid black;" class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                        <td style="border:1px solid black;" class="text-right">
                            {{ $account->balance >= 0 ? '('.number_format(abs($account->balance), 2).')' :  number_format(abs($account->balance), 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="border:1px solid black;" class="text-left"><b>Net Profit/Loss</b></td>
                    <td style="border:1px solid black;" class="text-right"><strong>{{ number_format($net_profit, 2) }}</strong></td>
                </tr>
                <tr>
                    <td style="border:1px solid black;" class="text-left"><b>Total of Equity</b></td>
                    <td style="border:1px solid black;" class="text-right">
                        @php
                            $totalEquity = $equities->sum('balance');
                            $totalEquity = $totalEquity > 0 ? (-1*$totalEquity) : abs($totalEquity);

                            $totalEquity += $net_profit;
                        @endphp
                        <strong>
                            {{ $totalEquity >= 0 ? number_format($totalEquity, 2) : '('.number_format(abs($totalEquity), 2).')' }}
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td style="border:1px solid black;" class="text-left"><b>Total of Liability Equity</b></td>
                    <td style="border:1px solid black;" class="text-right">
                        @php

                            $liabilityEquity = $totalLiability + $totalEquity;
                        @endphp
                        <strong>
                            {{ $liabilityEquity >= 0 ? number_format($liabilityEquity, 2) : '('.number_format(abs($liabilityEquity), 2).')' }}
                        </strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

