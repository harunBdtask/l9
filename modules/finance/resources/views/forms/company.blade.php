@extends('finance::layout')

@section('title', ($company ? 'Update Company' : 'New Company'))
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $company ? 'Update Company' : 'New Company' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                {!! Form::model($company, ['url' => $company ? 'finance/ac-companies/'.$company->id : 'finance/ac-companies', 'method' => $company ? 'PUT' : 'POST', 'files' => true]) !!}
                                <div class="form-group">
                                    <label for="name">Company Name</label>
                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write company\'s name here']) !!}

                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="group_name">Group Name</label>
                                    {!! Form::text('group_name', null, ['class' => 'form-control form-control-sm', 'id' => 'group_name', 'placeholder' => 'Write Group\'s name here']) !!}

                                    @if($errors->has('group_name'))
                                        <span class="text-danger">{{ $errors->first('group_name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="corporate_address">Corporate Address</label>
                                    {!! Form::text('corporate_address', null, ['class' => 'form-control form-control-sm', 'id' => 'corporate_address', 'placeholder' => 'Write company\'s corporate address here']) !!}

                                    @if($errors->has('corporate_address'))
                                        <span class="text-danger">{{ $errors->first('corporate_address') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="factory_address">Factory Address</label>
                                    {!! Form::text('factory_address', null, ['class' => 'form-control form-control-sm', 'id' => 'factory_address', 'placeholder' => 'Write company\'s factory address here']) !!}

                                    @if($errors->has('factory_address'))
                                        <span class="text-danger">{{ $errors->first('factory_address') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="tin">TIN</label>
                                    {!! Form::text('tin', null, ['class' => 'form-control form-control-sm', 'id' => 'tin', 'placeholder' => 'Write company\'s TIN here']) !!}

                                    @if($errors->has('tin'))
                                        <span class="text-danger">{{ $errors->first('tin') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    {!! Form::text('country', null, ['class' => 'form-control form-control-sm', 'id' => 'country', 'placeholder' => 'Write company\'s country here']) !!}

                                    @if($errors->has('country'))
                                        <span class="text-danger">{{ $errors->first('country') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="phone_no">Phone No</label>
                                    {!! Form::text('phone_no', null, ['class' => 'form-control form-control-sm', 'id' => 'phone_no', 'placeholder' => 'Write company\'s phone no here']) !!}

                                    @if($errors->has('phone_no'))
                                        <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    {!! Form::text('email', null, ['class' => 'form-control form-control-sm', 'id' => 'email', 'placeholder' => 'Write company\'s email here']) !!}

                                    @if($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group m-t-md">
                                    <button type="submit" class="btn white">{{ $company ? 'Update' : 'Create' }}</button>
                                    <a class="btn white" href="{{ url('finance/ac-companies') }}">Cancel</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
