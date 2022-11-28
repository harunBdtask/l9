@extends('finance::layout')
@section('title','Audit Approval')

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
                <h2 style="font-weight: 400; ">Audit Approval</h2>
            </div>

            <div class="box-body">
                @include('commercial::partials.flash')

                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <form action="{{ url('/finance/fund-requisition/audit-approval/create') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="requisition_no"
                                       value="{{ request('requisition_no') ?? '' }}"
                                       placeholder="Requisition No">
                                <span class="input-group-btn">
                                  <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                                    <a href="/finance/fund-requisition/audit-approval" class="btn btn-danger"><i
                                            class="fa fa-times"></i></a>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        @if(count($requisitionDetails)>0)
                            <form action="/finance/fund-requisition/audit-approval" method="post">
                                @csrf
                                <table class="reportTable">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Dept</th>
                                        <th>Item</th>
                                        <th>Item Des</th>
                                        <th>UOM</th>
                                        <th>Existing Qty</th>
                                        <th>Req Qty</th>
                                        <th>Rate/Unit</th>
                                        <th>Amount</th>
                                        <th>Remarks</th>
                                        <th>Audit Comment</th>
                                        <th>Approve</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($requisitionDetails as $index=>$requisition)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td>{{$requisition->department->name}}</td>
                                            <td>{{$requisition->item->item_group}}</td>
                                            <td>{{$requisition->item_description}}</td>
                                            <td>{{$requisition->uoms()}}</td>
                                            <td>{{$requisition->existing_qty}}</td>
                                            <td>{{$requisition->req_qty}}</td>
                                            <td>{{$requisition->rate}}</td>
                                            <td>{{$requisition->amount}}</td>
                                            <td>
                                                <p>{{$requisition->remarks}}</p>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="comment[{{$requisition->id}}]"
                                                       type="text" id="comment_{{$index}}" disabled>
                                            </td>
                                            <td><input name="check[{{$requisition->id}}]" data-key="{{$index}}"
                                                       id="checkid" type="checkbox">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="text-center btn-groups">
                                    <button class="btn btn-success btn-sm" type="submit">Save</button>
                                    <a href="/finance/fund-requisition/audit-approval/create"
                                       class="btn btn-sm btn-danger">Refresh</a>
                                </div>
                            </form>
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
    <script>
        $(document).on("change", '#checkid', function () {
            let key = $(this).attr("data-key");
            let comment = $("#comment_" + key);
            if ($(this).is(':checked') === true) {
                comment.removeAttr("disabled");
            } else {
                comment.attr("disabled", "disabled").val(null);
            }
        })
    </script>
@endpush

