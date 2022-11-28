{!! Form::model($contract, ['url' => '#']) !!}

<table class="reportTable mainForm">
    <tbody>
    <tr>
        <th style="min-width: 100px"><label for="beneficiary_id">Beneficiary</label></th>
        <td style="min-width: 150px">{{ Form::select('beneficiary_id', $factories, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'disabled', 'data-parsley-required']) }}</td>

        <th><label for="buyer">Buyer Name <span class="class-danger req"></span></label></th>
        <td>{{ Form::select('buyer_id', $buyers->prepend('Select Buyer', ''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'buyer', 'disabled']) }}</td>

    </tr>

    <tr>

        <th style="min-width: 100px"><label for="internal_file_no">Internal File No</label></th>
        <td style="min-width: 150px">{!! Form::text('internal_file_no', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'disabled']) !!}</td>

        <th><label for="lc_value">LC Value</label></th>
        <td>{!! Form::text('lc_value', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'disabled',  'placeholder' => '']) !!}</td>

    </tr>

    <tr>
        <th><label for="currency">Currency</label></th>
        <td>{{ Form::select('currency', \SkylarkSoft\GoRMG\Commercial\Options::CURRENCIES, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'disabled']) }}</td>

        <th><label for="replacement_lc">Replacement LC</label></th>
        <td>{{ Form::select('replacement_lc', \SkylarkSoft\GoRMG\Commercial\Options::REPLACEMENT_LC, null, ['id' => 'replacement_lc', 'class' => 'form-control form-control-sm form-control form-control-sm-sm','disabled']) }}</td>
    </tr>

    <tr>
        <th><label for="lien_bank_id">Lien Bank</label></th>
        <td>{{ Form::select('lien_bank_id', \SkylarkSoft\GoRMG\Commercial\Options::BANKS, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'disabled']) }}</td>

        <th><label for="lien_date">Lien Date</label></th>
        <td>{!! Form::date('lien_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'placeholder' => '',  'disabled']) !!}</td>
    </tr>

    <tr>
        <th><label for="last_shipment_date">Last Shipment Date </label></th>
        <td>{!! Form::date('last_shipment_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'placeholder' => '',  'disabled']) !!}</td>

        <th><label for="lc_expiry_date">Expiry Date</label></th>
        <td>{!! Form::date('lc_expiry_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'placeholder' => '',  'disabled']) !!}</td>
    </tr>

    <tr>

        <th><label for="tolerance_percent">Tolerance %</label></th>
        <td>{!! Form::number('tolerance_percent', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'placeholder' => '',  'disabled']) !!}</td>

        <th><label for="shipping_mode">Shipping Mode</label></th>
        <td>{{ Form::select('shipping_mode', \SkylarkSoft\GoRMG\Commercial\Options::SHIPPING_MODES, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm',  'disabled']) }}</td>

    </tr>

    <tr>
        <th><label for="port_of_entry">Port of Entry</label></th>
        <td>{!! Form::text('port_of_entry', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'placeholder' => '',  'disabled']) !!}</td>

        <th><label for="port_of_loading">Port of Loading</label></th>
        <td>{!! Form::text('port_of_loading', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'placeholder' => '',  'disabled']) !!}</td>

    </tr>

    <tr>
        <th><label for="port_of_discharge">Port of Discharge</label></th>
        <td>{!! Form::text('port_of_discharge', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'placeholder' => '', 'disabled']) !!}</td>

        <th><label for="pay_term">Pay Term</label></th>
        <td>{{ Form::select('pay_term', \SkylarkSoft\GoRMG\Commercial\Options::PAY_TERMS, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'disabled']) }}</td>
    </tr>

    <tr>
        <th><label for="tenor">Tenor</label></th>
        <td>{!! Form::text('tenor', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'placeholder' => '', 'disabled']) !!}</td>
        <th><label for="claim_adjustment">Claim Adjustment</label></th>
        <td>{!! Form::text('claim_adjustment', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'placeholder' => '', 'disabled']) !!}</td>
    </tr>

    <tr>
        <th><label for="discount_clauses">Discount Clauses</label></th>
        <td colspan="3">{!! Form::text('discount_clauses', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm',  'placeholder' => '', 'disabled']) !!}</td>
    </tr>

    <tr>
        <th><label for="export_item_category">Export Item Category </label></th>
        <td>{{ Form::select('export_item_category', \SkylarkSoft\GoRMG\Commercial\Options::EXPORT_ITEM_CATEGORIES, null, ['id' => 'export_item_category', 'class' => 'form-control form-control-sm form-control form-control-sm-sm', 'disabled']) }}</td>

        <th><label for="remarks">Remarks</label></th>
        <td colspan="5">{!! Form::text('remarks', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'placeholder' => '', 'disabled']) !!}</td>
    </tr>

    <tr>
        <th><label for="buying_agent_id">Buying Agent </label></th>
        <td>{{ Form::select('buying_agent_id', $buying_agents, $contract->buying_agent_id??null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'buying_agent_id','disabled']) }}</td>

        <th><label for="primary_contract_id">Primary Contract </label></th>
        @php  
            $pcontracts = [];
            if(!empty($contract->primary_contract)){
                $pcontracts = [$contract->primary_contract->id => $contract->primary_contract->unique_id];
            }
            @endphp
        <td>{{ Form::select('primary_contract_id', collect($pcontracts)->prepend('Select Contract', ''), $contract->primary_contract_id??null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'primary_contract_id', 'disabled']) }}</td>
    </tr>
    <tr>
        <th><label for="primary_contract_value">Primary Contract Value </label></th>
        <td>{!! Form::text('primary_contract_value', $contract->primary_contract->contract_value??null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'primary_contract_value','disabled']) !!}</td>

    </tr>


    </tbody>
</table>

{!! Form::close() !!}
