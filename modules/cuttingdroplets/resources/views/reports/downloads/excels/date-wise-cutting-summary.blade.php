{{--Order Wise Cutting Production Summary--}}
<table>
    <thead>
    <tr>
        <td colspan="6">{{ factoryName() }}</td>
    </tr>
    </thead>
</table>
<table class="reportTable">
    <thead>
    <tr>
        <th colspan="6">Order Wise Cutting Production Summary</th>
    </tr>
    <tr>
        <th>SL</th>
        <th>Buyer</th>
        <th>Order/Style</th>
        <th>PO</th>
        <th>Order Quantity</th>
        <th>Cutting Production</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if(!empty($order_wise_cutting_summary_report))
        @php
            $torder_quantity = 0;
            $tcutting_quantity = 0;
        @endphp
        @foreach($order_wise_cutting_summary_report->groupBy('order_id') as $reportByOrder)
            @php
                $cutting_production = 0;
            @endphp
            @foreach($reportByOrder as $report)
                @php
                    $torder_quantity += $report['order_qty'];
                    $tcutting_quantity += $report['cutting_production'];
                    $buyer_name = $report['buyer_name'];
                    $style_name = $report['style_name'];
                    $order_no = $report['order_no'];
                    $order_qty = $report['order_qty'];
                    $cutting_production += $report['cutting_production'];
                @endphp
            @endforeach
            <tr>
                <td>{{ $loop->iteration }}
                <td>{{ $buyer_name }}</td>
                <td>{{ $style_name }}</td>
                <td>{{ $order_no }}</td>
                <td>{{ $order_qty }}</td>
                <td>{{ $cutting_production }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="4">Total</td>
            <td>{{ $torder_quantity }}</td>
            <td>{{ $tcutting_quantity }}</td>
        </tr>
    @else
        <tr>
            <td colspan="5" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>

<!-- color wise -->
<table class="reportTable">
    <thead>
    <tr>
        <th colspan="6">Color Wise Cutting Production Summary</th>
    </tr>
    <tr>
        <th>SL</th>
        <th>Buyer</th>
        <th>Order/Style</th>
        <th>PO</th>
        <th>Color</th>
        <th>Cutting Production</th>
    </tr>
    </thead>
    <tbody class="">
    @if(!empty($color_wise_cutting_summary_report))
        @php
            $tcutting_quantity_color = 0;
            $color_sl = 1;
        @endphp
        @foreach($color_wise_cutting_summary_report->groupBy('order_id') as $reportByOrder)
            @foreach($reportByOrder->groupBy('color_id') as $key => $reportByColor)
                @php
                    $cutting_color_wise_production = 0;
                @endphp
                @foreach($reportByColor as $color)
                    @php
                        $cutting_color_wise_production += $color['cutting_production'] ?? 0;
                        $tcutting_quantity_color += $color['cutting_production'] ?? 0;
                        $buyer_name = $color['buyer_name'];
                        $style_name = $color['style_name'];
                        $order_no = $color['order_no'];
                        $color = $color['color'];
                    @endphp
                @endforeach
                <tr>
                    <td>{{ $color_sl++ }}</td>
                    <td>{{ $buyer_name }}</td>
                    <td>{{ $style_name }}</td>
                    <td>{{ $order_no }}</td>
                    <td>{{ $color }}</td>
                    <td>{{ $cutting_color_wise_production }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="5">Total</td>
            <td>{{ $tcutting_quantity_color }}</td>
        </tr>
    @else
        <tr>
            <td colspan="6" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>

<!--table wise target summary-->
<table class="reportTable" aria-describedby="example2_info">
    <thead>
    <tr>
        <th colspan="5"><b>Cutting Target Wise Cutting Production Summary</b></th>
    </tr>
    </thead>
    <thead>
    <tr>
        <th>Floor</th>
        <th>Table</th>
        <th>Target/Day</th>
        <th>Cutting Production</th>
        <th>Achievement</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($cutting_target_wise_summary_report))
        @php
            $ttoday_target = 0;
            $total_cutting = 0;
        @endphp
        @foreach($cutting_target_wise_summary_report as $tble)
            @php
                $ttoday_target += $tble['cutting_target_per_day'];
                $total_cutting += $tble['cutting_production'];
            @endphp
            <tr>
                <td>{{ $tble['cutting_floor'] }}</td>
                <td>{{ $tble['cutting_table'] }}</td>
                <td>{{ $tble['cutting_target_per_day'] }}</td>
                <td>{{ $tble['cutting_production'] }}</td>
                <td>{{ round($tble['cutting_percentage'],2) }} %</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="2">Total</td>
            <td>{{ $ttoday_target }}</td>
            <td>{{ $total_cutting }}</td>
            <td></td>
        </tr>
    @else
        <tr>
            <td colspan="5" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>