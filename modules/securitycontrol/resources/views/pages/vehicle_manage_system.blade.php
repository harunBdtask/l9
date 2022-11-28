@extends('skeleton::layout')
@push('style')
    <style>
        .data-count {
            padding: 0px;
            text-align: right;
            font-size: 14px;
        }

        select {
            min-height: 2.375rem !important;
        }

        .select2-container--default .select2-selection--single {
            height: 2.375rem !important;
            border-radius: 0px !important;
            border-color: rgba(120, 130, 140, 0.2) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2.375rem !important;
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 0px !important;
        }

        .pagination .page-item.active, .pagination > .active > a, .pagination > .active > span, .pager .page-item.active, .pager > .active > a, .pager > .active > span {
            color: white !important;
            background-color: #0089BC !important;
            border-color: #0089BC !important;
        }

        .custom-color {
            color: white;
            background-color: #0089BC;

        }

        .reportTable > thead > tr {
            color: white !important;
            background-color: #0089BC !important;
        }
    </style>

@endpush
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 employee_info">
                <div class="box">
                    <div class="box-header custom-color">
                        <h2>Vehicle Registration</h2>
                        <small>Register Vehicle Information</small>
                    </div>
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert' . $msg) }}</p>
                            @endif
                        @endforeach
                    </div>
                    <div class="box-body">
                        {!! Form::open(['route'=>['vehicle.store',request()->segment(2) ? request()->segment(2) : null ] ,'method'=>'post','class'=>'form-control']) !!}
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('vehicle_name','Name') !!}
                                {!! Form::text('vehicle_name',$vehicle_edit->vehicle_name ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Name','autocomplete'=>'off']) !!}
                                @if($errors->has('vehicle_name'))
                                    <span class="help-block text-danger">*{{ $errors->first('vehicle_name')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('vehicle_registration','Registration No') !!}
                                {!! Form::text('vehicle_registration',$vehicle_edit->vehicle_registration ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Registration Number','autocomplete'=>'off']) !!}
                                @if($errors->has('vehicle_registration'))
                                    <span class="help-block text-danger">*{{ $errors->first('vehicle_registration')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('vehicle_engine','Engine No') !!}
                                {!! Form::text('vehicle_engine',$vehicle_edit->vehicle_engine ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Engine Number','autocomplete'=>'off']) !!}
                                @if($errors->has('vehicle_engine'))
                                    <span class="help-block text-danger">*{{ $errors->first('vehicle_engine')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('vehicle_chassis','Chassis No') !!}
                                {!! Form::text('vehicle_chassis',$vehicle_edit->vehicle_chassis ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Chassis Number','autocomplete'=>'off']) !!}
                                @if($errors->has('vehicle_chassis'))
                                    <span class="help-block text-danger">*{{ $errors->first('vehicle_chassis')}}</span>
                                @endif

                            </div>
                        </div>

                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('vehicle_type','Vehicle') !!}
                                {!! Form::select('vehicle_type', VEHICLE_TYPE,$vehicle_edit->vehicle_type ?? '',['class'=>'form-control select2-input','placeholder'=>'Select Vehicle Type'] ) !!}
                                @if($errors->has('vehicle_type'))
                                    <span class="help-block text-danger">*{{ $errors->first('vehicle_type')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('vehicle_model','Vehicle Model') !!}
                                {!! Form::text('vehicle_model',$vehicle_edit->vehicle_model ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Model','autocomplete'=>'off']) !!}
                                @if($errors->has('vehicle_model'))
                                    <span class="help-block text-danger">*{{ $errors->first('vehicle_model')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-a text-right">
                            @if(request()->segment(1) == 'driver-edit')
                            @else
                                {!! Form::submit('Submit',['class'=>'btn dark vehicle-btn']) !!}
                                {!! Form::button('Back',['class'=>'btn red back-vehicle']) !!}
                            @endif
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 employee_details">
                <div class="box">
                    <div class="box-header custom-color">
                        <h2>Vehicle Details</h2>
                        <small>Vehicle information to vehicle management system</small>
                    </div>
                    <div class="box-body">

                        <p class="data-count print-delete">{{ $vehicles->firstItem() }}
                            to {{ $vehicles->lastItem() }} of total {{$vehicles->total()}} entries</p>
                        <div class="table-responsive" style="margin-top: 20px; min-height: 225px;">
                            <table class="reportTable">
                                <thead>
                                <tr style="background-color:#039BE5;color: #FFFFFF">
                                    <th>Name</th>
                                    <th>Registration</th>
                                    <th>Engine</th>
                                    <th>Chassis No</th>
                                    <th>Type</th>
                                    <th>Model</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(count($vehicles)>0)
                                    @foreach($vehicles as $vehicle)
                                        <tr>
                                            <td>{{ $vehicle->vehicle_name }}</td>
                                            <td>{{ $vehicle->vehicle_registration }}</td>
                                            <td>{{ $vehicle->vehicle_engine }}</td>
                                            <td>{{ $vehicle->vehicle_chassis }}</td>
                                            <td>{{VEHICLE_TYPE[$vehicle->vehicle_type] }}</td>
                                            <td>{{ $vehicle->vehicle_model }}</td>
                                            <td>
                                                <div class="dropdown inline">
                                                    <button class="btn btn-xs white dropdown-toggle"
                                                            data-toggle="dropdown"
                                                            aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu pull-right">
                                                        <a class="dropdown-item"
                                                           href="{{route('vehicle.edit',['id'=> $vehicle->id])}}">Edit</a>
                                                        <div class="dropdown-divider"></div>

                                                        <a class="dropdown-item show-modal white"
                                                           data-toggle="modal"
                                                           data-target="#confirmationModal"
                                                           ui-toggle-class="flip-x" ui-target="#animate"
                                                           data-url="{{ route('vehicle.delete',['id'=>$vehicle->id]) }}">Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7"><b>No data found</b></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        <div class="text-center print-delete">{{$vehicles->links() }}</div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 employee_info">
                <div class="box">
                    <div class="box-header custom-color">
                        <h2>Driver Registration</h2>
                        <small>Register Driver Information</small>
                    </div>

                    <div class="box-body">
                        {!! Form::open(['route'=>['driver.store',request()->segment(2) ? request()->segment(2) : null ] ,'method'=>'post','class'=>'form-control']) !!}
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('name','Driver Name') !!}
                                {!! Form::text('name',$drive_edit->name ?? '',['class'=>'form-control','placeholder'=>'Enter Driver Name','autocomplete'=>'off']) !!}
                                @if($errors->has('name'))
                                    <span class="help-block text-danger">*{{ $errors->first('name')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('license_no','License No') !!}
                                {!! Form::text('license_no',$drive_edit->license_no ?? '',['class'=>'form-control','placeholder'=>'Enter Driver License Number','autocomplete'=>'off']) !!}
                                @if($errors->has('license_no'))
                                    <span class="help-block text-danger">*{{ $errors->first('license_no')}}</span>
                                @endif

                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-sm-12">
                                {!! Form::label('address','Address') !!}
                                {!! Form::textarea('address',$drive_edit->address ?? '',['class'=>'form-control','placeholder'=>'Enter Driver Address','rows'=>4,'cols'=>3,'style' => 'resize:none','autocomplete'=>'off']) !!}
                                @if($errors->has('address'))
                                    <span class="help-block text-danger">*{{ $errors->first('address')}}</span>
                                @endif
                            </div>
                            {{--                            <div class="col-sm-6">--}}
                            {{--                                {!! Form::label('image','Upload driver image') !!}--}}
                            {{--                                {!! Form::file('image') !!}--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="p-a text-right">
                            @if(request()->segment(1) == 'vehicle-edit')
                            @else
                                {!! Form::submit('Submit',['class'=>'btn dark','id'=>'driver-btn']) !!}
                                {!! Form::button('Back',['class'=>'btn red back-driver']) !!}
                            @endif

                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 employee_details">
                <div class="box">
                    <div class="box-header custom-color">
                        <h2>Driver Details</h2>
                        <small>Add Driver information</small>
                    </div>
                    <div class="box-body">
                        <p class="data-count print-delete">{{ $drivers->firstItem() }}
                            to {{ $drivers->lastItem() }} of total {{ $drivers->total()}} entries</p>
                        <div class="table-responsive" style="margin-top: 20px; min-height:225px">
                            <table class="reportTable">
                                <thead>
                                <tr style="background-color:#039BE5;color: #FFFFFF">
                                    <th>Name</th>
                                    <th>License</th>
                                    <th>Address</th>
                                    {{--                                <th>Image</th>--}}
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($drivers)>0)
                                    @foreach($drivers as $driver)
                                        <tr>
                                            <td>{{ $driver->name }}</td>
                                            <td>{{ $driver->license_no }}</td>
                                            <td>{{ str_limit( $driver->address,10) }}</td>
                                            <td>
                                                <div class="dropdown inline">
                                                    <button class="btn btn-xs white dropdown-toggle"
                                                            data-toggle="dropdown"
                                                            aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu pull-right">
                                                        <a class="dropdown-item"
                                                           href="{{ route('driver.edit',['id'=>$driver->id]) }}">Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item show-modal white"
                                                           data-toggle="modal"
                                                           data-target="#confirmationModal"
                                                           ui-toggle-class="flip-x" ui-target="#animate"
                                                           data-url="{{ route('driver.delete',['id'=>$driver->id]) }}">Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4"><b>No data found</b></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        <div class="text-center print-delete">{{$drivers->links() }}</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script-head')
    <script>
        $(document).ready(function () {
            let segments = location.pathname.split('/');
            if (segments[1] === 'vehicle-system') {
                $('.back-vehicle').text('Cancel');
                $('.back-driver').text('Cancel');
                $('.back-vehicle,.back-driver').click(function () {
                    location.reload();
                });

            } else if(segments[1] === 'vehicle-edit') {
                $('.vehicle-btn').text('Update');
                $('.back-vehicle').text('Back');
                $('.back-vehicle').click(function () {
                    window.history.back();
                });


            }else if(segments[1] === 'driver-edit'){
                $('#driver-btn').text('Update');
                $('.back-driver').text('Back');
                $('.back-driver').click(function () {
                    window.history.back();
                });


            }

        });
    </script>
@endpush
