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

        .custom-form-control {
            font-size: 1rem;
            line-height: 1.5;

            display: block;

            width: 100%;
            height: calc(2.75rem + 2px);
            padding: .625rem .75rem;

            transition: all .2s cubic-bezier(.68, -.55, .265, 1.55);

            color: #8898aa;
            border: 1.5px solid #0089BC;
            border-radius: 5px;
            background-color: #fff;
            background-clip: padding-box;
            box-shadow: none;
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .custom-form-control {
                transition: none;
            }
        }

        .custom-form-control::-ms-expand {
            border: 0;
            background-color: transparent;
        }

        .custom-form-control:focus {
            color: #939baa;
            border-color: rgba(50, 151, 211, .25);
            outline: 0;
            background-color: #fff;
            box-shadow: none, none;
            border: 1.5px solid #0089BC;
        }

        .custom-form-control::-webkit-input-placeholder {
            opacity: 1;
            color: #adb5bd;
        }

        .custom-form-control:-ms-input-placeholder {
            opacity: 1;
            color: #adb5bd;
        }

        .custom-form-control::-ms-input-placeholder {
            opacity: 1;
            color: #adb5bd;
        }

        .custom-form-control::placeholder {
            opacity: 1;
            color: #adb5bd;
        }

        .custom-form-control:disabled,
        .custom-form-control[readonly] {
            opacity: 1;
            background-color: #e9ecef;
        }


    </style>

@endpush
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-8 col-lg-offset-2">
                <div class="box">
                    <div class="box-header custom-color">
                        <h2>Employee Tracking</h2>
                        <small>Register Employee Information</small>
                    </div>

                    <div class="box-body">
                        {!! Form::open(['route'=>['employee.store',request()->segment(2) ? request()->segment(2) : null ] ,'method'=>'post','class'=>'form-control']) !!}
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('name','Employee name') !!}
                                {!! Form::text('name',$employee_edit->name ?? '',['class'=>'form-control','placeholder'=>'Enter Employee Name','autocomplete'=>'off']) !!}
                                @if($errors->has('name'))
                                    <span class="help-block text-danger">*{{ $errors->first('name')}}</span>
                                @endif
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('registration_no','') !!}
                                {!! Form::text('registration_no',$employee_edit->registration_no ?? '',['class'=>'form-control','placeholder'=>'Enter Employee Id','autocomplete'=>'off']) !!}
                                @if($errors->has('registration_no'))
                                    <span class="help-block text-danger">*{{ $errors->first('registration_no')}}</span>
                                @endif

                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('accessories_name','Accessories Name') !!}
                                {!! Form::text('accessories_name',$employee_edit->accessories_name ?? '',['class'=>'form-control','placeholder'=>'Enter Accessories Name ','autocomplete'=>'off']) !!}
                                @if($errors->has('accessories_name'))
                                    <span class="help-block text-danger">*{{ $errors->first('accessories_name')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('quantity','Quantity') !!}
                                {!! Form::text('quantity',$employee_edit->quantity ?? '',['class'=>'form-control','placeholder'=>'Enter Quantity','autocomplete'=>'off']) !!}
                                @if($errors->has('quantity'))
                                    <span class="help-block text-danger">*{{ $errors->first('quantity')}}</span>
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
            <div class="col-sm-12 col-md-12 col-lg-10 col-lg-offset-1 ">
                <div class="box">
                    <div class="box-header custom-color">
                        <h2>Employee Tracking Details</h2>
                        <small>Visitor Information Details</small>
                    </div>
                    <div class="box-body">
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-4">--}}
{{--                                {!! Form::text('search',null,['class'=>'custom-form-control','placeholder'=>'Scan visitor card','autocomplete'=>'off','id'=>'search_visitor']) !!}--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <p class="data-count print-delete">{{ $employees->firstItem() }}
                            to {{ $employees->lastItem() }} of total {{$employees->total()}} entries</p>
                        <div class="table-responsive" style="margin-top: 20px; min-height: 225px;">
                            <table class="reportTable">
                                <thead>
                                <tr style="background-color:#039BE5;color: #FFFFFF">
                                    <th>Name</th>
                                    <th>Registration No</th>
                                    <th>Accessories name</th>
                                    <th>Quantity</th>
                                    <th>Out Time</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(count($employees)>0)
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>{{ $employee->name ?? ''}}</td>
                                            <td>{{ $employee->registration_no ?? ''}}</td>
                                            <td>{{ $employee->accessories_name ?? '' }}</td>
                                            <td>{{ $employee->quantity ?? '' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($employee->out_time)->diffForHumans() }}</td>
                                            <td>
                                                <div class="dropdown inline">
                                                    <button class="btn btn-xs white dropdown-toggle"
                                                            data-toggle="dropdown"
                                                            aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu pull-right">
                                                        <a class="dropdown-item"
                                                       href="{{route('employee.edit',['id'=> $employee->id])}}">Edit</a>

                                                        <a class="dropdown-item show-modal white"
                                                           data-toggle="modal"
                                                           data-target="#confirmationModal"
                                                           ui-toggle-class="flip-x" ui-target="#animate"
                                                           data-url="{{ route('employee.delete',['id'=>$employee->id]) }}">Delete
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6"><b>No data found</b></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        <div class="text-center print-delete">{{$employees->links() }}</div>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@push('script-head')
    <script>
        $(document).ready(function () {

        });
    </script>
@endpush
