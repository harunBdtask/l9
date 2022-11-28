<div class="row">
    <div class="col-md-5"
         style="float: left; position:relative; margin-top:30px">
        <table class="borderless">
            <tbody>
            <tr>
                <td style="padding-left: 0;">
                    <b>Requisition ID :</b>
                </td>
                <td style="padding-left: 30px;"> {{ $procurementRequisition->requisition_uid }}  </td>
            </tr>
            <tr>
                <td style="padding-left: 0;">
                    <b>Factory :</b>
                </td>
                <td style="padding-left: 30px;"> {{ $procurementRequisition->factory->factory_name }}  </td>
            </tr>
            <tr>
                <td style="padding-left: 0;">
                    <b>Date : </b>
                </td>
                <td style="padding-left: 30px;"> {{ \Carbon\Carbon::create($procurementRequisition->date)->toFormattedDateString() }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;">
                    <b>Required Date :</b>
                </td>
                <td style="padding-left: 30px;"> {{ \Carbon\Carbon::create($procurementRequisition->required_date)->toFormattedDateString() }} </td>
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
                    <b>Department:</b>
                </td>
                <td style="padding-left: 30px;"> {{ $procurementRequisition->department->department_name }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;">
                    <b>Priority :</b>
                </td>
                <td style="padding-left: 30px;"> {{ $procurementRequisition->priority_value }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;">
                    <b>Approved By :</b>
                </td>
                <td style="padding-left: 30px;"> {{ $procurementRequisition->approvalBy->screen_name }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;">
                    <b>Created By:</b>
                </td>
                <td style="padding-left: 30px;"> {{ $procurementRequisition->createdBy->screen_name }}  </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<table class="reportTable" style="width: 100%;margin-top: 100px;">
    <thead>
    <tr>
        <th>Item</th>
        <th>Item Description</th>
        <th>Uom</th>
        <th>Qty</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @php
        $requisitionDetails = $procurementRequisition->procurementRequisitionDetails;
    @endphp

    @forelse($requisitionDetails as $detail)

        <tr>
            <td>{{ $detail->item->item_group }}</td>
            <td>{{ $detail->item_description }}</td>
            <td>{{ $detail->uom->unit_of_measurement??'' }}</td>
            <td>{{ $detail->qty }}</td>
            <td>{{ $detail->remarks }}</td>
        </tr>
    @empty
        <tr class="tr-height">
            <td colspan="5" class="text-center text-danger">No Account Found</td>
        </tr>
    @endforelse
    </tbody>
</table>