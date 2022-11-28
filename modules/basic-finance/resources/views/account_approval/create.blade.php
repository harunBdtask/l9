@extends('basic-finance::layout')
@section('title','Account Approval')

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
                <h2 style="font-weight: 400; ">Account Approval</h2>
            </div>

            <div class="box-body">
                <div class="from-group row message"></div>

                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <form action="{{ url('/basic-finance/fund-requisition/account-approval/create') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="requisition_no"
                                       value="{{ request('requisition_no') ?? '' }}"
                                       placeholder="Requisition No">
                                <span class="input-group-btn">
                                  <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                                    <a href="/basic-finance/fund-requisition/account-approval" class="btn btn-danger"><i
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
                            <form action="/basic-finance/fund-requisition/account-approval" method="post">
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
                                        <th>Prev. Qty</th>
                                        <th style="width: 5%">Appr. Qty</th>
                                        <th>Rate/Unit</th>
                                        <th style="width: 5%">Appr. Rate/Unit</th>
                                        <th>Amount</th>
                                        <th style="width: 5%">Appr. Amount</th>
                                        <th>Remarks</th>
                                        <th>Appr Remarks</th>
                                        <th>Approve</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($requisitionDetails as $index=>$requisition)
                                        @php
                                            $prev_approved_qty = $requisition->accountApproval->sum('approved_qty');
                                        @endphp
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td>{{$requisition->unit->unit}}</td>
                                            <td>{{$requisition->item->item_group}}</td>
                                            <td>{{$requisition->item_description}}</td>
                                            <td>{{$requisition->uoms()}}</td>
                                            <td>{{$requisition->existing_qty}}</td>
                                            <td>{{$requisition->req_qty}}</td>
                                            <td>{{$requisition->accountApproval->sum('approved_qty')}}</td>
                                            <input type="hidden" id="prev_appr_qty{{$index}}"
                                                   value="{{$prev_approved_qty}}">
                                            <input type="hidden" id="req_qty{{$index}}"
                                                   value="{{$requisition->req_qty}}">
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="appr_qty[{{$requisition->id}}]"
                                                       type="text" id="appr_qty{{$index}}"
                                                       onkeyup="calculateRowWiseAmount({{$index}})" disabled>
                                            </td>
                                            <td>{{$requisition->rate}}</td>
                                            <td>
                                                <input class="form-control form-control-sm "
                                                       name="appr_rate[{{$requisition->id}}]"
                                                       type="text" id="appr_rate{{$index}}"
                                                       onkeyup="calculateRowWiseAmount({{$index}})" disabled>
                                            </td>
                                            <td>{{$requisition->amount}}</td>
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="appr_amount[{{$requisition->id}}]"
                                                       type="text" id="appr_amount{{$index}}" disabled>
                                            </td>
                                            <td>
                                                <p>{{$requisition->remarks}}</p>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="appr_remarks[{{$requisition->id}}]"
                                                       type="text" id="appr_remarks{{$index}}" disabled>
                                            </td>
                                            <td>
                                                @if($prev_approved_qty<$requisition->req_qty)
                                                    <input name="check[{{$requisition->id}}]" data-key="{{$index}}"
                                                           id="checkid" type="checkbox">
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="text-center btn-groups">
                                    <button class="btn btn-success btn-sm" id="saveBtn" type="submit">Save</button>
                                    <a href="/basic-finance/fund-requisition/account-approval/create"
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
            let appr_qty = $("#appr_qty" + key);
            let appr_rate = $("#appr_rate" + key);
            let appr_amount = $("#appr_amount" + key);
            let appr_remarks = $("#appr_remarks" + key);
            if ($(this).is(':checked') === true) {
                appr_qty.removeAttr("disabled");
                appr_rate.removeAttr("disabled");
                appr_remarks.removeAttr("disabled");
            } else {
                appr_qty.attr("disabled", "disabled").val(null);
                appr_rate.attr("disabled", "disabled").val(null);
                appr_amount.val(null);
                appr_remarks.attr("disabled", "disabled").val(null);
            }
        })

        function errorMessage(message) {
            let errorMessage = [
                '<div class="col-lg-12">',
                '<div class="alert alert-danger alert-dismissible show" role="alert">',
                '<strong>' + message + '</strong>',
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">',
                '<span aria-hidden="true">&times;</span>',
                '</button>',
                '</div>',
                '</div>'
            ].join('');
            $('.message').html(errorMessage);
        }


        function calculateRowWiseAmount(index) {
            let rate = Number($("#appr_rate" + index).val());
            let req_qty = Number($("#req_qty" + index).val());
            let qty = Number($("#appr_qty" + index).val());
            let prev_qty = Number($("#prev_appr_qty" + index).val());
            let amount = $("#appr_amount" + index);
            if ((qty + prev_qty) > req_qty) {
                errorMessage("Quantity exceeds");
                $("#saveBtn").attr("disabled", "disabled");
                return false;
            }
            $("#saveBtn").removeAttr("disabled");
            $('.message').html("");
            amount.val(rate * qty);
        }
    </script>
@endpush

