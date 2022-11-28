@extends('skeleton::layout')
@section('title','Month Wise PO Report')
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
                <h2>Month Wise PO Report</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <select name="factory_id" class="form-control select2-input" id="factory_id">
                                            <option value="">Select Company</option>
                                            @foreach($factories as $key=>$factory)
                                                <option
                                                    value="{{ $factory['id'] }}" {{ $factory['id'] == $factoryId ? 'selected' : null }}>{{ $factory['factory_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Buyer</label>
                                        <select name="buyer_id" class="form-control select2-input" id="buyer_id">
                                            <option value="">Select Buyer</option>
                                            @foreach($buyers as $buyer)
                                                <option
                                                    value="{{ $buyer->id }}" {{ $buyer->id == $buyerId ? 'selected' : null }}>{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Month</label>
                                        <select class="form-control select2-input" name="month" id="month">
                                            <option value="">Select</option>
                                            @foreach($months as $list)
                                                <option
                                                    value="{{ $list['id'] }}" {{ $list['id'] == request('month') ? 'selected' : '' }}>
                                                    {{ $list['text'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <select class="form-control select2-input" name="year" id="year">
                                            <option value="0">Select</option>
                                            @foreach($years as $list)
                                                <option
                                                    value="{{ $list }}" {{ $list == request('year') ? 'selected' : '' }}>
                                                    {{ $list }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                            <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                                    name="search" title="Summery" id="search">
                                                <i class="fa fa-search"></i>
                                            </button>
                                    </div>
                                </div>
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
                <div>
                    <table class="borderless" style="text-align: center;">
                        <tr>
                            <td>
                                <h4> Month Wise PO Report </h4>
                            </td>
                        </tr>
                    </table>
                </div>
                @include('merchandising::new-report.month-wise-po-report.month_wise_po_report_table')
            </div>
        </div>
    </div>
@endsection
@push("script-head")
    <script>
        $(document).on('change', '#factory_id', function () {
            let factoryId = $(this).val();
            $('#buyer_id').empty().append(`<option value="">Select Buyer</option>`).val('').trigger('change');
            $.ajax({
                method: 'GET',
                url: `/orders/get-buyers?factoryId=${factoryId}`,
                success: function (result) {
                    $.each(result, function (key, value) {
                        let element = `<option value="${value.id}">${value.name}</option>`;
                        $('#buyer_id').append(element);
                    })
                },
                error: function (error) {
                    console.log(error)
                }
            })
        });
        $(document).on('change', '#buyer_id', function () {
            let factoryId = $("#factory_id").val();
            let buyerId = $(this).val();
            $('#season_id').empty().append(`<option value="">Select Season</option>`).val('').trigger('change');
            $('#assign_factory_id').empty().append(`<option value="">Assigning Factory</option>`).val('').trigger('change');
            $.ajax({
                method: 'GET',
                url: `/orders/get-seasons?factoryId=${factoryId}&buyerId=${buyerId}`,
                success: function (result) {
                    $.each(result, function (key, value) {
                        let element = `<option value="${value.id}">${value.season_name}</option>`;
                        $('#season_id').append(element);
                    })
                },
                error: function (error) {
                    console.log(error)
                }
            })
            $.ajax({
                method: 'GET',
                url: `/get-orders-assign-factory?buyerId=${buyerId}`,
                success: function (result) {
                    $.each(result, function (key, value) {
                        let element = `<option value="${value.id}">${value.name}</option>`;
                        $('#assign_factory_id').append(element);
                    })
                },
                error: function (error) {
                    console.log(error)
                }
            })
        });
        $(document).ready(function () {

            $('#pdf').click(function (e) {
                e.preventDefault();
                const factory_id = $('#factory_id').val();
                const buyer_id = $('#buyer_id').val();
                const month = $('#month').val();
                const year = $('#year').val();
                const url = `{{ url('/month-wise-po-report-pdf') }}?factoryId=${factory_id}&buyerId=${buyer_id}&month=${month}&year=${year}`;
                window.open(url, '_blank');
            });

            $('#excel').click(function (e) {
                e.preventDefault();
                const factory_id = $('#factory_id').val();
                const buyer_id = $('#buyer_id').val();
                const month = $('#month').val();
                const year = $('#year').val();
                const url = `{{ url('/month-wise-po-report-excel') }}?factoryId=${factory_id}&buyerId=${buyer_id}&month=${month}&year=${year}`;
                window.open(url, '_blank');
            });
        })
    </script>
@endpush
