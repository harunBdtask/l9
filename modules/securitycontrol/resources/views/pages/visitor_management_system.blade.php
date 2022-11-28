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
                        <h2>Visitor Registration</h2>
                        <small>Register Visitor Information</small>
                    </div>

                    <div class="box-body">
                        {!! Form::open(['route'=>['visitor.store',request()->segment(2) ? request()->segment(2) : null ] ,'method'=>'post','class'=>'form-control']) !!}
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('name','Visitor name') !!}
                                {!! Form::text('name',$visitor_edit->name ?? '',['class'=>'form-control','placeholder'=>'Enter Visitor Name','autocomplete'=>'off']) !!}
                                @if($errors->has('name'))
                                    <span class="help-block text-danger">*{{ $errors->first('name')}}</span>
                                @endif
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('designation','Designation') !!}
                                {!! Form::text('designation',$visitor_edit->designation ?? '',['class'=>'form-control','placeholder'=>'Enter Visitor Designation','autocomplete'=>'off']) !!}
                                @if($errors->has('designation'))
                                    <span class="help-block text-danger">*{{ $errors->first('designation')}}</span>
                                @endif

                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('company_name','Company Name') !!}
                                {!! Form::text('company_name',$visitor_edit->company_name ?? '',['class'=>'form-control','placeholder'=>'Enter Visitor Company Name','autocomplete'=>'off']) !!}
                                @if($errors->has('company_name'))
                                    <span class="help-block text-danger">*{{ $errors->first('company_name')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('mobile_number','Mobile Number') !!}
                                {!! Form::text('mobile_number',$visitor_edit->mobile_number ?? '',['class'=>'form-control','placeholder'=>'Enter Visitor Mobile Number','autocomplete'=>'off']) !!}
                                @if($errors->has('mobile_number'))
                                    <span class="help-block text-danger">*{{ $errors->first('mobile_number')}}</span>
                                @endif

                            </div>
                        </div>

                        <div class="row m-b">
                            <div class="col-sm-6">
                                {!! Form::label('email','Email') !!}
                                {!! Form::text('email',$visitor_edit->email ?? '',['class'=>'form-control','placeholder'=>'Enter Visitor Email','autocomplete'=>'off']) !!}
                                @if($errors->has('email'))
                                    <span class="help-block text-danger">*{{ $errors->first('email')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('meeting_person','Meeting Person') !!}
                                {!! Form::text('meeting_person',$visitor_edit->meeting_person ?? '',['class'=>'form-control','placeholder'=>'Enter Meeting Person Name','autocomplete'=>'off']) !!}
                                @if($errors->has('meeting_person'))
                                    <span class="help-block text-danger">*{{ $errors->first('meeting_person')}}</span>
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
                        <h2>Visitor Details</h2>
                        <small>Visitor Information Details</small>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                {!! Form::text('search',null,['class'=>'custom-form-control','placeholder'=>'Scan visitor card','autocomplete'=>'off','id'=>'search_visitor']) !!}
                            </div>

                        </div>

                        <p class="data-count print-delete">{{ $visitors->firstItem() }}
                            to {{ $visitors->lastItem() }} of total {{$visitors->total()}} entries</p>
                        <div class="table-responsive" style="margin-top: 20px; min-height: 225px;">
                            <table class="reportTable">
                                <thead>
                                <tr style="background-color:#039BE5;color: #FFFFFF">
                                    <th>Name</th>
                                    <th>Registration No</th>
                                    <th>Designation</th>
                                    <th>Company</th>
                                    <th>Mobile No</th>
                                    <th>Email</th>
                                    <th>Meeting Person</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(count($visitors)>0)
                                    @foreach($visitors as $visitor)
                                        <tr>
                                            <td>{{ $visitor->name ?? ''}}</td>
                                            <td>{{ $visitor->registration_id ?? '' }}</td>
                                            <td>{{ $visitor->designation ?? '' }}</td>
                                            <td>{{ $visitor->company_name ?? ''}}</td>
                                            <td>{{ $visitor->mobile_number ?? ''}}</td>
                                            <td>{{ $visitor->email ?? '' }}</td>
                                            <td>{{ $visitor->meeting_person ?? '' }}</td>
                                            <td id="status-{{$visitor->registration_id}}"
                                                class="{{ $visitor->status ? 'green':  'red'}} text-white">
                                                <b>{{ $visitor->status ? 'Check In' : 'Check Out' }}</b></td>
                                            <td>
                                                <div class="dropdown inline">
                                                    <button class="btn btn-xs white dropdown-toggle"
                                                            data-toggle="dropdown"
                                                            aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu pull-right">
                                                        @if($visitor->status)
                                                            <a class="dropdown-item"
                                                               href="{{route('visitor.status.update',['id'=> $visitor->id])}}">CheckOut</a>
                                                            <a class="dropdown-item"
                                                               href="{{route('visitor.edit',['id'=> $visitor->id])}}">Edit</a>
                                                        @endif
                                                        <a class="dropdown-item show-modal white"
                                                           data-toggle="modal"
                                                           data-target="#confirmationModal"
                                                           ui-toggle-class="flip-x" ui-target="#animate"
                                                           data-url="{{ route('visitor.delete',['id'=>$visitor->id]) }}">Delete
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item"
                                                           href="{{route('visitor.show',['id'=> $visitor->id])}}">View</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9"><b>No data found</b></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        <div class="text-center print-delete">{{$visitors->links() }}</div>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@push('script-head')
    <script>
        $(document).ready(function () {
            toastr.options.preventDuplicates = true;
            toastr.options.timeOut = 600;
            toastr.options.onHidden = function () {
                $('#search_visitor').val(' ');
                location.reload()

            };

            function debounce(func, wait, immediate) {
                var timeout;
                return function () {
                    var context = this, args = arguments;
                    var later = function () {
                        timeout = null;
                        if (!immediate) func.apply(context, args);
                    };
                    var callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) func.apply(context, args);
                };
            };

            const handleCardScan = debounce(function () {
                var registrationId = $(this).val();

                $.ajax({
                    url: '{{url('qrScan')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {id: registrationId},
                    success: function (data) {
                        if (data['status'] === 'Success') {
                            toastr.success('Visitor Check Out Successfully');

                        } else if (data['status'] === 'Warning') {
                            toastr.warning('Visitor Already Checked Out');
                        }

                    },
                    error : function (data) {
                        toastr.warning('Visitor Already Checked Out');
                    }

                });

            }, 250);

            $('#search_visitor').on('keyup', handleCardScan);
        });
    </script>
@endpush
