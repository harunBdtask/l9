@extends('skeleton::layout')
@section('title', 'Supplier')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $supplier ? 'Update Supplier' : 'New Supplier' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($supplier, ['url' => $supplier ? 'suppliers/'.$supplier->id : 'suppliers', 'method' => $supplier ? 'PUT' : 'POST', 'files' => true]) !!}
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="name">
                                        Name
                                        <small class="label bg-danger">Required</small>
                                    </label>
                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Supplier Name']) !!}
                                    @if($errors->has('name'))
                                        <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="short_name">
                                        Short Name
                                        <small class="label bg-danger">Required</small>
                                    </label>
                                    {!! Form::text('short_name', null, ['class' => 'form-control form-control-sm', 'id' => 'short_name', 'placeholder' => 'Short Name']) !!}
                                    @if($errors->has('short_name'))
                                        <small class="text-danger">{{ $errors->first('short_name') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="contact_person">Contact Person</label>
                                    {!! Form::text('contact_person', null, ['class' => 'form-control form-control-sm', 'id' => 'contact_person', 'placeholder' => 'Contact Person']) !!}

                                    @if($errors->has('contact_person'))
                                        <small class="text-danger">{{ $errors->first('contact_person') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="contact_no">Contact No</label>
                                    {!! Form::text('contact_no', null, ['class' => 'form-control form-control-sm', 'id' => 'contact_no', 'placeholder' => 'Contact No']) !!}
                                    @if($errors->has('contact_no'))
                                        <small class="text-danger">{{ $errors->first('contact_no') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="designation">Designation</label>
                                    {!! Form::text('designation', null, ['class' => 'form-control form-control-sm', 'id' => 'designation', 'placeholder' => 'Designation']) !!}
                                    @if($errors->has('designation'))
                                        <small class="text-danger">{{ $errors->first('designation') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="exporters_ref">Exporters Ref:</label>
                                    {!! Form::text('exporters_ref', null, ['class' => 'form-control form-control-sm', 'id' => 'exporters_ref', 'placeholder' => 'Exporters Reference']) !!}
                                    @if($errors->has('exporters_ref'))
                                        <small class="text-danger">{{ $errors->first('exporters_ref') }}</small>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    {!! Form::text('email', null, ['class' => 'form-control form-control-sm', 'id' => 'email', 'placeholder' => 'Email']) !!}
                                    @if($errors->has('email'))
                                        <small class="text-danger">{{ $errors->first('email') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="web_address">WEB Address</label>
                                    {!! Form::text('web_address', null, ['class' => 'form-control form-control-sm', 'id' => 'web_address', 'placeholder' => 'WEB Address']) !!}
                                    @if($errors->has('web_address'))
                                        <small class="text-danger">{{ $errors->first('web_address') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="country_id">Country</label>
                                    {!! Form::select('country_id', $countries, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'country_id', 'placeholder' => 'Select Country']) !!}
                                    @if($errors->has('country_id'))
                                        <span class="text-danger">{{ $errors->first('country_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="party_type">
                                        Party Type
                                        <small class="label bg-danger">Required</small>
                                    </label>
                                    {!! Form::select('party_type[]', $parties, $supplier ? explode(",", $supplier->party_type) : null , ['class' => 'form-control form-control-sm select2-input c-select form-control form-control-sm-sm', 'id' => 'party_type', 'multiple' => 'multiple']) !!}
                                    @if($errors->has('party_type'))
                                        <span class="text-danger">{{ $errors->first('party_type') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="factory_id">
                                        Company
                                        <small class="label bg-danger">Required</small>
                                    </label>
                                    {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'factory_id', 'placeholder' => 'Select Company']) !!}
                                    @if($errors->has('factory_id'))
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="buyer_id">
                                        Tag Buyers
                                        <small class="label bg-danger">Required</small>
                                    </label>
                                    <?php
                                        $buyers= collect($buyers)->prepend('All Buyer', 'all_buyer');
                                    ?>
                                    {!! Form::select('buyer_id[]', $buyers,  $supplier ? explode(",", $supplier->buyer_id) : null  , ['class' => 'form-control form-control-sm select2-input c-select form-control form-control-sm-sm', 'id' => 'buyer_id', 'multiple' => 'multiple']) !!}
                                    @if($errors->has('buyer_id'))
                                        <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="day_credit_limit">Credit Limit (Days)</label>
                                    {!! Form::number('day_credit_limit', null, ['class' => 'form-control form-control-sm', 'id' => 'day_credit_limit', 'placeholder' => 'Credit Limit (Days)']) !!}
                                    @if($errors->has('day_credit_limit'))
                                        <small class="text-danger">{{ $errors->first('day_credit_limit') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="amount_credit_limit">Credit Limit (Amount)</label>
                                    {!! Form::number('amount_credit_limit', null, ['class' => 'form-control form-control-sm', 'id' => 'amount_credit_limit', 'placeholder' => 'Credit Limit (Amount)']) !!}
                                    @if($errors->has('amount_credit_limit'))
                                        <small class="text-danger">{{ $errors->first('amount_credit_limit') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="currency_id">Currency</label>
                                    {!! Form::select('currency_id', $currencies, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'currency_id', 'placeholder' => 'Select Currency']) !!}
                                    @if($errors->has('currency_id'))
                                        <span class="text-danger">{{ $errors->first('currency_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="discount_method">Discount Method</label>
                                    {!! Form::select('discount_method', $currencies, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'discount_method', 'placeholder' => 'Select Discount']) !!}
                                    @if($errors->has('discount_method'))
                                        <span class="text-danger">{{ $errors->first('discount_method') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="security_deducted">Security Deducted</label>
                                    {!! Form::select('security_deducted', ['Yes'=>'Yes', 'No' => 'No'], null, ['class' => 'form-control form-control-sm c-select', 'id' => 'security_deducted ', 'placeholder' => 'Security Deducted']) !!}
                                    @if($errors->has('security_deducted'))
                                        <span class="text-danger">{{ $errors->first('security_deducted') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="ait_deducted">AIT Deducted</label>
                                    {!! Form::select('ait_deducted', ['Yes'=>'Yes', 'No' => 'No'], null, ['class' => 'form-control form-control-sm c-select', 'id' => 'ait_deducted', 'placeholder' => 'AIT Deducted']) !!}
                                    @if($errors->has('ait_deducted'))
                                        <span class="text-danger">{{ $errors->first('ait_deducted') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="sewing_efficiency_marketing">Sewing Efficiency Marketing %</label>
                                    {!! Form::number('sewing_efficiency_marketing', null, ['class' => 'form-control form-control-sm', 'id' => 'sewing_efficiency_marketing', 'placeholder' => 'Sewing Efficiency Marketing %']) !!}
                                    @if($errors->has('sewing_efficiency_marketing'))
                                        <span class="text-danger">{{ $errors->first('sewing_efficiency_marketing') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="sewing_efficiency_planing">Sewing Efficiency Planing %</label>
                                    {!! Form::number('sewing_efficiency_planing', null, ['class' => 'form-control form-control-sm', 'id' => 'sewing_efficiency_planing', 'placeholder' => 'Sewing Efficiency Planing %']) !!}
                                    @if($errors->has('sewing_efficiency_planing'))
                                        <span class="text-danger">{{ $errors->first('sewing_efficiency_planing') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="ait_deducted">Team</label>
                                    {!! Form::select('team_name', $teams, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'team_name', 'placeholder' => 'Select Team']) !!}
                                    @if($errors->has('team_name'))
                                        <span class="text-danger">{{ $errors->first('team_name') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    {!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], null, ['class' => 'form-control form-control-sm c-select', 'id' => 'status']) !!}
                                    @if($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="buyer_code">Buyer Code</label>
                                    {!! Form::text('buyer_code', null, ['class' => 'form-control form-control-sm', 'id' => 'buyer_code', 'placeholder' => 'Buyer Code']) !!}
                                    @if($errors->has('buyer_code'))
                                        <span class="text-danger">{{ $errors->first('buyer_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    {!! Form::text('remarks', null, ['class' => 'form-control form-control-sm', 'id' => 'remarks', 'placeholder' => 'Remarks']) !!}
                                    @if($errors->has('remarks'))
                                        <span class="text-danger">{{ $errors->first('remarks') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    {!! Form::file('photo', ['class' => 'form-control form-control-sm', 'id' => 'photo']) !!}
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="associate_with">Associate With</label>
                                    {!! Form::select('associate_with[]', $factories, $associateWith ?? [], ['class' => 'form-control form-control-sm select2-input c-select form-control form-control-sm-sm', 'id' => 'associate_with', 'multiple' => 'multiple']) !!}
                                    @if($errors->has('associate_with'))
                                        <span class="text-danger">{{ $errors->first('associate_with') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="associate_with">Control Ledger</label>
                                    @if (!empty($supplier->ledger_account_id))
                                        {!! Form::select('control_ledger_id', $controlAccounts ?? [], null, [ ($supplier->ledger_account_id ? 'disabled' : ''), 'class' => 'form-control form-control-sm select2-input c-select form-control form-control-sm-sm', 'id' => 'control_ledger_id', 'placeholder' => 'Select Control Ledger']) !!}
                                        <input type="hidden" name="control_ledger_id" value="{{$supplier->control_ledger_id}}">
                                    @else
                                        {!! Form::select('control_ledger_id', $controlAccounts ?? [], null, [ 'class' => 'form-control form-control-sm select2-input c-select form-control form-control-sm-sm', 'id' => 'control_ledger_id', 'placeholder' => 'Select Control Ledger']) !!}
                                    @endif
                                    @if($errors->has('control_ledger_id'))
                                        <span class="text-danger">{{ $errors->first('control_ledger_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="vds_rate">VDS Rate</label>
                                    {!! Form::number('vds_rate', null, ['class' => 'form-control form-control-sm', 'id' => 'vds_rate', 'step'=>0.01, 'placeholder' => 'VDS Rate']) !!}

                                    @if($errors->has('vds_rate'))
                                        <small class="text-danger">{{ $errors->first('vds_rate') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="tds_rate">TDS Rate</label>
                                    {!! Form::number('tds_rate', null, ['class' => 'form-control form-control-sm', 'id' => 'tds_rate', 'step'=>0.01, 'placeholder' => 'VDS Rate']) !!}

                                    @if($errors->has('tds_rate'))
                                        <small class="text-danger">{{ $errors->first('tds_rate') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="buyer_code">Address 1</label>
                                    {!! Form::textarea('address_1', null, ['class' => 'form-control form-control-sm', 'id' => 'address_1', 'placeholder' => 'Address 1', 'rows' => 3]) !!}
                                    @if($errors->has('address_1'))
                                        <span class="text-danger">{{ $errors->first('address_1') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="buyer_code">Address 2</label>
                                    {!! Form::textarea('address_2', null, ['class' => 'form-control form-control-sm', 'id' => 'address_2', 'placeholder' => 'Address 2', 'rows' => 3]) !!}
                                    @if($errors->has('address_2'))
                                        <span class="text-danger">{{ $errors->first('address_2') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm white"><i class="fa fa-save"></i> {{ $supplier ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-sm btn-dark" href="{{ url('suppliers') }}"><i class="fa fa-remove"></i> Cancel</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
