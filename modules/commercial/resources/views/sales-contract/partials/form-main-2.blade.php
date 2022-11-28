{!! Form::model($contract, ['url' => !$contract ? 'commercial/sales-contract' : 'commercial/sales-contract/' . $contract->id, 'method' => !$contract ? 'post' : 'put', 'id' => 'form']) !!}

<div class="row form-group table-responsive">
    <div class="col-md-10 col-md-offset-1 customForm">

        <div class="d-flex flex-wrap">

            <div class="col-md-3 form-group">
                <label for="beneficiary_id">Beneficiary <span class="text-danger req">*</span></label>
                {{ Form::hidden('sales_contract_id', $contract->id ?? null, ['id' => 'sales_contract_id']) }}

                {{ Form::select('beneficiary_id', $factories, null, ['class' => 'form-control form-control-sm', 'data-parsley-required', 'id' => 'beneficiary_id']) }}
            </div>

            <div class="col-md-3 form-group">
                <label for="internal_file_no">Internal File No <span class="text-danger req">*</span></label>
                {!! Form::text('internal_file_no', null, ['class' => 'form-control form-control-sm', 'data-parsley-required', 'id' => 'internal_file_no']) !!}
            </div>

            @unless(in_array('bank_file_no', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="bank_file_no">Bank File No</label>
                    {!! Form::text('bank_file_no', null, ['class' => 'form-control form-control-sm', 'id' => 'bank_file_no']) !!}
                </div>
            @endunless


            <div class="col-md-3 form-group">
                <label for="year">Year <span class="text-danger req">*</span></label>
                {!! Form::text('year', null, ['class' => 'form-control form-control-sm', 'id' => 'year', 'data-parsley-required']) !!}
            </div>

            <div class="col-md-3 form-group">
                <label for="contract_number">Contract Number <span class="text-danger req">*</span></label>
                {!! Form::text('contract_number', null, ['class' => 'form-control form-control-sm', 'data-parsley-required', 'id' => 'contract_number', 'placeholder' => '']) !!}
            </div>

            <div class="col-md-3 form-group">
                <label for="contract_value">Contract Value <span
                            class="text-danger req">*</span></label>
                {!! Form::text('contract_value', null, ['class' => 'form-control form-control-sm',isset($contract->amendments_count) && $contract->amendments_count >0 ? 'readonly' : '', 'data-parsley-required', 'id' => 'contract_value', 'placeholder' => '']) !!}
            </div>

            @unless(in_array('currency', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="currency_id">Currency</label>
                    {{ Form::select('currency_id', $currencies, $contract->currency_id ?? '2', ['class' => 'form-control form-control-sm', 'id' => 'currency_id']) }}
                </div>
            @endunless

            <div class="col-md-3 form-group">
                <label for="contract_date">Contract Date <span
                            class="text-danger req">*</span></label>
                {!! Form::date('contract_date', null, ['class' => 'form-control form-control-sm', 'data-parsley-required', 'id' => 'contract_date', 'placeholder' => '']) !!}

            </div>

            @unless(in_array('convertible_to', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="convertible_to">Convertible To</label>
                    {{ Form::select('convertible_to', \SkylarkSoft\GoRMG\Commercial\Options::CONVERTIBLE_TO, null, ['class' => 'form-control form-control-sm', 'id' => 'convertible_to']) }}
                </div>
            @endunless

            @unless(in_array('buyer_name', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="buyer">Buyer Name <span class="class-danger req"></span></label>
                    {{ Form::select('buyer_id', $buyers->prepend('Select Buyer', ''), null, ['class' => 'form-control form-control-sm', 'data-parsley-required', 'id' => 'buyer']) }}
                </div>
            @endunless


            @unless(in_array('applicant_name', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="applicant">Applicant Name</label>
                    {{ Form::select('applicant_id', $buyers->prepend('Select Applicant', ''), null, ['class' => 'form-control form-control-sm', 'id' => 'applicant']) }}
                </div>
            @endunless

            @unless(in_array('notifying_party', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="notifying_party">Notifying Party</label>
                    {{ Form::select('notifying_party_id', $buyers->prepend('Select Party', ''), null, ['class' => 'form-control form-control-sm', 'id' => 'notifying_party']) }}
                </div>
            @endunless

            @unless(in_array('consignee', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="consignee">Consignee</label>
                    {{ Form::select('consignee_id', $buyers->prepend('Select Consignee', ''), null, ['class' => 'form-control form-control-sm', 'id' => 'consignee']) }}
                </div>
            @endunless

            @unless(in_array('lien_bank', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="lien_bank_id">Lien Bank</label>
                    {{ Form::select('lien_bank_id', $lien_banks->prepend("Select Bank"), null, ['class' => 'form-control form-control-sm', 'id' => 'lien_bank_id']) }}
                </div>
            @endunless

            @unless(in_array('lien_date', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="lien_date">Lien Date</label>
                    {!! Form::date('lien_date', null, ['class' => 'form-control form-control-sm', 'id' => 'lien_date', 'placeholder' => '']) !!}
                </div>
            @endunless

            <div class="col-md-3 form-group">
                <label for="last_shipment_date">Last Shipment Date <span
                            class="text-danger req">*</span></label>
                {!! Form::date('last_shipment_date', null, ['class' => 'form-control form-control-sm', 'data-parsley-required', 'id' => 'last_shipment_date', 'placeholder' => '']) !!}
            </div>

            @unless(in_array('expiry_date', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="expiry_date">Expiry Date</label>
                    {!! Form::date('expiry_date', null, ['class' => 'form-control form-control-sm', 'id' => 'expiry_date', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('tolerance', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="tolerance_percent">Tolerance %</label>
                    {!! Form::number('tolerance_percent', null, ['class' => 'form-control form-control-sm', 'id' => 'tolerance_percent', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('shipping_mode', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="shipping_mode">Shipping Mode</label>
                    {{ Form::select('shipping_mode', \SkylarkSoft\GoRMG\Commercial\Options::SHIPPING_MODES, null, ['class' => 'form-control form-control-sm', 'id' => 'shipping_mode']) }}
                </div>
            @endunless

            @unless(in_array('pay_term', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="pay_term">Pay Term</label>
                    {{ Form::select('pay_term', \SkylarkSoft\GoRMG\Commercial\Options::PAY_TERMS, null, ['class' => 'form-control form-control-sm', 'id' => 'pay_term']) }}
                </div>
            @endunless

            @unless(in_array('tenor', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="tenor">Tenor</label>
                    {!! Form::text('tenor', null, ['class' => 'form-control form-control-sm', 'id' => 'tenor', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('inco_term', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="inco_term">Inco Term</label>
                    {{ Form::select('inco_term', \SkylarkSoft\GoRMG\Commercial\Options::INCO_TERMS, null, ['id' => 'inco_term', 'class' => 'form-control form-control-sm']) }}
                </div>
            @endunless

            @unless(in_array('inco_term_place', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="inco_term_place">Inco Term Place</label>
                    {!! Form::text('inco_term_place', null, ['class' => 'form-control form-control-sm', 'id' => 'inco_term_place', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('contract_source', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="contract_source">Contract Source</label>
                    {{ Form::select('contract_source', \SkylarkSoft\GoRMG\Commercial\Options::CONTACT_SOURCES, null, ['id' => 'contract_source', 'class' => 'form-control form-control-sm']) }}
                </div>
            @endunless

            @unless(in_array('port_of_entry', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="port_of_entry">Port of Entry</label>
                    {!! Form::text('port_of_entry', null, ['class' => 'form-control form-control-sm', 'id' => 'port_of_entry', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('port_of_loading', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="port_of_loading">Port of Loading</label>
                    {!! Form::text('port_of_loading', null, ['class' => 'form-control form-control-sm', 'id' => 'port_of_loading', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('port_of_discharge', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="port_of_discharge">Port of Discharge</label>
                    {!! Form::text('port_of_discharge', null, ['class' => 'form-control form-control-sm', 'id' => 'port_of_discharge', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('shipping_line', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="shipping_line">Shipping Line</label>
                    {!! Form::text('shipping_line', null, ['class' => 'form-control form-control-sm', 'id' => 'shipping_line', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('doc_present_days', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="doc_present_days">Doc Present Days</label>
                    {!! Form::number('doc_present_days', null, ['class' => 'form-control form-control-sm', 'id' => 'doc_present_days', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('claim_adjustment', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="claim_adjustment">Claim Adjustment</label>
                    {!! Form::text('claim_adjustment', null, ['class' => 'form-control form-control-sm','data-parsley-type="number"', isset($contract->amendments_count) && $contract->amendments_count >0 ? 'readonly' : '','id' => 'claim_adjustment', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('btb_limit', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="btb_limit_percent">BTB Limit %</label>
                    {!! Form::number('btb_limit_percent',null, ['class' => 'form-control form-control-sm', 'id' => 'btb_limit_percent', 'readonly', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('foreign_comn', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="foreign_comn_percent">Foreign Comn%</label>
                    {!! Form::number('foreign_comn_percent', null, ['class' => 'form-control form-control-sm', 'id' => 'foreign_comn_percent', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('local_comn', $hiddenFields))
                <div class="col-md-3 form-group">
                    <label for="local_comn_percent">Local Comn%</label>
                    {!! Form::number('local_comn_percent', null, ['class' => 'form-control form-control-sm', 'id' => 'local_comn_percent', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('discount_clauses', $hiddenFields))
                <div class="col-md-12 form-group">
                    <label for="discount_clauses">Discount Clauses</label>
                    {!! Form::text('discount_clauses', null, ['class' => 'form-control form-control-sm', 'id' => 'discount_clauses', 'placeholder' => '']) !!}
                </div>
            @endunless

            @unless(in_array('bl_clause', $hiddenFields))
                <div class="col-md-12 form-group">
                    <label for="bl_clause">BL Clause</label>
                    {!! Form::text('bl_clause', null, ['class' => 'form-control form-control-sm', 'id' => 'bl_clause', 'placeholder' => '']) !!}
                </div>
            @endunless

            <div class="col-md-3 form-group">
                <label for="export_item_category">Export Item Category <span
                            class="text-danger req">*</span></label>
                {{ Form::select('export_item_category', \SkylarkSoft\GoRMG\Commercial\Options::EXPORT_ITEM_CATEGORIES, null, ['id' => 'export_item_category', 'class' => 'form-control form-control-sm']) }}
            </div>

            @unless(in_array('remarks', $hiddenFields))
                <div class="col-md-9 form-group">
                    <label for="remarks">Remarks</label>
                    {!! Form::text('remarks', null, ['class' => 'form-control form-control-sm', 'id' => 'remarks', 'placeholder' => '']) !!}
                </div>
            @endunless
        </div>

        <div class="d-flex flex-row-reverse">
            <div class="col-md-3 form-group">
                <button class="btn btn-primary btn-sm btn-block">
                    <i class="glyphicon glyphicon-floppy-open"></i>
                    {{ $contract ? 'UPDATE' : 'SAVE' }}
                </button>
            </div>
        </div>

    </div>
</div>

{!! Form::close() !!}
