@extends('skeleton::layout')
@section('title','Factory Profile')
@section('content')
    <div class="padding">
        <div class="col-md-12">
            <div class="box" >
                <div class="box-header">
                    <h2>{{ $profile ? 'Update Subcontract Factory' : 'New Subcontract Factory' }}</h2>
                    <div class="clearfix"></div>
                </div>
                @include('partials.response-message')
                <div class="box-body b-t">
                    {!! Form::model($profile, ['url' => $profile ? 'subcontract-factory-profile/'.$profile->id : 'subcontract-factory-profile', 'method' => $profile ? 'PUT' : 'POST']) !!}
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="operation_type">Operation Type</label>
                            {!! Form::select('operation_type', $types, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'operation_type', 'required' => true]) !!}
                            @if($errors->has('operation_type'))
                                <span class="text-danger">{{ $errors->first('operation_type') }}</span>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <label for="name">Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Name', 'required' => true]) !!}
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <label for="short_name">Short Name</label>
                            {!! Form::text('short_name', null, ['class' => 'form-control form-control-sm', 'id' => 'short_name', 'placeholder' => 'Short Name']) !!}
                            @if($errors->has('short_name'))
                                <span class="text-danger">{{ $errors->first('short_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-t">
                        <div class="col-sm-4">
                            <label for="address">Address</label>
                            {!! Form::text('address', null, ['class' => 'form-control form-control-sm', 'id' => 'address', 'placeholder' => 'Address', 'required' => true]) !!}
                            @if($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <label for="responsible_person">Responsible Person</label>
                            {!! Form::text('responsible_person', null, ['class' => 'form-control form-control-sm', 'id' => 'responsible_person', 'placeholder' => 'Responsible Person', 'required' => true]) !!}
                            @if($errors->has('responsible_person'))
                                <span class="text-danger">{{ $errors->first('responsible_person') }}</span>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <label for="email">Email</label>
                            {!! Form::email('email', null, ['class' => 'form-control form-control-sm', 'id' => 'email', 'placeholder' => 'Email']) !!}
                            @if($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-t">
                        <div class="col-sm-4">
                            <label for="contact_no">Contract No</label>
                            {!! Form::number('contact_no', null, ['class' => 'form-control form-control-sm', 'id' => 'contact_no', 'placeholder' => 'Contract No', 'required' => true]) !!}
                            @if($errors->has('contact_no'))
                                <span class="text-danger">{{ $errors->first('contact_no') }}</span>
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <label for="remarks">Remarks</label>
                            {!! Form::text('remarks', null, ['class' => 'form-control form-control-sm', 'id' => 'remarks', 'placeholder' => 'Remarks']) !!}
                            @if($errors->has('remarks'))
                                <span class="text-danger">{{ $errors->first('remarks') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-t">
                        <div class="text-center">
                            <button type="submit" class="{{ $profile ? 'btn btn-primary' : 'btn btn-success' }}"><i class="fa fa-save"></i> {{ $profile ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-danger" href="{{ url('subcontract-factory-profile') }}"><i class="fa fa-remove"></i> Cancel</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
