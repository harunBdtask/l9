{!! Form::model($contract, ['url' => '/commercial/export-amendment/' . $contract->id, 'method' => 'post', 'id' => 'form']) !!}

<table class="reportTable mainForm">
    <tbody>
    <tr>
        <th><label for="amendment_no">Amend. No</label></th>
        <td>
            {!! Form::hidden('contract_id', $contract->id) !!}
            {!! Form::number('amendment_no', $contract->amended + 1, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'amendment_no', 'readonly']) !!}
        </td>

        <th><label for="amendment_date">Amend. Date</label></th>
        <td>
            {!! Form::date('amendment_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'amendment_date', 'data-parsley-required']) !!}

            @if($errors->has('amendment_date'))
                <span class="text-danger">{{ $errors->first('amendment_date') }}</span>
            @endif
        </td>
    </tr>

    <tr>
        <th><label for="amendment_value">Amend. Value</label></th>
        <td>{!! Form::number('amendment_value', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'amendment_value', 'step' => 0.01]) !!}</td>

        <th><label for="value_changed_by">Changed By</label></th>
        <td>{{ Form::select('value_changed_by', \SkylarkSoft\GoRMG\Commercial\Options::CHANGED_BY, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'value_changed_by']) }}</td>
    </tr>

    <tr>
        <th><label for="last_shipment_date">Last Shipment Date </label></th>
        <td>{!! Form::date('last_shipment_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'last_shipment_date', 'placeholder' => '']) !!}</td>

        <th><label for="lc_expiry_date">Expiry Date</label></th>
        <td>{!! Form::date('lc_expiry_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'lc_expiry_date', 'placeholder' => '']) !!}</td>
    </tr>

    <tr>
        <th><label for="shipping_mode">Shipping Mode</label></th>
        <td>{{ Form::select('shipping_mode', \SkylarkSoft\GoRMG\Commercial\Options::SHIPPING_MODES, $contract->shipping_mode, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'shipping_mode']) }}</td>

        <th><label for="inco_term">Inco Term</label></th>
        <td>{{ Form::select('inco_term', \SkylarkSoft\GoRMG\Commercial\Options::INCO_TERMS, $contract->inco_term, ['id' => 'inco_term', 'class' => 'form-control form-control-sm form-control form-control-sm-sm']) }}</td>
    </tr>

    <tr>
        <th><label for="inco_term_place">Inco Term Place</label></th>
        <td>{!! Form::text('inco_term_place', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'inco_term_place', 'placeholder' => '']) !!}</td>

        <th><label for="port_of_entry">Port of Entry</label></th>
        <td>{!! Form::text('port_of_entry', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'port_of_entry', 'placeholder' => '']) !!}</td>
    </tr>

    <tr>
        <th><label for="port_of_loading">Port of Loading</label></th>
        <td>{!! Form::text('port_of_loading', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'port_of_loading', 'placeholder' => '']) !!}</td>

        <th><label for="port_of_discharge">Port of Discharge</label></th>
        <td>{!! Form::text('port_of_discharge', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'port_of_discharge', 'placeholder' => '']) !!}</td>
    </tr>

    <tr>
        <th><label for="pay_term">Pay Term</label></th>
        <td>{{ Form::select('pay_term', \SkylarkSoft\GoRMG\Commercial\Options::PAY_TERMS, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'pay_term']) }}</td>

        <th><label for="tenor">Tenor</label></th>
        <td>{!! Form::text('tenor', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'tenor', 'placeholder' => '']) !!}</td>
    </tr>

    <tr>
        <th><label for="claim_adjustment">Claim Adjustment</label></th>
        <td>{!! Form::text('claim_adjusted', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-type="number"','id' => 'claim_adjustment', 'placeholder' => '']) !!}</td>

        <th><label for="claim_adjusted_by">Adjusted By</label></th>
        <td>{{ Form::select('claim_adjusted_by', \SkylarkSoft\GoRMG\Commercial\Options::CHANGED_BY, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'claim_adjusted_by']) }}</td>
    </tr>


    <tr>
        <th><label for="discount_clauses">Discount Clauses</label></th>
        <td colspan="3">{!! Form::text('discount_clauses', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'discount_clauses', 'placeholder' => '']) !!}</td>
    </tr>

    <tr>
        <th><label for="remarks">Remarks</label></th>
        <td colspan="3">{!! Form::text('remarks', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'remarks', 'placeholder' => '']) !!}</td>
    </tr>

    <tr>
        <th></th>
        <td>
            <button  type="submit" class="btn btn-primary btn-sm btn-block">
                Amend
            </button>
        </td>
    </tr>

    </tbody>
</table>

{!! Form::close() !!}
