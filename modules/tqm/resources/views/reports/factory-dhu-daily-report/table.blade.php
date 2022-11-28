<table class="reportTable">
    <thead>
    <tr>
        <th colspan="7" style="text-align: center">Factory DHU Report Daily</th>
    </tr>
    <tr style="background-color: #dbdbdb">
        <th style="text-align: left;">Buyer</th>
        <th style="text-align: left;">Style</th>
        <th style="text-align: right;">Checked</th>
        <th style="text-align: right;">QC Pass</th>
        <th style="text-align: right;">Defects</th>
        <th style="text-align: right;">Reject</th>
        <th style="text-align: right;">DHU Level</th>
    </tr>
    </thead>

    <tbody>
    @php
        $totalChecked = 0;
        $totalQcPass = 0;
        $totalDefects = 0;
        $totalReject = 0;
        $totalDHU = 0;

        $totalCheckedCumulative = 0;
        $totalQcPassCumulative = 0;
        $totalDefectsCumulative = 0;
        $totalRejectCumulative = 0;
        $totalDHUCumulative = 0;
    @endphp
    @for($i=1; $i<=10; $i++)
        @php
            $dailyDefects = 3*($i+2);
            $dailyQcPass = 3*($i+70);
            $dailyChecked = 3*100;
            $reject = 3;
            $dailyDHU = 3*(($dailyDefects * 100) / $dailyChecked);

            $totalChecked += $dailyChecked;
            $totalQcPass += $dailyQcPass;
            $totalDefects += $dailyDefects;
            $totalReject += $reject;
            $totalDHU += $dailyDHU;

        @endphp
        <tr>
            <td style="text-align: left;">Kmart</td>
            <td style="text-align: left;">SS123</td>
            <td style="text-align: right;">{{ $dailyChecked }}</td>
            <td style="text-align: right;">{{ $dailyQcPass }}</td>
            <td style="text-align: right;">{{ $dailyDefects }}</td>
            <td style="text-align: right;">{{ $reject }}</td>
            <td style="text-align: right;">{{ number_format($dailyDHU, 2) }}</td>
        </tr>
    @endfor
    <tr style="background-color: #dbdbdb; font-weight: bold">
        <td style="text-align: right;" colspan="2">Total</td>
        <td style="text-align: right;">{{ $totalChecked }}</td>
        <td style="text-align: right;">{{ $totalQcPass }}</td>
        <td style="text-align: right;">{{ $totalDefects }}</td>
        <td style="text-align: right;">{{ $totalReject }}</td>
        <td style="text-align: right;">{{ number_format(($totalDHU / 10), 2) }}</td>
    </tr>
    </tbody>
</table>
