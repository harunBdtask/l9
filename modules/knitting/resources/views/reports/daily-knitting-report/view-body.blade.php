<div class="body-section table-responsive" style="margin-top: 0;">
    <table class="reportTable">
        <thead>
        <tr style="background-color: aliceblue;">
            <th>Machine No</th>
            <th>Program No</th>
            <th>Booking Type</th>
            <th>Party Name</th>
            <th>Stitch Length</th>
            <th>Finish GSM</th>
            <th>Machine Dia</th>
            <th>Machine Gauge</th>
            <th>Program Qty</th>
            <th>Yarn Ref</th>
            <th>Program Start Date</th>
            <th>Program End Date</th>
            <th>Fabric Des</th>
            <th>Machine Feeder</th>
            <th>Finish Dia/Type</th>
            <th>Colour</th>
            <th>Program Colour QTY</th>
            <th>Yarn Lot</th>
            <th>Yarn Brand</th>
            <th>Yarn Description</th>
            <th>PI No</th>
            <th>Yarn Allocated Qty</th>
            <th>Yarn Req Qty</th>
            <th>No Of Bag</th>
            <th>Remarks</th>
        </tr>
        </thead>

        <tbody>
            @foreach($data as $program)
                @foreach($program['knitting_yarns'] as $key => $yarn)
                    <tr>
                        @if($key == 0)
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['machine_no'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['program_no'] }}</td>
                            <td style="text-transform: capitalize" rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['booking_type'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['party_name'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['stitch_length'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['fabric_gsm'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['machine_dia'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['machine_gg'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['program_qty'] }}</td>
                        @endif
                        <td>{{ $yarn['yarn_ref'] }}</td>
                        @if($key == 0)
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['start_date'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['end_date'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['fabric_description'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['machine_feeder'] }}</td>
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['finish_fabric_dia'] }}</td>
                        @endif
                        <td>{{ $yarn['color'] }}</td>
                        <td>{{ $yarn['program_color_qty'] }}</td>
                        <td>{{ $yarn['yarn_lot'] }}</td>
                        <td>{{ $yarn['yarn_brand'] }}</td>
                        <td>{{ $yarn['yarn_description'] }}</td>
                        <td>{{ $yarn['pi_no'] }}</td>
                        <td>{{ $yarn['allocated_qty'] }}</td>
                        <td>{{ $yarn['requisition_qty'] }}</td>
                        <td>{{ $yarn['no_of_bag'] }}</td>
                        @if($key == 0)
                            <td rowspan="{{ count($program['knitting_yarns']) }}">{{ $program['remarks'] }}</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
