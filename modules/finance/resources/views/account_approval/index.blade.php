@extends('finance::layout')
@section('title','Account Approval List')

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
                <h2 style="font-weight: 400; ">Account Approval List</h2>
            </div>

            <div class="box-body">
                @include('commercial::partials.flash')

                <div class="row">
                    @if(request('type')!=='unapproved')
                        <a href="{{ url('/finance/fund-requisition/account-approval/create') }}"
                           class="btn btn-primary btn-sm"><i
                                class="fa fa-plus"></i> Account Approval</a>
                        <a href="{{ url('/finance/fund-requisition/account-approval?type=unapproved') }}"
                           class="btn btn-danger btn-sm"><i
                                class="fa fa-eye"></i> Unapproved</a>
                    @else
                        <a href="{{ url('/finance/fund-requisition/account-approval') }}"
                           class="btn btn-primary btn-sm"><i
                                class="fa fa-eye"></i> Approved</a>
                    @endif
                </div>
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <form action="{{ url('/finance/fund-requisition/account-approval') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search"
                                   value="{{ request('search') ?? '' }}"
                                   placeholder="Search">
                            <span class="input-group-btn">
                                  <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                                </span>
                        </div>
                    </form>
                </div>
            </div>
            @include('partials.response-message')
            <div class="row m-t">
                <div class="col-sm-12">
                    @if(count($approvals)>0)
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Audit Date</th>
                                <th>RQ No</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=1)
                            @if(request('type')==='unapproved')
                                @foreach($approvals as $dateWiseApproval)
                                    @foreach($dateWiseApproval as $approval)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{\Carbon\Carbon::make(collect($approval)->first()['audit_date'])->toFormattedDateString()}}</td>
                                            <td>{{collect($approval)->first()->requisition->requisition_no}}</td>
                                            <td>
                                                <a class="btn btn-xs btn-success"
                                                   href="/finance/fund-requisition/account-approval/create?requisition_no={{collect($approval)->first()->requisition->requisition_no}}">
                                                    <i class="fa fa-check"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                @foreach($approvals->groupBy('date') as $approval)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{\Carbon\Carbon::make(collect($approval)->first()['date'])->toFormattedDateString()}}</td>
                                        <td>{{collect($approval)->first()->requisition->requisition_no}}</td>
                                        <td>
                                            <a class="btn btn-xs btn-info"
                                               href="/finance/fund-requisition/account-approval/{{collect($approval)->first()
                                                                                                ->requisition_id}}?date={{collect($approval)->first()->date}}">
                                                <i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script-head')

@endpush

