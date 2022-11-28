<table>
    <thead>
    <tr>
        <td colspan="7">{{ factoryName() }}</td>
    </tr>
    <tr>
        <th colspan="7">
            Buyer: {{$buyer}} &nbsp; &nbsp;
            Order/Style: {{$style}} &nbsp; &nbsp;
            PO: {{$order_no}}
        </th>
    </tr>
    <tr>
        <th>Color Name</th>
        <th>Size Name</th>
        <th>PO Quantity</th>
        <th>Today's Cutting</th>
        <th>Total Cutting</th>
        <th>Left Quantity</th>
        <th>Extra Cuttting (%)</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($result_data['report_size_wise'])
        @foreach($result_data['report_size_wise'] as $order)
            <tr>
                <td>{{ $order['color'] }}</td>
                <td>{{ $order['size'] }}</td>
                <td>{{ $order['size_order_qty'] }}</td>
                <td>{{ $order['today_cutting'] }}</td>
                <td>{{ $order['total_cutting'] }}</td>
                <td>{{ $order['left_qty'] }}</td>
                <td>{{ $order['extra_cutting_ratio'] }} </td>
            </tr>
            });
        @endforeach
        <tr>
            <td colspan="2"><b>Total</b></td>
            <td>{{ $result_data['total_report']['total_order_cutting'] }}</td>
            <td>{{ $result_data['total_report']['total_today_cutting'] }}</td>
            <td>{{ $result_data['total_report']['total_total_cutting'] }}</td>
            <td>{{ $result_data['total_report']['total_left_qty'] }}</td>
            <td></td>
        </tr>;
    @else
        <tr>
            <td colspan="7" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>
