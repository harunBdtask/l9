@extends('skeleton::layout')
@section('title','Shipment Month Wise Order Status')
@section('content')
    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header-section {
            padding: 10px;
        }

        .body-section {
            padding: 10px;
            padding-top: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }

        @page {
            size: landscape;
            /*margin: 5mm;*/
            /*margin-left: 15mm;*/
            /*margin-right: 15mm;*/
        }

        .select2-container .select2-selection--single {
            height: 32px !important;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 35px !important;
        }

        @media print {
            html, body {
                width: 210mm;
                /*height: 293mm;*/
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Shipment Month Wise Order Status</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">

                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select class="form-control" name="month" id="month">
                                            <option value="">Select</option>
                                            @foreach($months as $list)
                                                <option
                                                    value="{{ $list['id'] }}" {{ $list['id'] == $month ? 'selected' : '' }}>
                                                    {{ $list['text'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select class="form-control" name="year" id="year">
                                            <option value="0">Select</option>
                                            @foreach(years() as $list)
                                                <option
                                                    value="{{ $list }}" {{ $list == $year ? 'selected' : '' }}>
                                                    {{ $list }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-info" title="Summery">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a class="btn" id="pdf" href="javascript:void(0)">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                                <a class="btn" id="excel" href="javascript:void(0)">
                                    <i class="fa fa-file-excel-o"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-offset-2 col-md-8 p-a-2 box">
                        {!! $chart !!}
                    </div>
                </div>
                @includeif('merchandising::order.report.shipment_wise_report.shipment_wise_order_report_table')
            </div>
        </div>
    </div>
@endsection
@push("script-head")
    <script>
        $(document).ready(function () {

            $('#pdf').click(function (e) {
                e.preventDefault();
                const month = $('#month').val();
                const year = $('#year').val();
                const url = `{{ url('/shipment-wise-order-report-pdf') }}?month=${month}&year=${year}`;
                window.open(url, '_blank');
            });

            $('#excel').click(function (e) {
                e.preventDefault();
                const month = $('#month').val();
                const year = $('#year').val();
                const url = `{{ url('/shipment-wise-order-report-excel') }}?month=${month}&year=${year}`;
                window.open(url, '_blank');
            });
        })
    </script>
@endpush
