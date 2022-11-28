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
        <table style="border: 1px solid black;width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Yarn Transfer</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>

    <br>
    <table class="borderless top-info">
        <tr>
            <th style="text-align: left;">Transfer ID No:</th>
            <td>{{ $data->transfer_no }}</td>
            <th style="text-align: left;">Transfer Criteria:</th>
            <td>{{ \SkylarkSoft\GoRMG\Inventory\Models\YarnTransfer::TRANSFER_CRITERIA[$data->transfer_criteria] }}</td>
            <th style="text-align: left;">To Company:</th>
            <td>{{ optional($data->factory)->factory_name }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Transfer Date:</th>
            <td>{{ $data->transfer_date }}</td>
            <th style="text-align: left;">From Store:</th>
            <td>{{ $data->fromStore->name }}</td>
            <th style="text-align: left;">To Store:</th>
            <td>{{ $data->toStore->name }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Challan No:</th>
            <td>{{ $data->challan_no }}</td>
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
                    <th>Transfer Qty(kg)</th>
                    <th>Rate</th>
                    <th>Transfer Amount</th>
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
                        <td>{{ $value->transfer_qty }}</td>
                        <td>{{ $value->rate }}</td>
                        <td>{{ $value->transfer_value }}</td>
                        <td>{{ optional($value->floor)->name }}</td>
                        <td>{{ optional($value->room)->name }}</td>
                        <td>{{ optional($value->rack)->name }}</td>
                        <td>{{ optional($value->shelf)->name }}</td>
                        <td>{{ optional($value->bin)->name }}</td>
                        <td>{{ $value->remarks }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">No data found!</td>
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

