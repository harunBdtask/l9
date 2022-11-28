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
<center>
    <table style="border: 1px solid black;width: 20%;">
        <thead>
        <tr>
            <td class="text-center">
                <span style="font-size: 12pt; font-weight: bold;">Trims Receive</span>
                <br>
            </td>
        </tr>
        </thead>
    </table>
</center>
<div class="row p-x-1">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4" style="float: left; position:relative; margin-top:30px">
                <table class="borderless">
                    <tbody>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Supplier Name :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsReceive->supplier->name }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Challan No :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsReceive->challan_no }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Challan Date :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsReceive->challan_date }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>LC No :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsReceive->lc_no }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Currency :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsReceive->currency->currency_name }} </td>
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
                            class="text-right"> {{ $trimsReceive->factory->factory_name }} </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Receive Basis :</strong>
                        </td>

                        <td style="padding-left: 30px;"
                            class="text-right">
                            @if ($trimsReceive->receive_basic == 'pi_basis')
                                <span>PI Basis</span>
                            @elseif($trimsReceive->receive_basic == 'work_order')
                                <span>Work Order Booking Base</span>
                            @else
                                <span>Independent</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Store :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsReceive->store->name }} </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Pay Mode :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right">
                            @if($trimsReceive->pay_mode == 'based_on_booking')
                                <span>Based On Booking</span>
                            @elseif($trimsReceive->pay_mode == 'credit')
                                <span>Credit</span>
                            @elseif($trimsReceive->pay_mode == 'import')
                                <span>Import</span>
                            @elseif($trimsReceive->pay_mode == 'in_house')
                                <span>In House</span>
                            @else
                                <span>Within Group</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Receive Date :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $trimsReceive->receive_date }} </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<br>
<div class="body-section" style="margin-top: 0px;">
    <table class="reportTable" style="margin-top:12px;">
        <thead>
        <tr>
            <th>Buyer Name</th>
            <th>Style Name</th>
            <th>PO NO</th>
            <th>Item Group</th>
            <th>Item Description</th>
            <th>Gmts Size</th>
            <th>Item Size</th>
            <th>Order UOM</th>
            <th>WO/PI Qty</th>
            <th>Receive Qty</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Reject QTY</th>
            <th>Ship Date</th>
            <th>Floor</th>
            <th>Room</th>
            <th>Rack</th>
            <th>Shelf</th>
            <th>Bin</th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalWoPiQTY = 0;
            $totalReceiveQTY = 0;
            $totalRate = 0;
            $totalAmount = 0;
            $totalRejectQTY = 0;
        @endphp
                @forelse($trimsReceive->details as $detail)
                        <tr>
                            <td>{{ $detail->trimsReceive->buyer->name  ?? '' }}</td>
                            <td>{{ $detail->style_name }}</td>
                            <td>{{ collect($detail->po_no)->join(', ') }}</td>
                            <td>{{ $detail->trimsItem->item_group }}</td>
                            <td>{{ $detail->item_description }}</td>
                            <td>{{ $detail->gmts_sizes }}</td>
                            <td>{{ $detail->item_size }}</td>
                            <td>{{ $detail->uom->unit_of_measurement }}</td>
                            <td>{{ $detail->wo_pi_qty }}</td>
                            <td>{{ $detail->receive_qty }}</td>
                            <td>{{ $detail->rate }}</td>
                            <td>{{ $detail->amount }}</td>
                            <td>{{ $detail->reject_qty }}</td>
                            <td>{{ $detail->ship_date }}</td>
                            <td>{{ $detail->floorDetail->name }}</td>
                            <td>{{ $detail->roomDetail->name }}</td>
                            <td>{{ $detail->rackDetail->name }}</td>
                            <td>{{ $detail->shelfDetail->name }}</td>
                            <td>{{ $detail->binDetail->name }}</td>
                        </tr>
                    @php
                        $totalWoPiQTY += $detail->wo_pi_qty;
                        $totalReceiveQTY += $detail->receive_qty;
                        $totalRate += $detail->rate;
                        $totalAmount += $detail->amount;
                        $totalRejectQTY += $detail->reject_qty;
                    @endphp
                @empty
                    <tr>
                        <td colspan="19">No Data Available</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="8"> <b>Total</b> </td>
                    <td>{{ $totalWoPiQTY }}</td>
                    <td>{{ $totalReceiveQTY }}</td>
                    <td>{{ $totalRate }}</td>
                    <td>{{ $totalAmount }}</td>
                    <td>{{ $totalRejectQTY }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
        </tbody>

    </table>
</div>

