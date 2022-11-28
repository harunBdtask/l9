<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
        <tr>
            <th colspan="7">{{ factoryName() }}</th>
        </tr>
        <tr>
            <th colspan="7"> {{ factoryAddress() }} </th>
        </tr>
    <tr>
        <th>Buyer</th>
        <th>Our Reference</th>
        <th>PO</th>
        <th>OQ</th>
        <th>Today Cutting</th>
        <th>Total Cutting</th>
        <th>Cutting Balance</th>
        {{--<th>Input Ready</th>--}}
    </tr>
    </thead>
    <tbody>
    @if(isset($cutting_report) && count($cutting_report) > 0)
        @php
            $g_t_order_qty = 0;
            $g_t_today_cutting_qty = 0;
            $g_t_total_cutting_qty = 0;
            $g_t_cutting_balance = 0;
            $g_t_input_ready = 0;
        @endphp
        @foreach($cutting_report as $report)
            <tr>
                <td colspan="7">
                    <span style="font-weight: bold;font-size: 14px;">{{$report['cutting_floor']}}</span>
                </td>
            </tr>
            @php
                $t_order_qty = 0;
                $t_today_cutting_qty = 0;
                $t_total_cutting_qty = 0;
                $t_cutting_balance = 0;
                $t_input_ready = 0;
            @endphp
            @foreach($report['cutting_details'] as $order)
                @php
                    $total_cutting_qty = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard::where(['purchase_order_id' => $order['purchase_order_id'], 'cutting_floor_id' => $order['cutting_floor_id'], 'status' => 1])
                        ->value(DB::raw("SUM(quantity - total_rejection)")) ?? 0;
                    $cutting_balance = $total_cutting_qty - $order['order_qty'] ?? 0;
                    $t_order_qty += $order['order_qty'] ?? 0;
                    $t_today_cutting_qty += $order['today_cutting_qty'] ?? 0;
                    $t_total_cutting_qty += $total_cutting_qty;
                    $t_cutting_balance += $total_cutting_qty - $order['order_qty'] ?? 0;
                    $t_input_ready += $order['input_ready'] ?? 0;

                    $g_t_order_qty += $order['order_qty'] ?? 0;
                    $g_t_today_cutting_qty += $order['today_cutting_qty'] ?? 0;
                    $g_t_total_cutting_qty += $total_cutting_qty;
                    $g_t_cutting_balance += $total_cutting_qty - $order['order_qty'] ?? 0;
                    $g_t_input_ready += $order['input_ready'] ?? 0;
                @endphp
                <tr>
                    <td>{{ $order['buyer_name'] }}</td>
                    <td>{{ $order['style_name'] }}</td>
                    <td>{{ $order['order_no'] }}</td>
                    <td>{{ $order['order_qty'] ?? 0 }}</td>
                    <td>{{ $order['today_cutting_qty'] ?? 0 }}</td>
                    <td>{{ $total_cutting_qty }}</td>
                    <td>{{ $cutting_balance }}</td>
                    {{--<td>{{ $order['input_ready'] ?? 0 }}</td>--}}
                </tr>
            @endforeach
            <tr style="font-weight:bold;">
                <td colspan="3">{{ $report['cutting_floor'] ?? '' }} = Total</td>
                <td>{{ $t_order_qty }}</td>
                <td>{{ $t_today_cutting_qty }}</td>
                <td>{{ $t_total_cutting_qty }}</td>
                <td>{{ $t_cutting_balance }}</td>
                {{--<td>{{ $t_input_ready }}</td>--}}
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="8" class="text-danger text-center">No Data Found</td>
        </tr>
    @endif
    </tbody>
    <tfoot>
    @if(isset($cutting_report) && count($cutting_report) > 0)
        <tr style="height:50px;font-size:16px; font-weight:bold;text-align: center;">
            <td colspan="3">Total</td>
            <td>&nbsp;</td>
            <td>{{ $g_t_today_cutting_qty }}</td>
            <td>{{ $g_t_total_cutting_qty }}</td>
            <td>{{ $g_t_cutting_balance }}</td>
            {{--<td>{{ $g_t_input_ready }}</td>--}}
        </tr>
    @endif
    </tfoot>
</table>