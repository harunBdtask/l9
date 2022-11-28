@extends('skeleton::layout')
@section('title','Daily Knitting Report')
@section('styles')
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
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Daily Program Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="{{ url('knitting/daily-knitting-report') }}">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group" style="margin-left: 10px;">
                                        <label for="from_date">From Date</label>
                                        <input class="form-control form-control-sm"
                                            id="from_date"
                                            type="date"
                                            name="from_date"
                                            value="{{ request('from_date') }}"
                                        >
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group" style="margin-left: 10px;">
                                        <label for="to_date">To Date</label>
                                        <input class="form-control form-control-sm"
                                               id="to_date"
                                               type="date"
                                               name="to_date"
                                               value="{{ request('to_date') }}"
                                        >
                                    </div>
                                </div>
                                <div class="col-sm-1" style="display:flex;">
                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                            title="Search" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a href="/knitting/daily-knitting-report" style="margin-top: 30px; margin-left: 5px;" class="btn btn-sm btn-primary"
                                            title="Refresh">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </div>

                                @if($data)
                                <div class="col-sm-5 text-right" style="margin-top: 2%;">
                                    <a class="btn" href="{{ url('/knitting/daily-knitting-report/pdf?'.Request::getQueryString()) }}">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                    <a class="btn" href="{{ url('/knitting/daily-knitting-report/excel?'.Request::getQueryString()) }}">
                                        <i class="fa fa-file-excel-o"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <br>
                    @if($data)
                        @includeIf('knitting::reports.daily-knitting-report.view-body')
                    @endif
                </div>
            </div>
            <div style="text-align: center;">
                <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
            </div>
        </div>
    </div>
@endsection
