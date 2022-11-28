@if($total_production_report)
    @php
        $grand_total_order_qty = 0;
        $grand_todays_cutting = 0;
        $grand_total_cutting = 0;
        $grand_left_qty = 0;
    @endphp
    @foreach($total_production_report as $key => $report)
        @php
            $style_name = $report->order->style_name ?? 'Style';
            $po = $report->purchaseOrder->po_no ?? '';
            $order_total_qty = $report->purchaseOrder->po_quantity ?? 0;
            
            $todays_cutting = $report->todays_cutting - $report->todays_cutting_rejection;
            $total_cutting = $report->total_cutting - $report->total_cutting_rejection;
            
            $left_quantity = $order_total_qty - $total_cutting ?? 0;
            $extra_cut_percent = 0;
            if (($total_cutting - $order_total_qty) > 0 && $total_cutting > 0 && $order_total_qty > 0) {
                $extra_cut_percent = (($total_cutting - $order_total_qty) * 100) / $order_total_qty;
            }

            $grand_total_order_qty += $order_total_qty;
            $grand_todays_cutting += $todays_cutting;
            $grand_total_cutting += $total_cutting;
            $grand_left_qty += $left_quantity;
        @endphp
        <tr>        
            <td>{{ $style_name }}</td>
            <td>{{ $po }}</td>
            <td>{{ $order_total_qty }}</td>
            <td>{{ $todays_cutting }}</td>
            <td>{{ $total_cutting }}</td>
            <td>{{ $left_quantity }}</td>
            <td>{{ round($extra_cut_percent,2) }} &#37;</td>
        </tr>
    @endforeach
    <tr>
        <th colspan="2"> Total</th>
        <th>{{ $grand_total_order_qty }}</th>
        <th>{{ $grand_todays_cutting }}</th>
        <th>{{ $grand_total_cutting }}</th>
        <th>{{ $grand_left_qty }}</th>
        <th></th>
    </tr>
    @if($total_production_report && $total_production_report->total() > 18 && $print == 0)
        <tr>
            <td colspan="8" align="center">{{ $total_production_report->appends(request()->except('page'))->links() }}</td>
        </tr>
    @endif
@else
    <tr>
        <td colspan="8" align="center">No data</td>
    </tr>
@endif