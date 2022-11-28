{!! Form::model($contract, ['url' => !$contract ? 'commercial/export-lc' : 'commercial/export-lc/' . $contract->id, 'method' => !$contract ? 'post' : 'put', 'id' => 'form' ]) !!}

<div class="row form-group table-responsive">
    <div class="col-md-10 col-md-offset-1">
        <table class="reportTable mainForm">
            <tbody>
            <tr>
                <th style="min-width: 150px"><label for="beneficiary_id">Beneficiary <span
                            class="text-danger req">*</span></label></th>
                <td style="min-width: 200px">{{ Form::select('beneficiary_id', $factories, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'beneficiary_id']) }}</td>

                <th style="min-width: 150px"><label for="internal_file_no">Internal File No <span
                            class="text-danger req">*</span></label></th>
                <td style="min-width: 200px">{!! Form::text('internal_file_no', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'internal_file_no']) !!}</td>

                <th style="min-width: 150px"><label for="bank_file_no">Bank File No</label></th>
                <td style="min-width: 200px">{!! Form::text('bank_file_no', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'bank_file_no']) !!}</td>
            </tr>

            <tr>
                <th><label for="year">Year <span class="text-danger req">*</span></label></th>
                <td>{!! Form::text('year', null, ['class'=>'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'year', 'data-parsley-required']) !!}</td>

                <th><label for="lc_number">LC Number <span class="text-danger req">*</span></label></th>
                <td>{!! Form::text('lc_number', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'lc_number', 'placeholder' => '']) !!}</td>

                <th><label for="lc_value">LC Value <span class="text-danger req">*</span></label></th>
                <td>{!! Form::text('lc_value', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'lc_value', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="lc_date">LC Date <span class="text-danger req">*</span></label></th>
                <td>{!! Form::date('lc_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'lc_date', 'placeholder' => '']) !!}</td>

                <th><label for="currency">Currency</label></th>
                <td>{{ Form::select('currency', \SkylarkSoft\GoRMG\Commercial\Options::CURRENCIES, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'currency']) }}</td>

                <th><label for="buyer">Buyer Name <span class="text-danger req">*</span></label></th>
                <td>{{ Form::select('buyer_id', $buyers->prepend('Select Buyer', ''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'buyer']) }}</td>
            </tr>

            <tr>
                <th><label for="applicant">Applicant Name</label></th>
                <td>{{ Form::select('applicant_id', $buyers->prepend('Select Applicant', ''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'applicant']) }}</td>

                <th><label for="notifying_party">Notifying Party</label></th>
                <td>{{ Form::select('notifying_party_id', $buyers->prepend('Select Party', ''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'notifying_party']) }}</td>

                <th><label for="consignee">Consignee</label></th>
                <td>{{ Form::select('consignee_id', $buyers->prepend('Select Consignee', ''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'consignee']) }}</td>
            </tr>

            <tr>
                <th><label for="issuing_bank">Issuing Bank</label></th>
                <td>{!! Form::text('issuing_bank', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'issuing_bank', 'placeholder' => '']) !!}</td>

                <th><label for="lien_bank_id">Lien Bank</label></th>
                <td>{{ Form::select('lien_bank_id', \SkylarkSoft\GoRMG\Commercial\Options::BANKS, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'lien_bank_id']) }}</td>

                <th><label for="lien_date">Lien Date</label></th>
                <td>{!! Form::date('lien_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'lien_date', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="last_shipment_date">Last Shipment Date <span class="text-danger req">*</span></label>
                </th>
                <td>{!! Form::date('last_shipment_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'last_shipment_date', 'placeholder' => '']) !!}</td>

                <th><label for="lc_expiry_date">LC Expiry Date</label></th>
                <td>{!! Form::date('lc_expiry_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'lc_expiry_date', 'placeholder' => '']) !!}</td>

                <th><label for="tolerance_percent">Tolerance %</label></th>
                <td>{!! Form::number('tolerance_percent', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'tolerance_percent', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="shipping_mode">Shipping Mode</label></th>
                <td>{{ Form::select('shipping_mode', \SkylarkSoft\GoRMG\Commercial\Options::SHIPPING_MODES, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'shipping_mode']) }}</td>

                <th><label for="pay_term">Pay Term</label></th>
                <td>{{ Form::select('pay_term', \SkylarkSoft\GoRMG\Commercial\Options::PAY_TERMS, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'pay_term']) }}</td>

                <th><label for="tenor">Tenor</label></th>
                <td>{!! Form::text('tenor', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'tenor', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="inco_term">Inco Term</label></th>
                <td>{{ Form::select('inco_term', \SkylarkSoft\GoRMG\Commercial\Options::INCO_TERMS, null, ['id' => 'inco_term', 'class' => 'form-control form-control-sm form-control form-control-sm-sm']) }}</td>

                <th><label for="inco_term_place">Inco Term Place</label></th>
                <td>{!! Form::text('inco_term_place', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'inco_term_place', 'placeholder' => '']) !!}</td>

                <th><label for="lc_source">LC Source</label></th>
                <td>{{ Form::select('lc_source', \SkylarkSoft\GoRMG\Commercial\Options::CONTACT_SOURCES, null, ['id' => 'lc_source', 'class' => 'form-control form-control-sm form-control form-control-sm-sm']) }}</td>
            </tr>

            <tr>
                <th><label for="port_of_entry">Port of Entry</label></th>
                <td>{!! Form::text('port_of_entry', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'port_of_entry', 'placeholder' => '']) !!}</td>

                <th><label for="port_of_loading">Port of Loading</label></th>
                <td>{!! Form::text('port_of_loading', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'port_of_loading', 'placeholder' => '']) !!}</td>

                <th><label for="port_of_discharge">Port of Discharge</label></th>
                <td>{!! Form::text('port_of_discharge', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'port_of_discharge', 'placeholder' => '']) !!}</td>
            </tr>

            @php
                $btb_percent = \SkylarkSoft\GoRMG\Commercial\Models\CommercialVariable::where('variable_name','btb_limit_percent')->first();
            @endphp

            <tr>
                <th><label for="doc_present_days">Doc Present Days</label></th>
                <td>{!! Form::number('doc_present_days', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'doc_present_days', 'placeholder' => '']) !!}</td>

                <th><label for="btb_limit_percent">BTB Limit %</label></th>
                <td>{!! Form::number('btb_limit_percent', $btb_percent->value ?? null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'btb_limit_percent', 'readonly', 'placeholder' => '']) !!}</td>

                <th><label for="foreign_comn_percent">Foreign Comn%</label></th>
                <td>{!! Form::number('foreign_comn_percent', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'foreign_comn_percent', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="local_comn_percent">Local Comn%</label></th>
                <td>{!! Form::number('local_comn_percent', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'local_comn_percent', 'placeholder' => '']) !!}</td>

                <th><label for="transferring_bank_ref">Transferring Bank Ref</label></th>
                <td>{!! Form::text('transferring_bank_ref', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'transferring_bank_ref', 'placeholder' => '']) !!}</td>

                <th><label for="transferable">Transferable</label></th>
                <td>{{ Form::select('transferable', \SkylarkSoft\GoRMG\Commercial\Options::TRANSFERABLE, null, ['id' => 'transferable', 'class' => 'form-control form-control-sm form-control form-control-sm-sm']) }}</td>
            </tr>

            <tr>
                <th><label for="replacement_lc">Replacement LC</label></th>
                <td>{{ Form::select('replacement_lc', \SkylarkSoft\GoRMG\Commercial\Options::REPLACEMENT_LC, null, ['id' => 'replacement_lc', 'class' => 'form-control form-control-sm form-control form-control-sm-sm']) }}</td>

                <th><label for="transferring_bank">Transferring Bank</label></th>
                <td>{!! Form::text('transferring_bank', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'transferring_bank', 'placeholder' => '']) !!}</td>

                <th><label for="negotiating_bank">Negotiating Bank</label></th>
                <td>{!! Form::text('negotiating_bank', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'negotiating_bank', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="nominated_ship_line">Nominated Ship. Line</label></th>
                <td>{!! Form::text('nominated_ship_line', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'nominated_ship_line', 'placeholder' => '']) !!}</td>

                <th><label for="re_imbursing_bank">Re-Imbursing Bank</label></th>
                <td>{!! Form::text('re_imbursing_bank', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 're_imbursing_bank', 'placeholder' => '']) !!}</td>

                <th><label for="claim_adjustment">Claim Adjustment</label></th>
                <td>{!! Form::text('claim_adjustment', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'claim_adjustment', 'placeholder' => '']) !!}</td>

            </tr>

            <tr>
                <th><label for="expiry_place">Expiry Place</label></th>
                <td>{!! Form::text('expiry_place', 'Bangladesh', ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'expiry_place', 'placeholder' => '']) !!}</td>

                <th><label for="reason">Reason</label></th>
                <td colspan="3">{!! Form::text('reason', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'reason', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="bl_clause">BL Clause</label></th>
                <td colspan="5">{!! Form::text('bl_clause', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'bl_clause', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="reimbursement_clauses">Reimbursement Clauses</label></th>
                <td colspan="5">{!! Form::text('reimbursement_clauses', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'reimbursement_clauses', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="discount_clauses">Discount Clauses</label></th>
                <td colspan="5">{!! Form::text('discount_clauses', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'discount_clauses', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="export_item_category">Export Item Category <span
                            class="text-danger req">*</span></label></th>
                <td>{{ Form::select('export_item_category', \SkylarkSoft\GoRMG\Commercial\Options::EXPORT_ITEM_CATEGORIES, null, ['id' => 'export_item_category', 'class' => 'form-control form-control-sm form-control form-control-sm-sm']) }}</td>

                <th><label for="remarks">Remarks</label></th>
                <td colspan="5">{!! Form::text('remarks', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'remarks', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th></th>
                <td>
                    <button class="btn btn-primary btn-sm btn-block">
                        <i class="glyphicon glyphicon-floppy-open"></i>
                        {{ $contract ? 'UPDATE' : 'SAVE' }}
                    </button>
                </td>
            </tr>

            </tbody>
        </table>
    </div>

</div>

{!! Form::close() !!}
