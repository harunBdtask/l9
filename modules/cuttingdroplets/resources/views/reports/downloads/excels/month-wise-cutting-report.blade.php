<table class="reportTable">
    <thead>
    <tr>
        <td colspan="6">{{ factoryName() }}</td>
    </tr>
    <tr>
        <th colspan="6">PO Wise Cutting Production Summary From: {{ $from_date  ?? '' }} - To: {{ $to_date ?? '' }}</th>
    </tr>
    <tr>
        <th>SL</th>
        <th>Buyer</th>
        <th>Order/Style</th>
        <th>PO</th>
        <th>PO Quantity</th>
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