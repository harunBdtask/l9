@extends('skeleton::layout')
@section('title','Yarn Allocation Report')
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
                <h2>Yarn Allocation Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <form action="{{ url('knitting/yarn-allocation-report') }}">
                        <div class="col-md-12">
                            <table class="reportTable" style="background-color: aliceblue">
                                <thead>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Yarn Lot</th>
                                <th>Yarn Count</th>
                                <th>Yarn Ref</th>
                                <th>Yarn Color</th>
                                <th>Yarn Type</th>
                                <th>Yarn Brand</th>
                                <th style="width: 5%;">Action</th>
                                </thead>
                                <tbody>
                                <td>
                                    <input class="form-control form-control-sm"
                                           id="from_date"
                                           type="date"
                                           name="from_date"
                                           value="{{ request('from_date') }}"
                                    >
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           id="to_date"
                                           type="date"
                                           name="to_date"
                                           value="{{ request('to_date') }}"
                                    >
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           id="yarn_lot"
                                           type="text"
                                           name="yarn_lot"
                                           value="{{ request('yarn_lot') }}"
                                    >
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           id="yarn_count"
                                           type="text"
                                           name="yarn_count"
                                           value="{{ request('yarn_count') }}"
                                    >
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           id="yarn_ref"
                                           type="text"
                                           name="yarn_ref"
                                           value="{{ request('yarn_ref') }}"
                                    >
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           id="yarn_color"
                                           type="text"
                                           name="yarn_color"
                                           value="{{ request('yarn_color') }}"
                                    >
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           id="yarn_type"
                                           type="text"
                                           name="yarn_type"
                                           value="{{ request('yarn_type') }}"
                                    >
                                </td>
                                <td>
                                    <input class="form-control form-control-sm"
                                           id="yarn_brand"
                                           type="text"
                                           name="yarn_brand"
                                           value="{{ request('yarn_brand') }}"
                                    >
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info"
                                            title="Search" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a href="/knitting/yarn-allocation-report" class="btn btn-sm btn-primary"
                                       title="Refresh">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </td>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                @if($data)
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ url('/knitting/yarn-allocation-report/pdf?'.Request::getQueryString()) }}">
                                <i class="fa fa-file-pdf-o"></i>
                            </a> |
                            <a href="{{ url('/knitting/yarn-allocation-report/excel?'.Request::getQueryString()) }}">
                                <i class="fa fa-file-excel-o"></i>
                            </a>
                        </div>
                        <div class="col-md-12 table-responsive">
                            @includeIf('knitting::reports.yarn-allocation-report.view-body')
                        </div>
                    </div>
                @endif
            </div>
            <div style="text-align: center;">
                <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
            </div>
        </div>
    </div>
@endsection
