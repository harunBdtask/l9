<thead>
@if(request()->segment(4) === 'excel' )
    <tr>
        <th colspan="13">{{ sessionFactoryName() }}</th>
    </tr>
    <tr>
        <th colspan="13">{{ sessionFactoryAddress() }}</th>
    </tr>

    @endif
<tr>
    <th align="center" colspan="6">
        Buyer: {{ $buyer ?? '' }} &nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;
    </th>
    <th align="center" colspan="7">
        Style/Order No: {{ $order_style_no ?? '' }}
    </th>

</tr>
<tr>
    <th>Color</th>
    <th>Order Qty</th>
    <th>Cutting Qty</th>
    <th>Cutting(%)</th>
    <th>Sewing Qty</th>
    <th>Sewing Balance</th>
    <th>Wash Sent</th>
    <th>Wash Received</th>
    <th>Wash Balance</th>
    <th>Poly Qty</th>
    <th>Poly Balance</th>
    <th>Shipment Date</th>
    <th>Remarks</th>
</tr>
</thead>
<tbody>
@if(isset($finishing_production_report))
    @php
        $g_total_color_wise_order_qty = 0;
        $g_total_cutting_qty = 0;
        $g_total_sewing_qty = 0;
        $g_total_sewing_balance = 0;
        $g_total_wash_sent = 0;
        $g_total_wash_received = 0;
        $g_total_wash_balance = 0;
        $g_total_poly_qty = 0;
        $g_total_poly_balance = 0;
    @endphp
    @foreach($finishing_production_report->sortByDesc('purchaseOrder.ex_factory_date')->groupBy('color_id') as $groupByColor)
        @php
            $total_color_wise_order_qty = 0;
            $total_cutting_qty = 0;
            $total_sewing_qty = 0;
            $total_sewing_balance = 0;
            $total_wash_sent = 0;
            $total_wash_received = 0;
            $total_wash_balance = 0;
            $total_poly_qty = 0;
            $total_poly_balance = 0;
        @endphp
        @foreach($groupByColor as $report)
            @php
                $color_wise_order_qty = 0;
                if(isset($report->purchaseOrder->po_details)){
                    foreach($report->purchaseOrder->po_details as $detail){
                        if($detail->color_id == $report->color_id){
                            $color_wise_order_qty += $detail->quantity;
                        }
                    }
                }
                // Order Qty
                $total_color_wise_order_qty += $color_wise_order_qty;
                // Cutting Qty
                $cutting_qty = $report->total_cutting - $report->total_cutting_rejection ?? 0;
                $total_cutting_qty += $cutting_qty;
                // Cutting(%)
                $cutting_percent = (isset($total_color_wise_order_qty) && $total_color_wise_order_qty > 0) ? $total_cutting_qty * 100 / $total_color_wise_order_qty : 0;
                // Sewing Qty
                $sewing_qty = $report->total_sewing_output ?? 0;
                $total_sewing_qty += $sewing_qty;
                // Sewing Balance
                $sewing_balance = $report->total_cutting - $report->total_cutting_rejection - $report->total_sewing_output ?? 0;
                $total_sewing_balance += $sewing_balance;
                // Wash Sent
                $washing_sent = $report->total_washing_sent ?? 0;
                $total_wash_sent += $washing_sent;
                // Wash Received
                $washing_received = $report->total_washing_received ?? 0;
                $total_wash_received += $washing_received;
                // Wash Balance
                $washing_balance = $report->total_washing_sent - $report->total_washing_received ?? 0;
                $total_wash_balance += $washing_balance;
                // Poly Qty
                $poly_qty = $report->total_poly ?? 0;
                $total_poly_qty += $poly_qty;
                // Poly Balance
                $poly_balance = $report->total_cutting - $report->total_cutting_rejection  - $report->total_poly ?? 0;
                $total_poly_balance += $poly_balance;
            @endphp
            @if($loop->last)
                <tr>
                    @php
                        $g_total_color_wise_order_qty += $total_color_wise_order_qty;
                        $g_total_cutting_qty += $total_cutting_qty;
                        $g_total_sewing_qty += $total_sewing_qty;
                        $g_total_sewing_balance += $total_sewing_balance;
                        $g_total_wash_sent += $total_wash_sent;
                        $g_total_wash_received += $total_wash_received;
                        $g_total_wash_balance += $total_wash_balance;
                        $g_total_poly_qty += $total_poly_qty;
                        $g_total_poly_balance += $total_poly_balance;
                    @endphp
                    <td>{{$report->colors->name}}</td>
                    <td>{{$total_color_wise_order_qty}}</td>
                    <td>{{$total_cutting_qty}}</td>
                    <td>{{ number_format($cutting_percent, 2) }}</td>
                    <td>{{$total_sewing_qty}}</td>
                    <td>{{$total_sewing_balance}}</td>
                    <td>{{$total_wash_sent}}</td>
                    <td>{{$total_wash_received}}</td>
                    <td>{{$total_wash_balance}}</td>
                    <td>{{$total_poly_qty}}</td>
                    <td>{{$total_poly_balance}}</td>
                    <td>{{date('d M,Y',strtotime($report->purchaseOrder->ex_factory_date)) ?? '-'}}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
    @endforeach
@elseif(isset($finishing_production_report) && empty($finishing_production_report))
    <tr>
        <td colspan="13" align="center">No Data</td>
    </tr>
@endif
</tbody>
<tfoot>
@if(isset($finishing_production_report))
    <tr align="center">
        <td>Total</td>
        <td>{{$g_total_color_wise_order_qty}}</td>
        <td>{{$g_total_cutting_qty}}</td>
        <td></td>
        <td>{{$g_total_sewing_qty}}</td>
        <td>{{$g_total_sewing_balance}}</td>
        <td>{{$g_total_wash_sent}}</td>
        <td>{{$g_total_wash_received}}</td>
        <td>{{$g_total_wash_balance}}</td>
        <td>{{$g_total_poly_qty}}</td>
        <td>{{$g_total_poly_balance}}</td>
        <td colspan="2"></td>
    </tr>
@endif
</tfoot>