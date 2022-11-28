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
            <div class="col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                <div class="box">
                    <div class="box-header custom-color">
                        <h2>Third Party Vehicle Registration</h2>
                        <small>Register Third Party Vehicle Information</small>
                    </div>

                    <div class="box-body">
                        {!! Form::open(['route'=>['third.vehicle.store',request()->segment(2) ? request()->segment(2) : null ] ,'method'=>'post','class'=>'form-control']) !!}
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('vehicle_name','Vehicle Name') !!}
                                {!! Form::text('vehicle_name',$third_vehicle->vehicle_name ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Name','autocomplete'=>'off']) !!}
                                @if($errors->has('vehicle_name'))
                                    <span class="help-block text-danger">*{{ $errors->first('vehicle_name')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('vehicle_registration','Vehicle Registration') !!}
                                {!! Form::text('vehicle_registration',$third_vehicle->vehicle_registration ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Registration Number','autocomplete'=>'off']) !!}
                                @if($errors->has('vehicle_registration'))
                                    <span class="help-block text-danger">*{{ $errors->first('vehicle_registration')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('driver_name','Driver Name') !!}
                                {!! Form::text('driver_name',$third_vehicle->driver_name ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Driver Name','autocomplete'=>'off']) !!}
                                @if($errors->has('driver_name'))
                                    <span class="help-block text-danger">*{{ $errors->first('driver_name')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('driver_license','Driver License') !!}
                                {!! Form::text('driver_license',$third_vehicle->driver_license ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Driver License No','autocomplete'=>'off']) !!}
                                @if($errors->has('driver_license'))
                                    <span class="help-block text-danger">*{{ $errors->first('driver_license')}}</span>
                                @endif

                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('purpose','Purpose') !!}
                                {!! Form::text('purpose',$third_vehicle->purpose ?? '',['class'=>'form-control','placeholder'=>'Enter Vehicle Purpose','autocomplete'=>'off']) !!}
                                @if($errors->has('purpose'))
                                    <span class="help-block text-danger">*{{ $errors->first('purpose')}}</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-a text-right">
                            {!! Form::submit('Submit',['class'=>'btn dark']) !!}
                        </div>

                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                <div class="box">
                    <div class="box-header custom-color">
                        <h2>Third party Vehicle Details</h2>
                        <small>Third party Vehicle information</small>
                    </div>
                    <div class="box-body">

                        <p class="data-count print-delete">{{ $third_vehicles->firstItem() }}
                            to {{ $third_vehicles->lastItem() }} of total {{ $third_vehicles->total()}} entries</p>
                        <div class="table-responsive" style="margin-top: 20px; min-height: 225px;">
                            <table class="reportTable">
                                <thead>
                                <tr style="background-color:#039BE5;color: #FFFFFF">
                                    <th>Driver Name</th>
                                    <th>Driver License</th>
                                    <th>Vehicle Name</th>
                                    <th>Vehicle Registration</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(count($third_vehicles)>0)
                                    @foreach($third_vehicles as $vehicle)
                                        <tr>
                                            <td>{{ $vehicle->driver_name }}</td>
                                            <td>{{ $vehicle->driver_license }}</td>
                                            <td>{{ $vehicle->vehicle_name}}</td>
                                            <td>{{ $vehicle->vehicle_registration}}</td>
                                            <td>{{ $vehicle->purpose }}</td>
                                            <td class="{{!$vehicle->status ? 'red': 'green'}} text-white">
                                                <b> {{ $vehicle->status == false ? 'Check Out' : 'Check In' }} </b></td>
                                            <td>
                                                <div class="dropdown inline">
                                                    <button class="btn btn-xs white dropdown-toggle"
                                                            data-toggle="dropdown"
                                                            aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu pull-right">

                                                        @if($vehicle->status)
                                                            <a class="dropdown-item"
                                                               href="{{ route('third.vehicle.edit',['id'=>$vehicle->id]) }}">Edit</a>
                                                            <a class="dropdown-item"
                                                               href="{{ route('third.vehicle.status',['id'=>$vehicle->id]) }}">Check
                                                                out</a>
                                                            <div class="dropdown-divider"></div>
                                                        @endif
                                                        <a class="dropdown-item show-modal white"
                                                           data-toggle="modal"
                                                           data-target="#confirmationModal"
                                                           ui-toggle-class="flip-x" ui-target="#animate"
                                                           data-url="{{ route('third.vehicle.delete',['id'=>$vehicle->id]) }}">Delete
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
                        <div class="text-center print-delete">{{$third_vehicles->links() }}</div>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
