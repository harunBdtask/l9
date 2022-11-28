@extends('basic-finance::layout')
@section('title','Fund Requisition List')

@push('style')
    <style>
        .form-control {
            border: 1px solid #909ac8 !important;
            border-radius: 10px 0 0 0;
        }

        input, select {
            min-height: 10px !important;
        }

        .form-control:focus {
            border: 2px solid #909ac8 !important;
        }

        .req {
            font-size: 1rem;
        }

        .mainForm td, .mainForm th {
            border: none !important;
            padding: .3rem !important;
        }

        li.parsley-required {
            color: red;
            list-style: none;
            text-align: left;
        }

        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            border-color: #843534;
            box-shadow: none;
        }


        input.parsley-error:focus,
        select.parsley-error:focus,
        textarea.parsley-error:focus {
            border-color: #843534;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #ce8483
        }

        .remove-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #843534;
            color: whitesmoke;
        }

        .close-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #6cc788;
            color: whitesmoke;
        }

        /* select2 */
        .select2-container .select2-selection--single {
            height: 35px !important;
            border-radius: 10px 0 0 0 !important;
            line-height: 1.5rem !important;
            border: 1px solid #909ac8 !important;
        }


        .reportTable .select2-container .select2-selection--single {
            border: 1px solid #e7e7e7;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            width: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px !important;
        }

        .error + .select2-container .select2-selection--single {
            border: 1px solid red;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 35px !important;
            border-radius: 0px;
            width: 100%;
        }

    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header text-center" style="border-bottom: 1px solid #D1C4E9;">
                <h2 style="font-weight: 400; ">Fund Requisition List</h2>
            </div>

            <div class="box-body">
                @include('commercial::partials.flash')

                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/basic-finance/fund-requisition/create') }}" class="btn  btn-primary btn-sm"><i
                                class="fa fa-plus"></i> New Fund Requisition</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/basic-finance/fund-requisition') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{ $value ?? '' }}"
                                       placeholder="Search">
                                <span class="input-group-btn">
                                  <button class="btn" type="submit"> Search</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>RQ No</th>
                                <th>Req Date</th>
                                <th>Exp Rec Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requisitions as $index=>$requisition)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$requisition->requisition_no}}</td>
                                    <td>{{\Carbon\Carbon::parse($requisition->requisition_date)->toFormattedDateString()}}</td>
                                    <td>{{\Carbon\Carbon::parse($requisition->expect_receive_date)->toFormattedDateString()}}</td>
                                    <td>{{$requisition->is_approved ? 'APPROVED' : 'UNAPPROVED'}}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info"
                                           href="/basic-finance/fund-requisition/{{$requisition->id}}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/basic-finance/fund-requisition/'.$requisition->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        @if(count($requisitions))
                            {{ $requisitions->render() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush

