<table>
    <thead>
    <tr><td colspan="2">{{ sessionFactoryName() }}</td></tr>
    <tr>
        <th colspan="2">
            Buyer: {{ $buyer }} &nbsp;&nbsp;&nbsp;
            Booking No: {{ $booking_no }} &nbsp;&nbsp;&nbsp;
            Style: {{ $style }} &nbsp;&nbsp;&nbsp;
            PO: {{ $order_no }} &nbsp;&nbsp;&nbsp;
        </th>
    </tr>
    <tr>
        <th>Serial</th>
        <th>Challan No</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($inventory_challan_count))
        @foreach($inventory_challan_count as $report)
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