@extends('skeleton::layout')
@section('title','Season Wise Order Overview')
@section('content')
    <style>
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
                <h2>Season Wise Order Overview Report</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="" id="reportForm">
                        <div class="col-sm-8 ">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <select class="form-control form-control-sm select2-input" name="year"
                                                id="year">
                                            <option>Select Year</option>
                                            @foreach(years() as $list)
                                                <option
                                                    value="{{ $list }}" {{ ($list == request()->get('year') || $list == $year) ? 'selected' : '' }}>
                                                    {{ $list }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Season</label>
                                        <select class="form-control form-control-sm select2-input" name="season"
                                                id="season">
                                            <option>Select Season</option>
                                            @foreach($seasons as $season)
                                                <option
                                                    value="{{ $season }}" {{ $season == request()->get('season') ? 'selected' : '' }}>
                                                    {{ $season }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                            name="search" title="search" id="search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div style="text-align: center;">
                    <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
                </div>
                <div class="col-sm-12">
                    <div class="pull-right" style="margin-bottom: -5%;">
                        <a class="btn" id="pdf" href="javascript:void(0)">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a class="btn" id="excel" href="javascript:void(0)">
                            <i class="fa fa-file-excel-o"></i>
                        </a>
                    </div>
                </div>
                <div style="margin-top: 16mm" >
                    <center>
                        <table style="border: 1px solid black; width: 40%">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">Season Wise Order Overview</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    @include('merchandising::order.overview_report.season_wise_order_overview_table')
                </div>
            </div>
        </div>
    </div>

@endsection
@push("script-head")
    <script>
        $(document).ready(function () {

            $('#pdf').click(function (e) {
                e.preventDefault();
                const season = $('#season').val();
                const year = $('#year').val();
                const url = `{{ url('/season-wise-order-overview-pdf') }}?season=${season}&year=${year}`;
                window.open(url, '_blank');
            });

            $('#excel').click(function (e) {
                e.preventDefault();
                const season = $('#season').val();
                const year = $('#year').val();
                const url = `{{ url('/season-wise-order-overview-excel') }}?season=${season}&year=${year}`;
                window.open(url, '_blank');
            });
        })
    </script>
@endpush
