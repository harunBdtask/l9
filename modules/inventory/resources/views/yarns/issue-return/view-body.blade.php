
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
                    <span style="font-size: 12pt; font-weight: bold;">Yarn Issue Return</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>

    <br>
    <table class="borderless" style="text-align : left">
        <tr>
            <th style="width : 15%">Issue Return ID:</th>
            <td style="width : 15%">{{ $data->issue_return_no }}</td>
            <th>Issue ID No:</th>
            <td>{{ $data->issue_no }}</td>
            <th>Return Date: </th>
            <td style="width : 10%">{{ $data->return_date }}</td>
        </tr>
        <tr>
            <th>Return Source:</th>
            <td>{{ $data->return_source }}</td>
            <th>Knitting Company:</th>
            <td>{{ $data->knitting_company_id }}</td>
            <th>Return Challan No:</th>
            <td>{{ $data->return_challan }}</td>
        </tr>
        <tr>
            <th>Requisition No:</th>
            <td>{{ $data->requisition_no }}</td>
            <th>Location:</th>
            <td>{{ $data->location }}</td>
            <th>Store: </th>
            <td></td>
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
                    @forelse($data->details as $key => $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->yarn_lot }}</td>
                            <td>
                                {{ isset($value->composition) ? $value->composition->yarn_composition . ',' : '' }}
                                {{ isset($value->yarn_count) ? $value->yarn_count->yarn_count . ',' : '' }}
                                {{ isset($value->type) ? $value->type->yarn_type . ',' : '' }}
                            </td>
                            <td>{{ $value->return_qty }}</td>
                            <td>{{ $value->rate }}</td>
                            <td>{{ $value->return_value }}</td>
                            <td>{{ optional($value->floor)->name }}</td>
                            <td>{{ optional($value->room)->name }}</td>
                            <td>{{ optional($value->rack)->name }}</td>
                            <td>{{ optional($value->shelf)->name }}</td>
                            <td>{{ optional($value->bin)->name }}</td>
                            <td>{{ $value->remarks }}</td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="11">No data found!</td>
                        </tr>
                    @endforelse
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

