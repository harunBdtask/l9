@extends('skeleton::layout')
@section('title', 'Other Factories')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $print_factory ? 'Update Print/Wash/Knitting Factory' : 'New Print/Wash/Knitting Factory' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($print_factory, ['url' => $print_factory ? 'others-factories/'.$print_factory->id : 'others-factories', 'method' => $print_factory ? 'PUT' : 'POST']) !!}

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="name">Factory Type</label>
                                    {!! Form::select('factory_type', ['print' => 'Print Factory', 'wash' => 'Wash Factory', 'knitting' => 'Knitting Factory'],null, ['class' => 'form-control form-control-sm', 'id' => 'factory_type', 'placeholder' => 'Select factory type']) !!}

                                    @if($errors->has('factory_type'))
                                        <span class="text-danger">{{ $errors->first('factory_type') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="name">Factory Name</label>
                                    {!! Form::text('factory_name', null, ['class' => 'form-control form-control-sm', 'id' => 'factory_name', 'placeholder' => 'Write factory name here']) !!}

                                    @if($errors->has('factory_name'))
                                        <span class="text-danger">{{ $errors->first('factory_name') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="name">Factory Short
                                        Name</label>
                                    {!! Form::text('factory_short_name', null, ['class' => 'form-control form-control-sm', 'id' => 'factory_name', 'placeholder' => 'Write factory short name here']) !!}

                                    @if($errors->has('factory_short_name'))
                                        <span class="text-danger">{{ $errors->first('factory_short_name') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="name">Factory
                                        Address</label>
                                    {!! Form::textarea('factory_address', null, ['class' => 'form-control form-control-sm', 'rows' => 2, 'id' => 'factory_address']) !!}

                                    @if($errors->has('factory_address'))
                                        <span class="text-danger">{{ $errors->first('factory_address') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="name">Phone No.</label>
                                    {!! Form::text('phone_no', null, ['class' => 'form-control form-control-sm', 'id' => 'phone_no', 'placeholder' => 'Write phone no here']) !!}

                                    @if($errors->has('phone_no'))
                                        <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-4">

                                <div class="form-group">
                                    <label for="name">Responsible
                                        Person</label>
                                    {!! Form::text('responsible_person', null, ['class' => 'form-control form-control-sm', 'id' => 'responsible_person', 'placeholder' => 'Write responsible person name here']) !!}

                                    @if($errors->has('responsible_person'))
                                        <span class="text-danger">{{ $errors->first('responsible_person') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-12">
                                <div class="form-group m-t-md">
                                    <button type="submit"
                                            class="btn btn-sm white">{{ $print_factory ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-sm btn-dark" href="{{ url('others-factories') }}">Cancel</a>
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
