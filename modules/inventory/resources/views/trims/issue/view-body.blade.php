<style>
    .table-border th,
    .table-border td {
        padding: 3px;
        text-align: left;
        border: 1px solid black;
    }

    .table-border th.center,
    .table-border td.center {
        text-align: center;
    }

    .table-border th.right,
    .table-border td.right {
        text-align: right;
    }

    .table-border th.no-border-top,
    .table-border td.no-border-top {
        border-bottom-color: transparent !important;
    }

    .table-border th.no-borer-right,
    .table-border td.no-borer-right {
        border-right-color: transparent !important;
    }

    .bottom-info {
        min-width: 100px;
        display: inline-block;
    }

    #factoryName {
        display: none;
    }

    #logo {
        height: 60px;
        margin: 25px auto 0;
    }

    #factoryAddress {
        margin: 0;
        display: block;
    }
</style>
<div class="row p-x-1">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4" style="float: left; position:relative; margin-top:30px">
                <table class="borderless">
                    <tbody>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Issue Purpose :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right">
                            @if($trimsIssue->issue_purpose == 'sewing')
                                <span>Sewing</span>
                            @elseif($trimsIssue->issue_purpose == 'sales')
                                <span>Sales</span>
                            @elseif($trimsIssue->issue_purpose == 'sample_with_order')
                                <span>Sample With Order</span>
                            @elseif($trimsIssue->issue_purpose == 'sample_without_order')
                                <span>Sample Without Order</span>
                            @elseif($trimsIssue->issue_purpose == 'stolen')
                                <span>Stolen</span>
                            @elseif($trimsIssue->issue_purpose == 'adjustment')
                                <span>Adjustment</span>
                            @elseif($trimsIssue->issue_purpose == 'dyeing')
                                <span>Dyeing</span>
                            @elseif($trimsIssue->issue_purpose == 'cutting')
                                <span>Cutting</span>
                            @elseif($trimsIssue->issue_purpose == 'finishing')
                                <span>Finishing</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Issue Challan No :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsIssue->issue_challan_no }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Location :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsIssue->location }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Sewing Source :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right">
                            @if($trimsIssue->sewing_source == 'in_house')
                                <span>In-House</span>
                            @elseif($trimsIssue->sewing_source == 'out_bound')
                                <span>Out-Bound Sub-Con</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Sewing Floor :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsIssue->floor->floor_no }} </td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-md-4" style="float: right; position:relative;margin-top:30px">
                <table class="borderless">
                    <tbody>
                    <tr>
                        <td class="text-right">
                            <strong>Company Name :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsIssue->factory->factory_name }} </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Issue Basis :</strong>
                        </td>

                        <td style="padding-left: 30px;"
                            class="text-right">
                            @if($trimsIssue->issue_basis == 'with_order')
                                <span>With Order</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Store :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsIssue->store->name }} </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Sewing Comp :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right">
                            {{ $trimsIssue->sewing_composite }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Sewing Location :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsIssue->sewing_location }} </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="body-section" style="margin-top: 0;">
    <table class="reportTable" style="margin-top:12px;">
        <thead>
        <tr>
            <th>Style Name</th>
            <th>PO NO</th>
            <th>Item Group</th>
            <th>Gmts Size</th>
            <th>Item Color</th>
            <th>Order UOM</th>
            <th>WO/PI Qty</th>
            <th>Receive Qty</th>
            <th>Issue Qty</th>
            <th>Rate</th>
            <th>Stock Qty.</th>
            <th>Swing Line</th>
            <th>Floor</th>
            <th>Room</th>
            <th>Rack</th>
            <th>Shelf</th>
            <th>Bin</th>
        </tr>
        </thead>
        <tbody>
        @forelse($trimsIssue->details as $detail)
            <tr>
                <td>{{ $detail->style_name }}</td>
                <td>{{ collect($detail->po_no)->implode(', ') }}</td>
                <td>{{ $detail->item->item_group }}</td>
                <td>{{ $detail->trimsReceiveDetail->gmts_sizes }}</td>
                <td>{{ $detail->item_color }}</td>
                <td>{{ $detail->uom->unit_of_measurement }}</td>
                <td>{{ $detail->trimsReceiveDetail->wo_pi_qty }}</td>
                <td>{{ $detail->trimsReceiveDetail->receive_qty }}</td>
                <td>{{ $detail->issue_qty }}</td>
                <td>{{ $detail->rate }}</td>
                <td>{{ $detail->trimsReceiveDetail->receive_qty ? $detail->trimsReceiveDetail->receive_qty - $detail->issue_qty : '' }}</td>
                <td>{{ $detail->line->line_no }}</td>
                <td>{{ $detail->floor_name }}</td>
                <td>{{ $detail->room_name }}</td>
                <td>{{ $detail->rack_name }}</td>
                <td>{{ $detail->shelf_name }}</td>
                <td>{{ $detail->bin_name }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="17">No Data Available</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

