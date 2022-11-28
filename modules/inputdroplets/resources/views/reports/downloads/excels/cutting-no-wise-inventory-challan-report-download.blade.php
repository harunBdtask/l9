<table>
    <thead>
    <tr><td colspan="5">{{ sessionFactoryName() }}</td></tr>
    <tr>
        <th colspan="2">
            Buyer: {{ $buyer }} &nbsp;&nbsp;&nbsp;
            Booking No: {{ $booking_no }} &nbsp;&nbsp;&nbsp;
            Style: {{ $style }} &nbsp;&nbsp;&nbsp;
            PO: {{ $order_no }} &nbsp;&nbsp;&nbsp;
            Color: {{ $color }} &nbsp;&nbsp;&nbsp;
            Cutting No: {{ $cutting_no }} &nbsp;&nbsp;&nbsp;
        </th>
    </tr>
    <tr>
        <th>Serial</th>
        <th>Challan No</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($cutting_no_wise_data))
        @foreach($cutting_no_wise_data as $report)
            <tr style="text-align: center;">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $report }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center;">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>