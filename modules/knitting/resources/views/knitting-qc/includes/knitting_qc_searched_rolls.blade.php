<div class="col-sm-12">
    <h5>Before QC Rolls</h5>
    <div class="table-responsive">
        <table class="reportTable">
            <thead>
            <tr class="green-200">
                <th>Roll Barcode No</th>
                <th>Shift</th>
                <th>Operator</th>
                <th>QC Shift</th>
                <th>QC Operator</th>
                <th>Roll Weight</th>
                <th>Production Date Time</th>
                <th>Pcs Production</th>
                <th>QC Weight</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if($data && $data->count())
                @foreach($data->whereNull('qc_roll_weight') as $rollData)
                    @php
                        $scanable_barcode = $rollData->id ? str_pad($rollData->id, 9, '0', STR_PAD_LEFT) : '';
                    @endphp
                    <tr>
                        <td>{{ $scanable_barcode }}</td>
                        <td>{{ $rollData->shift->shift_name }}</td>
                        <td>{{ $rollData->operator->operator_name }}</td>
                        <td>{{ $rollData->qcShift->shift_name }}</td>
                        <td>{{ $rollData->qcOperator->operator_name }}</td>
                        <td>{{ $rollData->roll_weight }}</td>
                        <td>{{ $rollData->production_datetime }}</td>
                        <td>{{ $rollData->production_pcs_total }}</td>
                        <td>{{ $rollData->qc_roll_weight }}</td>
                        <td>
                            @permission('permission_of_knitting_qc_add')
                            <button class="btn btn-sm btn-success goto-qc-section"
                                    data-url="{{ url('/knitting/knitting-qc/check?roll-id='. $rollData->id)}}">
                                QC Check
                            </button>
                            @endpermission
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <th colspan="10">Not Data Found</th>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
<br>
<div class="col-sm-12">
    <h5>After QC Rolls</h5>
    <div class="table-responsive">
        <table class="reportTable">
            <thead>
            <tr class="green-200">
                <th>Roll Barcode No</th>
                <th>Shift</th>
                <th>Operator</th>
                <th>QC Shift</th>
                <th>QC Operator</th>
                <th>Roll Weight</th>
                <th>Production Date Time</th>
                <th>Pcs Production</th>
                <th>QC Weight</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if($data && $data->count())
                @foreach($data->whereNotNull('qc_roll_weight') as $rollData)
                    @php
                        $scanable_barcode = $rollData->id ? str_pad($rollData->id, 9, '0', STR_PAD_LEFT) : '';
                    @endphp
                    <tr>
                        <td>{{ $scanable_barcode }}</td>
                        <td>{{ $rollData->shift->shift_name }}</td>
                        <td>{{ $rollData->operator->operator_name }}</td>
                        <td>{{ $rollData->qcShift->shift_name }}</td>
                        <td>{{ $rollData->qcOperator->operator_name }}</td>
                        <td>{{ $rollData->roll_weight }}</td>
                        <td>{{ $rollData->production_datetime }}</td>
                        <td>{{ $rollData->production_pcs_total }}</td>
                        <td>{{ $rollData->qc_roll_weight }}</td>
                        <td>
                            @permission('permission_of_knitting_qc_edit')
                            <button class="btn btn-sm btn-success goto-qc-section"
                                    data-url="{{ url('/knitting/knitting-qc/check?roll-id='. $rollData->id)}}">QC
                                ReCheck
                            </button>
                            @endpermission
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <th colspan="10">Not Data Found</th>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
