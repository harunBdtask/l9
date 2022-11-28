@extends('dyes-store::layout')
@section('title')
    Stock Summary Report
@endsection
@section('content')

    <style>
        h1 {
            text-align: center;
            text-transform: uppercase;
            letter-spacing: -2px;
            font-size: 2.5em;
            margin: 20px 0;
        }

        .container {
            width: 90%;
            margin: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: center;
            padding: 5px 0;
        }

        thead {
            background: #ffffff;
        }

        tbody tr:nth-child(even) {
            background: #ECF0F1;
        }

        .fixed {
            top: 56px;
            position: fixed;
            width: auto;
            display: none;
            border: none;
        }

        .scrollMore {
            margin-top: 600px;
        }

        .up {
            cursor: pointer;
        }
    </style>

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyes & Chemicals Stock Summary Report</h2>
                <span class="pull-right" style="margin-top: -1%;">
                    <a id="pdf" type="button" href="/dyes-store/dyes-stock-summary-report/daily/pdf"
                       data-toggle="tooltip" data-placement="top" title="PDF">
                       <em style="color: #DC0A0B" class="fa fa-file-pdf-o"></em>
                    </a>|
                    <a id="excel" type="button" href="/dyes-store/dyes-stock-summary-report/daily/excel"
                       data-toggle="tooltip" data-placement="top" title="EXCEL">
                       <em style="color: #0F733B" class="fa fa-file-excel-o"></em>
                    </a>
                </span>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('dyes-store::report.dyes_stock_summary.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
