<div class="row p-x-1">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-5" style="float: left; position:relative; margin-top:30px">
                <table class="borderless">

                    <tbody>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Transfer UID :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $fabricTransfer->transfer_uid }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>From Company :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $fabricTransfer->fromCompany->factory_name ?? '' }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Transfer Date :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $fabricTransfer->transfer_date }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Challan No :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $fabricTransfer->challan_no }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Remarks :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $fabricTransfer->remarks }} </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-5" style="float: right; position:relative;margin-top:30px">
                <table class="borderless">
                    <tbody>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Criteria :</b>
                        </td>
                        <td style="padding-left: 30px;">
                            @if($fabricTransfer->criteria == 1)
                                <span>Order To Order</span>
                            @elseif($fabricTransfer->criteria == 2)
                                <span>Store To Store</span>
                            @else
                                <span>Company To Company</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>To Company :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $fabricTransfer->toCompany->factory_name ?? '' }} </td>

                        {{-- <td style="padding-left: 30px;"> {{ collect($order_nos)->implode(', ') }} </td> --}}
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Transfer Type :</b>
                        </td>
                        <td style="padding-left: 30px;">
                            @if($fabricTransfer->transfer_type == 1)
                                <span>Receive Basis</span>
                            @else
                                <span>Issue Basis</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Ready To Approve:</b>
                        </td>
                        <td style="padding-left: 30px;">
                            @if($fabricTransfer->ready_to_approve == 1)
                                <span>Yes</span>
                            @else
                                <span>No</span>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <table class="reportTable" style="width: 100%;margin-top: 30px;">
            <thead>
            <tr>
                <th>SL</th>
                <th>Transfer Challan No</th>
                <th>Unique Id</th>
                <th>Order No</th>
                <th>Operation</th>
                <th>Body Part</th>
                <th>Fabric Composition</th>
                <th>Fabric Type</th>
                <th>Fin Dia</th>
                <th>GSM</th>
                <th>Total Roll</th>
                <th>Transfer Qty</th>
                <th>Ready To Approve</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @foreach($fabricTransfer->details as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->transfer->challan_no }}</td>
                    <td>{{ $detail->transfer->transfer_uid }}</td>
                    <td>{{ $detail->toOrderDetail->order_no ?? '' }}</td>
                    <td>{{ $detail->toOrderDetail->subTextileOperation->name ?? '' }}</td>
                    <td>{{ $detail->toOrderDetail->bodyPart->name ?? '' }}</td>
                    <td>{{ $detail->toOrderDetail->fabric_composition_value ?? '' }}</td>
                    <td>{{ $detail->toOrderDetail->fabricType->construction_name ?? '' }}</td>
                    <td>{{ $detail->toOrderDetail->finish_dia ?? '' }}</td>
                    <td>{{ $detail->toOrderDetail->gsm ?? '' }}</td>
                    <td>{{ $detail->detailMSI->to_total_roll ?? '' }}</td>
                    <td>{{ $detail->detailMSI->transfer_qty }}</td>
                    <td>
                        @if($fabricTransfer->ready_to_approve == 1)
                            <span>Yes</span>
                        @else
                            <span>No</span>
                        @endif
                    </td>
                    <td>{{ $fabricTransfer->remarks }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
