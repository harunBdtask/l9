@extends('skeleton::layout')
@section("title","Office Time Settings")
@section('styles')
    <style>
        input[type=time].form-control {
            line-height: 1.25em;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Office Time Settings</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12 col-md-12">
                        <div class="box">
                            <div class="box-header form-colors">
                                {!! Form::model($officeTimeSetting, ['url' => 'hr/office-time-settings/', 'method' => 'POST']) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_time">Worker Office Time</label>
                                            {!! Form::time('worker_office_time', $officeTimeSetting->worker_office_time ?? '', ['class' => 'form-control form-control-sm']) !!}

                                            @error('worker_office_time')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_time">Worker Late Allowed Minute</label>
                                            {!! Form::time('worker_late_allowed_minute', $officeTimeSetting->worker_late_allowed_minute ?? '', ['class' => 'form-control form-control-sm']) !!}

                                            @error('worker_late_allowed_minute')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="staff_office_time">Staff Office Time</label>
                                            {!! Form::time('staff_office_time', $officeTimeSetting->staff_office_time ?? '', ['class' => 'form-control form-control-sm']) !!}

                                            @error('staff_office_time')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_time">Staff Late Allowed Minute</label>
                                            {!! Form::time('staff_late_allowed_minute', $officeTimeSetting->staff_late_allowed_minute ?? '', ['class' => 'form-control form-control-sm']) !!}

                                            @error('staff_late_allowed_minute')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="management_office_time">Management Office Time</label>
                                            {!! Form::time('management_office_time', $officeTimeSetting->management_office_time ?? '', ['class' => 'form-control form-control-sm']) !!}

                                            @error('management_office_time')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_time">Management Late Allowed Minute</label>
                                            {!! Form::time('management_late_allowed_minute', $officeTimeSetting->management_late_allowed_minute ?? '', ['class' => 'form-control form-control-sm']) !!}

                                            @error('management_late_allowed_minute')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                                {{ 'Update'}}
                                            </button>
                                        </div>
                                    </div>
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

@push('script-head')

@endpush
