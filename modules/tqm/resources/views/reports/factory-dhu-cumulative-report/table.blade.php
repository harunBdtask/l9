<table class="reportTable">
    <thead>
    <tr>
        <th colspan="7" style="text-align: center">Factory DHU Report Cumulative</th>
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
        $totalCheckedCumulative = 0;
        $totalQcPassCumulative = 0;
        $totalDefectsCumulative = 0;
        $totalRejectCumulative = 0;
        $totalDHUCumulative = 0;
    @endphp
    @for($i=1; $i<=10; $i++)
        @php
            $cumulativeDefects = 3*($i+4);
            $cumulativeQcPass = 3*($i+60);
            $cumulativeChecked = 3*500;
            $rejectCumulative = 3;
            $cumulativeDHU = 3*(($cumulativeDefects * 100) / $cumulativeChecked);

            $totalCheckedCumulative += $cumulativeChecked;
            $totalQcPassCumulative += $cumulativeQcPass;
            $totalDefectsCumulative += $cumulativeDefects;
            $totalRejectCumulative += $rejectCumulative;
            $totalDHUCumulative += $cumulativeDHU;
        @endphp
        <tr>
            <td style="text-align: left;">Kmart</td>
            <td style="text-align: left;">SS123</td>
            <td style="text-align: right;">{{ $cumulativeChecked }}</td>
            <td style="text-align: right;">{{ $cumulativeQcPass }}</td>
            <td style="text-align: right;">{{ $cumulativeDefects }}</td>
            <td style="text-align: right;">{{ $rejectCumulative }}</td>
            <td style="text-align: right;">{{ number_format($cumulativeDHU, 2) }}</td>
        </tr>
    @endfor
    <tr style="background-color: #dbdbdb; font-weight: bold">
        <td style="text-align: right;" colspan="2">Total</td>
        <td style="text-align: right;">{{ $totalCheckedCumulative }}</td>
        <td style="text-align: right;">{{ $totalQcPassCumulative }}</td>
        <td style="text-align: right;">{{ $totalDefectsCumulative }}</td>
        <td style="text-align: right;">{{ $totalRejectCumulative }}</td>
        <td style="text-align: right;">{{ number_format(($totalDHUCumulative / 10), 2) }}</td>
    </tr>
    </tbody>
</table>
