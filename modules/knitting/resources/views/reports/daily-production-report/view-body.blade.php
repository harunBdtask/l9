<div class="body-section table-responsive" style="margin-top: 0;">
    <table class="reportTable">
        <thead>
        <tr style="background-color: aliceblue;">
            <th>SL</th>
            <th>BUYER</th>
            <th>Booking Type</th>
            <th>ORDER NO</th>
            <th>Program No</th>
            <th>Card No</th>
            <th>MC No</th>
            <th>MC/DIA</th>
            <th>MC/GG</th>
            <th>F/TYPE</th>
            <th>COLOUR</th>
            <th>F/GSM</th>
            <th>ORDER QTY</th>
            <th>TODAY KNIT QTY</th>
            <th>TOTAL KNIT QTY</th>
            <th>BALANCE</th>
            <th>ACTUAL KNIT START DATE</th>
            <th>ACTUAL KNIT CLOSE DATE</th>
        </tr>
        </thead>

        <tbody>
            @foreach($data as $value)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value['buyer_name'] }}</td>
                    <td style="text-transform: capitalize">{{ $value['booking_type'] }}</td>
                    <td>{{ $value['order_no'] }}</td>
                    <td>{{ $value['program_no'] }}</td>
                    <td>{{ $value['knit_card_no'] }}</td>
                    <td>{{ $value['machine_no'] }}</td>
                    <td>{{ $value['machine_dia'] }}</td>
                    <td>{{ $value['machine_gg'] }}</td>
                    <td>{{ $value['fabric_type'] }}</td>
                    <td>{{ $value['color'] }}</td>
                    <td>{{ $value['gsm'] }}</td>
                    <td>{{ $value['order_qty'] }}</td>
                    <td>{{ $value['today_knit_qty'] }}</td>
                    <td>{{ $value['total_knit_qty'] }}</td>
                    <td>{{ $value['balance'] }}</td>
                    <td>{{ $value['actual_knit_start_date'] }}</td>
                    <td>{{ $value['actual_knit_close_date'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
