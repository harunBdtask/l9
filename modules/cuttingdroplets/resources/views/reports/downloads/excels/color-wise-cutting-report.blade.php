{{--
<table>
    <thead>
    <tr style="text-align: center;">
        <th>Table No.</th>
        <th>Buyer</th>
        <th>Our Reference</th>
        <th>PO</th>
        <th>Color</th>
        <th>Cutting No.</th>
        <th>Bundle Quantity</th>
        <th>Cutting Production</th>
        <th>Date of Cutting</th>
    </tr>
    </thead>
    <tbody>
    @php
    $total_bundle_quantity = 0;
    $total_cutting_quantity = 0;
    @endphp
    @if(!empty($result_report))
        @foreach($result_report as $report)
            @php
                $total_bundle_quantity += $report['bundle_quantity'];
                $total_cutting_quantity += $report['cutting_quantity'];
            @endphp
        <tr style="text-align: center;">
            <td>{{ $report['cutting_table_no'] }}</td>
            <td>{{ $report['buyer'] }}</td>
            <td>{{ $report['style'] }}</td>
            <td>{{ $report['order'] }}</td>
            <td>{{ $report['color'] }}</td>
            <td>{{$report['cutting_no']}}</td>
            <td>{{$report['bundle_quantity']}}</td>
            <td>{{ $report['cutting_quantity']}}</td>
            <td>{{$report['cutting_date']}}</td>
        </tr>
        @endforeach
        <tr style="text-align: center;font-weight: bold">
            <td colspan="6"><b>Total</b></td>
            <td>{{ $total_bundle_quantity }}</td>
            <td>{{ $total_cutting_quantity }}</td>
            <td></td>
        </tr>
    @else
        <tr>
            <td style="text-align: center;"><strong>No Data</strong></td>
        </tr>
    @endif
    </tbody>
</table>
--}}
<table>
    @include('cuttingdroplets::reports.includes.color-wise-cutting-report-table-download')
</table>