<table>
    <thead>
    <tr>
        <th colspan="7">{{ sessionFactoryName() }}</th>
    </tr>
    <tr>
        <th colspan="7">Daily Fabric Consumption Report {{ date('d-m-Y', strtotime($cutting_date)) }}</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style</th>
        <th>Color</th>
        <th>SID</th>
        <th>Cutting No</th>
        <th>Fabric Save/Loss</th>
        <th>Cutting Date</th>
    </tr>
    </thead>
    <tbody>
    @if($reports && $reports->count())
        @foreach($reports as $reportBundle)
            @php
                $report = $reportBundle->details;
                $colors = $report->allColors ?? '';
                $cuttingNo = $report->cutting_no;

                if ($report->colors) {
                    $cuttingNosWithColor = explode('; ', $cuttingNo);

                    $cuttingNo = '';
                    foreach ($cuttingNosWithColor as $cuttingNoWithColor) {
                        $cutting = explode(': ', $cuttingNoWithColor);
                        $cuttingNo .= \SkylarkSoft\GoRMG\SystemSettings\Models\Color::findOrFail($cutting[0])->name . ': ' . $cutting[1] . '; ';
                    }
                    $cuttingNo = rtrim($cuttingNo, '; ');
                }

            @endphp
            <tr>
                <td>{{ $report->buyer->name }}</td>
                <td>{{ $report->order->style_name }}</td>
                <td>{{ $colors }}</td>
                <td>{{ $report->sid }}</td>
                <td>{{ $cuttingNo }}</td>
                <td>{{ number_format($report->fabric_save, 2) }} KGs</td>
                <td>{{ $reportBundle->cutting_date ? date('d/m/Y', strtotime($reportBundle->cutting_date)) : '' }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="5">Total</th>
            <th>{{ number_format($reports->sum('details.fabric_save'), 2) }} KGs</th>
            <th>&nbsp;</th>
        </tr>
    @else
        <tr>
            <th colspan="7" align="center">No Data</th>
        </tr>
    @endif
    </tbody>
</table>