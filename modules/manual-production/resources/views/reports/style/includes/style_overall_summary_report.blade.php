<thead>
<tr style="background-color: #d7f6d3">
    <th colspan="14" align="center">STYLE OVERALL REPORT</th>
</tr>
<tr style="background-color: #d7f6d3" align="center">
    <th>Style</th>
    <th>Color</th>
    <th>O/Qty</th>
    <th>O/Qty + 5%</th>
    <th>Total Cutting</th>
    <th>Cutting Balance</th>
    <th>Cut(%)</th>
    <th>Print Send</th>
    <th>Print Receive</th>
    <th>Embr. Send</th>
    <th>Embr. Receive</th>
    <th>Input Qty
    <th>Output Qty</th>
    <th>Input(%)</th>
</tr>
</thead>
<tbody>
@if($reports && count($reports))
    @php
        $total_order_qty = 0;
        $total_order_qty_plus_five_percent = 0;
        $total_cutting = 0;
        $total_cutting_balance = 0;
        $total_print_send = 0;
        $total_print_receive = 0;
        $total_embr_send = 0;
        $total_embr_receive = 0;
        $total_input_qty = 0;
        $total_output_qty = 0;
    @endphp
    @foreach($reports as $report)
        @php
            $total_order_qty += $report['order_qty'];
            $total_order_qty_plus_five_percent += $report['order_qty_plus_five_percent'];
            $total_cutting += $report['total_cutting'];
            $total_cutting_balance += $report['cutting_balance'];
            $total_print_send += $report['print_send'];
            $total_print_receive += $report['print_receive'];
            $total_embr_send += $report['emb_send'];
            $total_embr_receive += $report['emb_receive'];
            $total_input_qty += $report['input_qty'];
            $total_output_qty += $report['output_qty'];
        @endphp
        <tr>
            @if($loop->first)
                <td rowspan="{{ count($reports) }}"> {{ $order }}</td>
            @endif
            <td>{{ Arr::get($report,'color',null) }}</td>
            <td>{{ (int)Arr::get($report,'order_qty',0) }}</td>
            <td>{{ Arr::get($report,'order_qty_plus_five_percent',0) }}</td>
            <td>{{ (int)Arr::get($report,'total_cutting',0) }}</td>
            <td>{{ (int)Arr::get($report,'cutting_balance',0) }}</td>
            <td>{{ Arr::get($report,'cutting_percent',0) }} %</td>
            <td>{{ (int)Arr::get($report,'print_send',0) }}</td>
            <td>{{ (int)Arr::get($report,'print_receive',0) }}</td>
            <td>{{ (int)Arr::get($report,'emb_send',0)}}</td>
            <td>{{ (int)Arr::get($report,'emb_receive',0) }}</td>
            <td>{{ (int)Arr::get($report,'input_qty',0) }}</td>
            <td>{{ (int)Arr::get($report,'output_qty',0) }}</td>
            <td>{{ Arr::get($report,'input_percent',0) }} %</td>
        </tr>
    @endforeach
    @php
        $total_cut_percent = $total_order_qty_plus_five_percent > 0 ? number_format(($total_cutting / $total_order_qty_plus_five_percent) * 100, 2) : 0;
        $total_input_percent = $total_cutting > 0 ? number_format(($total_input_qty / $total_cutting) * 100, 2) : 0;
    @endphp
    <tr style="background-color: #fcffc6">
        <th colspan="2">Total</th>
        <th>{{ (int)$total_order_qty }}</th>
        <th>{{ $total_order_qty_plus_five_percent }}</th>
        <th>{{ (int)$total_cutting }}</th>
        <th>{{ (int)$total_cutting_balance }}</th>
        <th>{{ $total_cut_percent }} %</th>
        <th>{{ (int)$total_print_send }}</th>
        <th>{{ (int)$total_print_receive }}</th>
        <th>{{ (int)$total_embr_send }}</th>
        <th>{{ (int)$total_embr_receive }}</th>
        <th>{{ (int)$total_input_qty }}</th>
        <th>{{ (int)$total_output_qty }}</th>
        <th>{{ $total_input_percent }} %</th>
    </tr>
@else
    <tr>
        <th colspan="14">No Data</th>
    </tr>
@endif
</tbody>
