@if ($errors->any())
    <div class="alert alert-danger  alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{!! Form::model($contract, ['url' => !$contract ? 'commercial/sales-contract' : 'commercial/sales-contract/' . $contract->id, 'method' => !$contract ? 'post' : 'put', 'id' => 'form']) !!}
<div class="row form-group table-responsive">
    <div class="col-md-12">
        <table class="borderLess mainForm" style="width: 100%">
            <tbody>
            <tr>
                <th><label for="beneficiary_id">Beneficiary <span
                            class="text-danger req">*</span></label></th>
                <td>{{ Form::select('beneficiary_id', $factories, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'required', 'id' => 'beneficiary_id']) }}</td>

                <th><label for="internal_file_no">Internal File No <span
                            class="text-danger req">*</span></label></th>
                <td>{!! Form::text('internal_file_no', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'required', 'id' => 'internal_file_no']) !!}</td>

                <th><label for="bank_file_no">Bank File No</label></th>
                <td>{!! Form::text('bank_file_no', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'bank_file_no']) !!}</td>
            </tr>

            <tr>
                <th><label for="year">Year <span class="text-danger req">*</span></label></th>
                <td>
                    {!! Form::text('year', $contract->year??today()->format('Y'), ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'year', 'required']) !!}
                </td>

                <th><label for="contract_number">Contract Number <span class="text-danger req">*</span></label>
                </th>
                <td>{!! Form::text('contract_number', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'required', 'id' => 'contract_number', 'placeholder' => '']) !!}</td>

                <th><label for="contract_value">Contract Value <span
                            class="text-danger req">*</span></label></th>
                <td>{!! Form::text('contract_value', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm',isset($contract->amendments_count) && $contract->amendments_count >0 ? 'readonly' : '', 'required', 'id' => 'contract_value', 'placeholder' => '']) !!}</td>
            </tr>

            <tr>
                <th><label for="currency_id">Currency</label></th>
                <td>{{ Form::select('currency_id', $currencies, $contract->currency_id ?? '2', ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'currency_id']) }}</td>

                <th><label for="contract_date">Contract Date <span
                            class="text-danger req">*</span></label></th>
                <td>{!! Form::date('contract_date', $contract->contract_date??today(), ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'required', 'id' => 'contract_date', 'placeholder' => '']) !!}</td>

                <th><label for="convertible_to">Convertible To</label></th>
                <td>{{ Form::select('convertible_to', \SkylarkSoft\GoRMG\Commercial\Options::CONVERTIBLE_TO, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'convertible_to']) }}</td>
            </tr>

            <tr>
                <th><label for="buyer">Buyer Name <span class="class-danger req"></span></label></th>
                <td>{!! Form::select('buyer_id[]', $buyers, null, ['class' => 'form-control select2-input   form-control-sm form-control form-control-sm-sm', 'id' => 'buyer', !$contract ? '' : 'disabled', 'multiple'=>'multiple']) !!}</td>

                <th><label for="applicant">Applicant Name</label></th>
                <td>{{ Form::select('applicant_id', $buyers->prepend('Select Applicant', ''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'applicant']) }}</td>

                <th><label for="notifying_party">Notifying Party</label></th>
                <td>{{ Form::select('notifying_party_id', $buyers->prepend('Select Party', ''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'notifying_party']) }}</td>
            </tr>

            <tr>
                <th><label for="consignee">Consignee</label></th>
                <td>{{ Form::select('consignee_id', $buyers->prepend('Select Consignee', ''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'consignee']) }}</td>

                <th><label for="lien_bank_id">Lien Bank</label></th>
                <td>{{ Form::select('lien_bank_id', $lien_banks->prepend("Select Bank",''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'lien_bank_id']) }}</td>

                <th><label for="lien_date">Lien Date</label></th>
                <td>{!! Form::date('lien_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'lien_date', 'placeholder' => '']) !!}</td>
            </tr>
            <tr>
                <th><label for="last_shipment_date">Last Shipment Date <span
                            class="text-danger req">*</span></label></th>
                <td>{!! Form::date('last_shipment_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'required', 'id' => 'last_shipment_date', 'placeholder' => '']) !!}</td>

                <th><label for="expiry_date">Expiry Date</label></th>
                <td>{!! Form::date('expiry_date', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'expiry_date', 'placeholder' => '']) !!}</td>

                <th><label for="tolerance_percent">Tolerance %</label></th>
                <td>{!! Form::number('tolerance_percent', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'tolerance_percent', 'placeholder' => '']) !!}</td>
            </tr>


            <tr>
                <th><label for="export_item_category">Export Item Category <span
                            class="text-danger req">*</span></label></th>
                <td>{{ Form::select('export_item_category', \SkylarkSoft\GoRMG\Commercial\Options::EXPORT_ITEM_CATEGORIES, null, ['id' => 'export_item_category', 'class' => 'form-control form-control-sm form-control form-control-sm-sm']) }}</td>

                <th><label for="hs_code">H.S Code<span
                            class="text-danger req">*</span></label></th>
                <td>{!! Form::text('hs_code', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'required', 'id' => 'hs_code', 'placeholder' => '']) !!}</td>

                <th><label for="remarks">Remarks</label></th>
                <td>{!! Form::text('remarks', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'remarks', 'placeholder' => '']) !!}</td>
            </tr>
            <tr>
                <th><label for="buying_agent_id">Buying Agent </label></th>
                <td>{{ Form::select('buying_agent_id', $buying_agents->prepend('Select Agent', ''), null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'buying_agent_id']) }}</td>

                <th><label for="primary_contract_id">Primary Contract </label></th>
                @php
                    $pcontracts = [];
                    if(!empty($primary_contracts)){
                        $pcontracts = [$primary_contracts->id => $primary_contracts->unique_id];
                    }
                 @endphp
                <td>{{ Form::select('primary_contract_id', collect($pcontracts)->prepend('Select Contract', ''), $contract->primary_contract_id??null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'primary_contract_id']) }}</td>

                <th><label for="primary_contract_value">Primary Contract Value </label></th>
                <td>{!! Form::text('primary_contract_value', $primary_contracts->contract_value??null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'primary_contract_value','readonly'=>'readonly']) !!}</td>

            </tr>
            </tbody>
        </table>
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div style="width: 1200px" class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Additional Info</h5>
                    </div>
                    <div class="modal-body">
                        <table class="reportTable mainForm">
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

                                <th><label for="contract_source">Contract Source</label></th>
                                <td>{{ Form::select('contract_source', \SkylarkSoft\GoRMG\Commercial\Options::CONTACT_SOURCES, null, ['id' => 'contract_source', 'class' => 'form-control form-control-sm form-control form-control-sm-sm']) }}</td>
                            </tr>

                            <tr>
                                <th><label for="port_of_entry">Port of Entry</label></th>
                                <td>{!! Form::text('port_of_entry', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'port_of_entry', 'placeholder' => '']) !!}</td>

                                <th><label for="port_of_loading">Port of Loading</label></th>
                                <td>{!! Form::text('port_of_loading', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'port_of_loading', 'placeholder' => '']) !!}</td>

                                <th><label for="port_of_discharge">Port of Discharge</label></th>
                                <td>{!! Form::text('port_of_discharge', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'port_of_discharge', 'placeholder' => '']) !!}</td>
                            </tr>

                            <tr>
                                <th><label for="shipping_line">Shipping Line</label></th>
                                <td>{!! Form::text('shipping_line', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'shipping_line', 'placeholder' => '']) !!}</td>

                                <th><label for="doc_present_days">Doc Present Days</label></th>
                                <td>{!! Form::number('doc_present_days', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'doc_present_days', 'placeholder' => '']) !!}</td>

                                <th><label for="claim_adjustment">Claim Adjustment</label></th>
                                <td>{!! Form::text('claim_adjustment', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm','data-parsley-type="number"', isset($contract->amendments_count) && $contract->amendments_count >0 ? 'readonly' : '','id' => 'claim_adjustment', 'placeholder' => '']) !!}</td>
                            </tr>
                            <tr>
                                <th><label for="btb_limit_percent">BTB Limit %</label></th>
                                <td>{!! Form::number('btb_limit_percent',$btb_limit_percent ?? 0, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'btb_limit_percent', 'readonly', 'placeholder' => '']) !!}</td>

                                <th><label for="foreign_comn_percent">Foreign Comn%</label></th>
                                <td>{!! Form::number('foreign_comn_percent', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'foreign_comn_percent', 'placeholder' => '']) !!}</td>

                                <th><label for="local_comn_percent">Local Comn%</label></th>
                                <td>{!! Form::number('local_comn_percent', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'local_comn_percent', 'placeholder' => '']) !!}</td>
                            </tr>

                            {{--            <tr>--}}
                            {{--                <th>Converted From</th>--}}
                            {{--                <td>--}}
                            {{--                    <button--}}
                            {{--                        data-toggle="modal"--}}
                            {{--                        data-target="#converted-form-modal"--}}
                            {{--                        type="button"--}}
                            {{--                        class="btn btn-outline btn-sm btn-block b-info text-info"--}}
                            {{--                    >--}}
                            {{--                        Browse--}}
                            {{--                    </button>--}}
                            {{--                </td>--}}

                            {{--                <th>Transferred BTB LC</th>--}}
                            {{--                <td>--}}
                            {{--                    <button--}}
                            {{--                        type="button"--}}
                            {{--                        data-toggle="modal"--}}
                            {{--                        data-target="#transferred_btb_lc_modal"--}}
                            {{--                        class="btn btn-outline btn-sm btn-block b-info text-info"--}}
                            {{--                    >--}}
                            {{--                        Browse--}}
                            {{--                    </button>--}}
                            {{--                </td>--}}

                            {{--                <th></th>--}}
                            {{--                <td></td>--}}
                            {{--            //TODO: will work later--}}
                            {{--            </tr>--}}

                            <tr>
                                <th><label for="discount_clauses">Discount Clauses</label></th>
                                <td colspan="5">{!! Form::text('discount_clauses', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'discount_clauses', 'placeholder' => '']) !!}</td>
                            </tr>

                            <tr>
                                <th><label for="bl_clause">BL Clause</label></th>
                                <td colspan="5">{!! Form::text('bl_clause', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'bl_clause', 'placeholder' => '']) !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
{{--                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
                        <button type="button" class="btn btn-primary"  data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 text-center">
        <a class="btn btn-sm btn-danger"  href="{{ url('/commercial/sales-contracts') }}">
            <i class="glyphicon glyphicon-circle-arrow-left"></i>
            Back
        </a>
        <button class="btn btn-sm btn-info"  type="button" data-toggle="modal" data-target="#exampleModalCenter">
            <i class="glyphicon glyphicon-fullscreen"></i>
            Additional Info
        </button>
        <button class="{{ $contract ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-success' }}">
            <i class="glyphicon glyphicon-floppy-open"></i>
            {{ $contract ? 'UPDATE' : 'SAVE' }}
        </button>
    </div>
</div>

{!! Form::close() !!}
