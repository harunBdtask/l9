<table class="reportTable">
    <thead>
    <tr style="background-color: aliceblue;">
        <th>Program No</th>
        <th>Booking Type</th>
        <th>Program Qty</th>
        <th>PI Number</th>
        <th>Program Start Date</th>
        <th>Program End Date</th>
        <th>Fabric Type</th>
        <th>Stitch Length</th>
        <th>Finish GSM</th>
        <th>Machine Dia</th>
        <th>Machine Gauge</th>
        <th>Machine Feeder</th>
        <th>Finish Dia/Type</th>
        <th>Colour</th>
        <th>Program Colour QTY</th>
        <th>Yarn Description</th>
        <th>Yarn Lot</th>
        <th>Yarn Allocated QTY</th>
        <th>REQ QTY</th>
        <th>Remarks</th>
    </tr>
    </thead>

    <tbody>
       @foreach ($plannings as $planning)
        <tr>
            <td>{{ $planning['program_no'] }}</td>
            <td style="text-transform: capitalize">{{ $planning['booking_type'] }}</td>
            <td>{{ $planning['program_qty'] }}</td>
            <td>{{ $planning['pi_number'] }}</td>
            <td>{{ $planning['start_date'] }}</td>
            <td>{{ $planning['end_date'] }}</td>
            <td>{{ $planning['fabric_type'] }}</td>
            <td>{{ $planning['stitch_length'] }}</td>
            <td>{{ $planning['fabric_gsm'] }}</td>
            <td>{{ $planning['machine_dia'] }}</td>
            <td>{{ $planning['machine_gg'] }}</td>
            <td>{{ $planning['machine_feeder'] }}</td>
            <td>{{ $planning['finish_dia'] }}</td>
            <td>{{ $planning['color'] }}</td>
            <td>{{ $planning['program_color_qty'] }}</td>
            <td>{{ $planning['yarn_description'] }}</td>
            <td>{{ $planning['yarn_lot'] }}</td>
            <td>{{ $planning['yarn_allocated_qty'] }}</td>
            <td>{{ $planning['req_qty'] }}</td>
            <td>{{ $planning['remarks'] }}</td>
        </tr>
        @endforeach

    </tbody>
</table>
