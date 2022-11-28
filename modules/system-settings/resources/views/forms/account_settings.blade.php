@extends('skeleton::layout')
@section('title', 'Account Settings')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Update Account Settings</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        @include('partials.response-message')
                        {!! Form::model($users_info, ['url' => 'account-system-settings/'.$users_info->id, 'method' => 'PUT', 'files' => true]) !!}
                        <div class="row m-b">
                            <div class="col-sm-6">
                                <label for="first_name">First Name</label>
                                {!! Form::text('first_name', null, ['class' => 'form-control form-control-sm', 'id' => 'first_name', 'placeholder' => 'Write first name here']) !!}
                                @if($errors->has('first_name'))
                                    <p class="help-block text-danger"> {{ $errors->first('first_name') }} </p>
                                @endif

                            </div>
                            <div class="col-sm-6">
                                <label for="last_name">Last Name</label>
                                {!! Form::text('last_name', null, ['class' => 'form-control form-control-sm', 'id' => 'last_name', 'placeholder' => 'Write last name here']) !!}
                                @if($errors->has('last_name'))
                                    <p class="help-block text-danger"> {{ $errors->first('last_name') }} </p>
                                @endif
                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-sm-6">
                                <label for="email">Email</label>
                                {!! Form::email('email', null, ['class' => 'form-control form-control-sm', 'id' => 'email', 'placeholder' => 'Write user\'s email here']) !!}
                                @if($errors->has('email'))
                                    <p class="help-block text-danger"> {{ $errors->first('email') }} </p>
                                @endif

                            </div>
                            <div class="col-sm-6">
                                <label for="phone_no">Phone number</label>
                                {!! Form::text('phone_no', null, ['class' => 'form-control form-control-sm', 'id' => 'phone_no', 'placeholder' => 'Write user phone no here']) !!}
                                @if($errors->has('phone_no'))
                                    <p class=" help-block text-danger">{{ $errors->first('phone_no') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-sm-6">
                                <label for="designation">Designation</label>
                                {!! Form::text('designation', null, ['class' => 'form-control form-control-sm', 'id' => 'designation', 'placeholder' => 'Write user designation here']) !!}
                            </div>
                            <div class="col-sm-6">
                                <label>Profile Image</label>
                                {!! Form::file('profile_image',['class' => 'form-control form-control-sm' ]) !!}
                                @if($errors->has('profile_image'))
                                    <p class="help text-danger">{{ $errors->first('profile_image') }}</p>
                                @endif

                            </div>

                        </div>

                        <div class="row m-b">
                            <div class="col-sm-6">
                                <label for="acc_department_id"> Department (Accounting)</label>
                                {!! Form::select('acc_department_id', $acc_departments, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'acc_department_id']) !!}
                            </div>
                            <div class="col-sm-6">
                                <label for="signature">Signature</label>
                                <input type="file" name="signature" class="form-control" accept="image/jpeg , image/jpg, image/gif, image/png">
                                @if($errors->has('signature'))
                                    <p class="help text-danger">{{ $errors->first('signature') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row m-b">

                            <div class="col-sm-6">
                                <label for="address">Address</label>
                                {!! Form::textarea('address', null, ['rows' => '2','class' => 'form-control form-control-sm', 'id' => 'address', 'placeholder' => 'Write user address here']) !!}
                            </div>

                        </div>
                        <button type="submit" class="btn btn-sm white">Submit</button>
                        <button type="button" class="btn btn-sm btn-dark"><a href="{{ url('/') }}">Cancel</a></button>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
