<table id="fixTable" class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
    <tr style="background: #f0f8fe;">
        <th>Buyer Name</th>
        <th>Order Qty</th>
        <th>Today Cutting</th>
        <th>Total Cutting</th>
        <th>Today Print Send</th>
        <th>Print Send</th>
        <th>Print Send Balance</th>
        <th>Today Print Receive</th>
        <th>Print Receive</th>
        <th>Print Receive Balance</th>
        <th>Today Embr. Send</th>
        <th>Emb. Send</th>
        <th>Emb. Send Balance</th>
        <th>Today Emb. Receive</th>
        <th>Emb. Receive</th>
        <th>Emb. Receive Balance</th>
        <th>Today Input</th>
        <th>Total Input</th>
        <th>Total Input Balance</th>
        <th>Left Cutting</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($cutting_report) && $cutting_report->count())
        @foreach($cutting_report as $key => $value)
            <tr style="text-align: center;">
                <td>{{ $value['buyer_name'] }}</td>
                <td>{{ $value['order_qty'] }}</td>
                <td>{{ $value['todays_cutting'] }}</td>
                <td>{{ $value['total_cutting'] }}</td>
                <td>{{ $value['todays_print_send'] }}</td>
                <td>{{ $value['total_print_send'] }}</td>
                <td>{{ $value['print_send_balance'] }}</td>
                <td>{{ $value['todays_print_receive'] }}</td>
                <td>{{ $value['total_print_receive'] }}</td>
                <td>{{ $value['print_receive_balance'] }}</td>
                <td>{{ $value['todays_embroidary_sent'] }}</td>
                <td>{{ $value['total_embroidary_sent'] }}</td>
                <td>{{ $value['embroidary_sent_balance'] }}</td>
                <td>{{ $value['todays_embroidary_received'] }}</td>
                <td>{{ $value['total_embroidary_received'] }}</td>
                <td>{{ $value['embroidary_receive_balance'] }}</td>
                <td>{{ $value['todays_input'] }}</td>
                <td>{{ $value['total_input'] }}</td>
                <td>{{ $value['total_input_balance'] }}</td>
                <td>{{ $value['left_cutting'] }}</td>
                <td>{{ $value['remarks'] }}</td>
            </tr>
        @endforeach
    @else
        <tr class="tr-height">
            <td colspan="21" class="text-danger text-center">No Data Found</td>
        </tr>
    @endif
    </tbody>
    <tfoot>
    @if(isset($cutting_report) && count($cutting_report) > 0)
        <tr style="font-weight:bold; text-align: center;">
            <td>Total</td>
            <td>{{ collect($cutting_report)->sum('order_qty') }}</td>
            <td></td>
            <td>{{ collect($cutting_report)->sum('total_cutting') }}</td>
            <td></td>
            <td>{{ collect($cutting_report)->sum('total_print_send') }}</td>
            <td>{{ collect($cutting_report)->sum('print_send_balance') }}</td>
            <td></td>
            <td>{{ collect($cutting_report)->sum('total_print_receive') }}</td>
            <td>{{ collect($cutting_report)->sum('print_receive_balance') }}</td>
            <td></td>
            <td>{{ collect($cutting_report)->sum('total_embroidary_sent') }}</td>
            <td>{{ collect($cutting_report)->sum('embroidary_sent_balance') }}</td>
            <td></td>
            <td>{{ collect($cutting_report)->sum('total_embroidary_received') }}</td>
            <td>{{ collect($cutting_report)->sum('embroidary_receive_balance') }}</td>
            <td></td>
            <td>{{ collect($cutting_report)->sum('total_input') }}</td>
            <td>{{ collect($cutting_report)->sum('total_input_balance') }}</td>
            <td>{{ collect($cutting_report)->sum('left_cutting') }}</td>
            <td></td>
        </tr>
    @endif
    </tfoot>
</table>
