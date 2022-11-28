
<div class="">
    <div class="header-section factory-header" style="padding-bottom: 0px;">
        <table class="borderless">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                    {{ factoryAddress() }}
                </td>
            </tr>
            </thead>
        </table>
        <hr>
    </div>

    <center>
        <table style="border: 1px solid black; width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Yarn Return Return</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>

    <br>
    <table class="borderless" style="text-align : left">
        <tr>
            <th>Receive Return ID No:</th>
            <td>{{ $data->receive_return_no }}</td>
            <th>Receive ID No:</th>
            <td>{{ $data->yarn_receive->receive_no }}</td>
            <th>Return Date:</th>
            <td>{{ $data->return_date }}</td>
        </tr>
        <tr>
            <th>Return To:</th>
            <td>{{ $data->supplier->name ?? '' }}</td>
            <th>Remarks:</th>
            <td>{{ $data->remarks }}</td>
        </tr>
    </table>
    <br>
    <div class="body-section" style="margin-top: 0px;">
        <div>
            <table class="reportTable">
                <thead>
                <tr style="background-color: #eee">
                    <th>SL</th>
                    <th>Lot No</th>
                    <th>Item Details</th>
                    <th>Return Qty(kg)</th>
                    <th>Rate</th>
                    <th>Return Amount</th>
                    <th>Floor</th>
                    <th>Room</th>
                    <th>Rack</th>
                    <th>Shelf</th>
                    <th>Bin/Box</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data->details as $key => $value)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $value->yarn_lot }}</td>
                        <td>
                            {{ isset($value->yarn_count->yarn_count) ? $value->yarn_count->yarn_count . ',' : '' }}
                            {{ isset($value->composition->yarn_composition) ? $value->composition->yarn_composition . ',' : '' }}
                            {{ isset($value->type->yarn_type) ? $value->type->yarn_type . ',' : '' }}
                            {{ isset($value->umo->unit_of_measurement) ? $value->umo->unit_of_measuremen . ',' : '' }}
                            {{ $value->yarn_color }}
                        </td>
                        <td>{{ $value->return_qty }}</td>
                        <td>{{ $value->rate }}</td>
                        <td>{{ $value->return_value }}</td>
                        <td>{{ $value->floor->name }}</td>
                        <td>{{ $value->room->name }}</td>
                        <td>{{ $value->rack->name }}</td>
                        <td>{{ $value->shelf->name }}</td>
                        <td>{{ $value->bin->name }}</td>
                        <td>{{ $value->remarks }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-right"><span style="margin-right: 5px">Total</span></td>
                    <td>{{ $data->details->sum('return_qty') }}</td>
                    <td>{{ $data->details->sum('rate') }}</td>
                    <td>{{ $data->details->sum('return_value') }}</td>
                    <td colspan="6"></td>
                </tr>
                </tbody>
            </table>
            <strong>In word: </strong>
        </div>
    </div>
    <div style="margin-top: 16mm">
        <table class="borderless">
            <tbody>
            <tr>
                <td class="text-center"><u>Prepared By</u></td>
                <td class='text-center'><u>Checked By</u></td>
                <td class="text-center"><u>Approved By</u></td>
            </tr>
            </tbody>
        </table>
    </div>
    @include('skeleton::reports.downloads.footer')
</div>

