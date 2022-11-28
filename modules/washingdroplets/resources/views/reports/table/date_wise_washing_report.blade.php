<table class="reportTable">
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Style/Order No.</th>
        <th>PO</th>
        <th>Color</th>
        <th>Total Sent</th>
        <th>Total Received</th>
        <th>Washing Rejection</th>
    </tr>
    </thead>
    <tbody class="date-wise-report">
    @if($washing_report)
        @foreach($washing_report->groupBy('purchase_order_id') as $groupByOrder)
            @foreach($groupByOrder->groupBy('color_id') as $groupByColor)
                @php
                    $buyer_name = $groupByOrder->first()['buyer_name'] ?? 'Buyer';
                    $order_style_no = $groupByOrder->first()['order_style_no'] ?? '';
                    $po_no = $groupByOrder->first()['po_no'] ?? '';
                    $color = $groupByColor->first()['color'] ?? '';

                    $total_sent = 0;
                    $total_received = 0;
                    $total_rejected = 0;
                @endphp
                @foreach($groupByColor as $details)
                    @php

                        $total_sent += $details['total_wash_sent'];
                        $total_received += $details['total_wash_received'];
                        $total_rejected += $details['total_wash_rejection'];

                    @endphp
                @endforeach
                <tr>
                    <td>{{ $buyer_name }}</td>
                    <td>{{ $order_style_no }}</td>
                    <td>{{ $po_no }}</td>
                    <td>{{ $color }}</td>
                    <td>{{ $total_sent }}</td>
                    <td>{{ $total_received }}</td>
                    <td>{{ $total_rejected }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <th colspan="4">Total</th>
            <th>{{$grand_total_sent}}</th>
            <th>{{$grand_total_received}}</th>
            <th>{{$grand_total_rejected}}</th>
        </tr>
    @else
        <tr>
            <th colspan="7" align="center">No Data</th>
        </tr>
    @endif
    </tbody>
</table>