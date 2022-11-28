@extends('iedroplets::layout')
@section('title', 'Overall Shipment Report')
@push('style')
    <style>
        .suggetion {
            z-index: 1000;
            position: absolute;
        }

        h2, h6 {
            text-transform: uppercase;
            font-weight: 400;
            letter-spacing: 1px;
        }

        dfn {
            color: red;
        }

        .append-suggetion {
            padding: 0px;
            margin: 0px;
            width: 340px;
            border: 1px solid #ccc;
            min-height: 50px;
            max-height: 200px;
            overflow-y: scroll;
            background: #fff;
        }

        .append-suggetion li {
            list-style: none;
            display: block;
            cursor: pointer;
            height: 40px;
            padding-left: 15px;
            line-height: 40px;
        }

        .append-suggetion li:hover {
            background: lightgoldenrodyellow;
        }

        select {
            height: 38px !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border-radius: 0px !important;
            border-color: rgba(120, 130, 140, 0.2) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 0px !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: none !important;
        }

        .custom-select {
            width: 100%;
            height: 30px !important;
            margin: 5px 0px;
            border: 1px solid rgba(120, 130, 140, 0.2);
        }

        .modal-dialog {
            width: 842px !important;
            margin: 30px auto;
        }

        table thead tr th {
            white-space: nowrap;
        }

        .modal-body .select2-container {
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            position: relative;
            vertical-align: middle;
            width: 169px !important;
        }

        .add-btn {
            padding: 3px;
            border-radius: 2px;
        }

        .reportTable thead tr th {
            width: 12%;
        }

        .error + .select2-container {
            border: 1px solid red;
        }

        table thead tr th {
            white-space: nowrap;
            font-size: 11px;
            font-weight: 500;
        }

        label {
            font-size: 12px;
            font-weight: 400;
            text-transform: uppercase;
            /*letter-spacing: 1px;*/
        }

        .form-control form-control-sm {
            font-size: 12px !important;
        }

        .modal-body .select2-container {
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            position: relative;
            vertical-align: middle;
            width: 169px !important;
        }

        input[type=date].form-control form-control-sm {
            line-height: 1;
        }

        .select2-results__option, .select2-selection__choice {
            font-size: 12px;
            text-transform: lowercase;
        }

        .select2-container--default .select2-selection--multiple {
            border-color: rgba(120, 130, 140, 0.2);
        }

        .select2-selection__rendered {
            font-size: 12px;
        }

        .select2-dropdown {
            border-color: #ccc;
        }

        .select2-container {
            max-width: 2000px !important;
        }

        .text-danger {
            font-size: 11px;
        }

        #fixTable {
            position: sticky;
        }
    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Overall Shipment Report</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>

                    @if(Session::has('permission_of_overall_shipment_report_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                        <div class="box-body">
                            @include('partials.response-message')
                            <form action="{{ url('/overall-shipment-report')}}" method="get">
                                {{--                                @csrf--}}
                                <div class="form-group">
                                    <div class="row m-b">
                                        <div class="col-sm-2">
                                            <label>Date</label>
                                            {{ Form::date('start_date', $startDate ?? null, ['class' => 'form-control form-control-sm']) }}
                                        </div>

                                        <div class="col-sm-2">
                                            <label>End Date</label>
                                            {{ Form::date('end_date', $endDate ?? null, ['class' => 'form-control form-control-sm']) }}
                                        </div>

                                        <div class="col-sm-2">
                                            <label>Buyer</label>
                                            {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                                            @if($errors->has('buyer_id'))
                                                <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Style/Order</label>
                                            {!! Form::select('order_id', $orders_list ?? [], old('order_id') ?? null, ['class' => 'style-select form-control form-control-sm select2-input']) !!}
                                            @if($errors->has('order_id'))
                                                <span class="text-danger">{{ $errors->first('order_id') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-sm-2" style="padding-top: 30px">
                                            {!! Form::submit('Search', ['class' => 'btn btn-info btn-sm']) !!}
                                        </div>

                                        <div class="col-sm-2" align="right" style="padding-top: 30px">
                                            <a class="hidden-print btn btn-xs" href="{{ url($downloadURL . '&pdf') }}"
                                               title="Print this document" id="print">
                                                <i class="fa fa-file-pdf-o text-danger"></i>&nbsp;PDF
                                            </a>
                                            <a class="hidden-print btn btn-xs" href="{{ url($downloadURL . '&xls') }}"
                                               title="Print this document" id="print">
                                                <i class="fa fa-file-excel-o text-success"></i>&nbsp;Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div id="parentTableFixed" class="table-responsive" style="overflow: auto;">
                                    @include('iedroplets::reports.includes.overall_shipment_report_table')
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script src="{{ asset('js/tableHeadFixer.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#fixTable").tableHeadFixer();
        });

        const buyerSelectDom = $('[name="buyer_id"]');
        const orderSelectDom = $('[name="order_id"]');
        buyerSelectDom.change(() => {
            orderSelectDom.empty().val('').select2();
            $.ajax({
                url: "/utility/get-styles-for-select2-search",
                type: "get",
                data: {'buyer_id': buyerSelectDom.val()},
                success({results}) {
                    orderSelectDom.empty();
                    orderSelectDom.html(`<option selected>SELECT</option>`);
                    results.forEach(el => {
                        let html = `<option value="${el.id}">${el.text}</option>`;
                        orderSelectDom.append(html);
                    });
                }
            })
        });
        
    </script>
@endpush
