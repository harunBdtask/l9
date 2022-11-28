
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
        <table style="border: 1px solid black; width: 30%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Yarn Issue Note/Challan</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>

    <br>
    <table class="borderless top-info">
        <tr>
            <th>Issue No:</th>
            <td>{{ $data->issue_no }}</td>
            <th>Booking/Req. No:</th>
            <td></td>
            <th>Knitting Source:</th>
            <td>{{ $data->knitting_source_name }}</td>
        </tr>
        <tr>
            <th>Challan/Program No:</th>
            <td>{{ $data->challan_no }}</td>
            <th>Issue Purpose:</th>
            <td>{{ $data->issue_purpose_name }}</td>
            <th>Issue Date:</th>
            <td>{{ date("d-m-Y", strtotime($data->issue_date)) }}</td>
        </tr>
        <tr>
            <th>Issue To:</th>
            <td>
                @if($data->issue_purpose == 1)
                    {{ optional($data->issueToFactory)->factory_name }}
                @endif
                @if($data->issue_purpose == 2)
                    {{ optional($data->issueToSupplier)->name }}
                @endif
            </td>
            <th>Gate Pass No:</th>
            <td></td>
            <th>Store:</th>
            <td></td>
        </tr>
        <tr>
            <th>Demand No:</th>
            <td></td>
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
                    <th>Req. No</th>
                    <th>Lot No</th>
                    <th>Item Details</th>
                    <th>Dye. Color</th>
                    <th>Supplier</th>
                    <th>Issue Qty(kg)</th>
                    <th>Returnable Qty(kg)</th>
                    <th>No.Of Cone per Bag</th>
                    <th>Buyer & Uniq Id</th>
                    <th>Name Style</th>
                    <th>Floor/Room/Rack</th>
                    <th>Shelf/Bin/Box</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($data->details as $key => $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->demand_no }}</td>
                            <td>{{ $value->yarn_lot }}</td>
                            <td>
                                {{ isset($value->composition->yarn_composition) ? $value->composition->yarn_composition . ',' : '' }}
                                {{ isset($value->composition->yarn_count) ? $value->composition->yarn_count . ',' : '' }}
                                {{ $value->composition->type ?? '' }}
                            </td>
                            <td>{{ $value->dyeing_color }}</td>
                            <td>{{ optional($data->supplier)->name }}</td>
                            <td>{{ $value->issue_qty }}</td>
                            <td>{{ $value->returnable_qty }}</td>
                            <td>{{ $value->no_of_cone_per_bag }}</td>
                            <td></td>
                            <td></td>
                            <td>
                                {{ isset($value->floor->name) ? $value->floor->name . ',' : '' }}
                                {{ isset($value->room->name) ? $value->room->name . ',' : '' }}
                                {{ $value->composition->rack ?? '' }}
                            </td>
                            <td>
                                {{ isset($value->shelf->name) ? $value->shelf->name . ',' : '' }}
                                {{ $value->bin->name ?? '' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center">No data found!</td>
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

