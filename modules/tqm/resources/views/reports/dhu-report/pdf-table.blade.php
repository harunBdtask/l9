@include('tqm::reports.dhu-report.common-table')

<table style="border: none; margin-top: 20px;">
    <tr>
        <td style="width: 50%; border: none; text-align: center;">
            <span style="font-weight: 500; text-decoration: underline;">Buyer Wise DHU</span>
            <table class="reportTable">
                <thead>
                <tr>
                    <th style="text-align: left;">Buyer</th>
                    <th style="text-align: right;">Ch.Qty</th>
                    <th style="text-align: right;">QC Qty</th>
                    <th style="text-align: right;">Defects</th>
                    <th style="text-align: right;">Reject</th>
                    <th style="text-align: right;">DHU</th>
                </tr>
                </thead>

                <tbody>
                @php
                    $totalChecked = 0;
                    $totalQcPass = 0;
                    $totalDefects = 0;
                    $totalReject = 0;
                @endphp
                @foreach($data['buyerWiseDhu'] as $buyer => $value)
                    @php
                        $checked = $value->sum('checked');
                        $totalChecked += $checked;
                        $qc_pass = $value->sum('qc_pass');
                        $totalQcPass += $qc_pass;
                        $defects = $value->sum('defects');
                        $totalDefects += $defects;
                        $reject = $value->sum('reject');
                        $totalReject += $reject;
                    @endphp
                    <tr>
                        <td style="text-align: left;">{{ $buyer }}</td>
                        <td style="text-align: right;">{{ $checked }}</td>
                        <td style="text-align: right;">{{ $qc_pass }}</td>
                        <td style="text-align: right;">{{ $defects }}</td>
                        <td style="text-align: right;">{{ $reject }}</td>
                        <td style="text-align: right;">
                            {{ $checked ? number_format((($defects*100)/$checked), 2) : 0.00 }}
                        </td>
                    </tr>
                @endforeach

                <tr style="background-color: #dbdbdb; font-weight: bold">
                    <td style="text-align: right;">Total</td>
                    <td style="text-align: right;">{{ $totalChecked }}</td>
                    <td style="text-align: right;">{{ $totalQcPass }}</td>
                    <td style="text-align: right;">{{ $totalDefects }}</td>
                    <td style="text-align: right;">{{ $totalReject }}</td>
                    <td style="text-align: right;">{{ $totalChecked ? number_format((($totalDefects*100)/$totalChecked), 2) : 0.00 }}</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td style="width: 10%; border: none;"></td>
        <td style="width: 40%; border: none; text-align: center;">
            <span style="font-weight: 500; text-decoration: underline;">Highest 3 Defects</span>
            <table class="reportTable">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Defect Name</th>
                    <th>No of Defects</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data['highestDefects'] as $defect)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $defect['defect_name'] }}</td>
                        <td>{{ $defect['total_defects'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
</table>
