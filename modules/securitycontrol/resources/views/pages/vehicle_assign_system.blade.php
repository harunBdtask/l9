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
            <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 ">
                <div class="box">
                    <div class="box-header custom-color">
                        <h2>Vehicle Assign</h2>
                        <small>Vehicle Assign to the Driver</small>
                    </div>
                    <div class="box-body">
                        {!! Form::open(['route'=>'vehicle-assign.store','method'=>'post','class'=>'form-control','autocomplete'=>'off']) !!}
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('vehicle','Vehicle') !!}
                                {!! Form::select('vehicle', $vehicles , null,['class'=>'form-control select2-input','placeholder'=>'Select Vehicle'] ) !!}
                                @if($errors->has('vehicle'))
                                    <span class="help-block text-danger">*{{ $errors->first('vehicle')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('driver','Driver') !!}
                                {!! Form::select('driver', $drivers , null,['class'=>'form-control select2-input','placeholder'=>'Select Driver'] ) !!}
                                @if($errors->has('driver'))
                                    <span class="help-block text-danger">*{{ $errors->first('driver')}}</span>
                                @endif

                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('from','From') !!}
                                {!! Form::text('from',null,['class'=>'form-control','placeholder'=>'Double Click to select from (Auto fill)','autocomplete'=>'off','id'=>'from-id']) !!}
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('destination','Destination') !!}
                                {!! Form::text('destination',null,['class'=>'form-control','placeholder'=>'Enter Destination','autocomplete'=>'off']) !!}
                                @if($errors->has('destination'))
                                    <span class="help-block text-danger">*{{ $errors->first('destination')}}</span>
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
                        <h2>Assigned Details</h2>
                        <small>Assigned vehicles details</small>
                    </div>
                    <div class="box-body">
                        <p class="data-count print-delete"> {{ $assigns->firstItem() }}
                            to {{ $assigns->lastItem() }} of total {{$assigns->total()}} entries</p>
                        <div class="table-responsive" style="margin-top: 20px; min-height: 225px;">
                            <table class="reportTable">
                                <thead>
                                <tr style="background-color:#039BE5;color: #FFFFFF">
                                    <th>Vehicle name</th>
                                    <th>Driver name</th>
                                    <th>From</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                    <th>Travel time</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($assigns)>0)
                                    @foreach($assigns as $assign)
                                        <tr>
                                            <td>{{ $assign->vehicle->vehicle_name ?? ''}}</td>
                                            <td>{{ $assign->driver->name ?? '' }}</td>
                                            <td>{{ (int) $assign->from == factoryId() ? factoryName()  : $assign->from ?? '' }}</td>
                                            <td>{{ $assign->to ?? '' }}</td>
                                            <td class="{{$assign->in_time ? 'red': 'green'}} text-white">{{ $assign->in_time == null ? 'in travel' : 'in house'  }}</td>
                                            <td>{{ $assign->travel_time ?? 0 }}</td>
                                            <td>
                                                <div class="dropdown inline">
                                                    <button class="btn btn-xs white dropdown-toggle"
                                                            data-toggle="dropdown"
                                                            aria-expanded="false">Action
                                                    </button>

                                                    <div class="dropdown-menu pull-right">
                                                        @if($assign->in_time == null)
                                                        <a class="dropdown-item"
                                                           href="{{ route('vehicle-assign.status',['id'=> $assign->id]) }}">Vehicle Receive</a>
                                                        @endif
                                                        <a class="dropdown-item show-modal white"
                                                               data-toggle="modal"
                                                               data-target="#confirmationModal"
                                                               ui-toggle-class="flip-x" ui-target="#animate"
                                                               data-url="{{ route('vehicle-assign.delete',['id'=>$assign->id]) }}">Delete
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
                        <div class="text-center print-delete">{{$assigns->links() }}</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script-head')
    <script>
        $('#from-id').prop("readonly", true);
        $('#from-id').dblclick(function () {
            $(this).prop('readonly',false)
        });

    </script>
    @endpush
