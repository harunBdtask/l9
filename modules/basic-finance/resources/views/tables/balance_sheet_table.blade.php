<div class="row">
    <div class="col-md-12">
        <table class="reportTable">
            <thead class="thead-light">
            <tr>
                <th class="text-left">HEAD OF ACCOUNT</th>
                <th class="text-right">BALANCE</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th colspan="2" class="text-left">ASSET</th>
            </tr>
            @foreach($assets as $account)
                <tr>
                    <td class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                    <td class="text-right">
                        {{ $account->balance >= 0 ? number_format($account->balance, 2) : '('.number_format(abs($account->balance), 2).')' }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th class="text-left">Total of Asset</th>
                <td class="text-right">
                    @php
                        $totalAsset = $assets->sum('balance');
                    @endphp
                    <strong>
                        {{ $totalAsset >= 0 ? number_format($totalAsset, 2) : '('.number_format(abs($totalAsset), 2).')' }}
                    </strong>
                </td>
            </tr>

            <tr>
                <th class="text-left" colspan="2">LIABILITY</th>
            </tr>
            @foreach($liabilities as $account)
                <tr>
                    <td class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                    <td class="text-right">
                        {{ $account->balance >= 0 ? '('.number_format(abs($account->balance), 2).')' : number_format(abs($account->balance), 2) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th class="text-left">Total of Liabililty</th>
                <td class="text-right">
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
                <th colspan="2" class="text-left">EQUITY</th>
            </tr>
            @foreach($equities as $account)
                <tr>
                    <td class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                    <td class="text-right">
                        {{ $account->balance >= 0 ? '('.number_format(abs($account->balance), 2).')' :  number_format(abs($account->balance), 2) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th class="text-left">Net Profit/Loss</th>
                <td class="text-right"><strong>{{ number_format($net_profit, 2) }}</strong></td>
            </tr>
            <tr>
                <th class="text-left">Total of Equity</th>
                <td class="text-right">
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
                <th class="text-left">Total of Liability Equity</th>
                <td class="text-right">
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
