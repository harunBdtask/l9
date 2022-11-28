<table>
    <thead>
    <tr>
        <th>SL</th>
        <th>Buyer</th>
        <th>Our Reference</th>
        <th>PO</th>
        <th>Order Qty</th>
        <th>Item Desc.</th>
        <th>Today's Cutting </th>
        <th>Total Cutting</th>
        <th>Left Qty</th>
        <th>Total Rejection</th>
    </tr>
    </thead>
    <tbody>
    @if($order_qty->count() > 0)
        @php
            $tcutting_qty = 0;
            $ttodays_cutting = 0;
            $tleft_qt = 0;
            $total_total_rejection = 0;
        @endphp
        @foreach($order_qty as $bundle)
            @php
                $tcutting_qty += $bundle['cutting_qty'];
                $ttodays_cutting += $bundle['todays_cutting'];
                $tleft_qt += $bundle['left_qt'];
                $total_total_rejection += $bundle['total_rejection'];
            @endphp
            <tr style="text-align: center;">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $bundle['buyer'] }}</td>
                <td>{{ $bundle['style'] }}</td>
                <td>{{ $bundle['order'] }}</td>
                <td>{{ $bundle['order_qty'] }}</td>
                <td>{{ '' }}</td>
                <td>{{ $bundle['todays_cutting'] }}</td>
                <td>{{ $bundle['cutting_qty'] }}</td>
                <td>{{ $bundle['left_qt'] }}</td>
                <td>{{ $bundle['total_rejection'] }}</td>
            </tr>
        @endforeach
        <tr style="text-align: center;font-weight: bold">
            <td colspan="5" style="text-align: center;">Total</td>
            <td>{{ $ttodays_cutting }}</td>
            <td>{{ $tcutting_qty }}</td>
            <td>{{ $tleft_qt }}</td>
            <td>{{ '' }}</td>
            <td>{{ $total_total_rejection }}</td>
        </tr>
    @else
        <tr>
            <td colspan="9" style="text-align: center; font-weight: bold;">Not found<td>
        </tr>
    @endif
    </tbody>
</table>