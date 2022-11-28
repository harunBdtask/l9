@extends('skeleton::layout')
@section('title', 'Sample Trims Issue')
@section('content')
    <style type="text/css">
        .v-align-top td,
        .v-algin-top th {
            vertical-align: top;
        }
        table, th, td {
            vertical-align: top;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        @font-face {
            font-family: myFirstFont;
            src: url("{{ asset('/font/DekoBlackExtended-Serial-Regular-DB_13367.ttf') }}");
        }

        #heading {
            font-family: myFirstFont;
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

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td,
        .borderless th {
            border: none;
        }

        .table-tr-border {
            border-top: none !important;
        }

        p {
            margin-top: 0;
            margin-bottom: 0rem;
        }

        .space {
            margin-bottom: 1rem;
        }

        .custom-width{
            width: 50%;
        }

        @page {
            size: A4;
            margin: 5mm;
            margin-left: 15mm;
            margin-right: 15mm;
        }

        @media print {

            html,
            body {
                width: 210mm;
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
            <div class="box-body table-responsive b-t">
                <div class="header-section" style="padding-bottom: 0px;">
                    <div class="pull-right" style="margin-bottom: -5%;">
                        <a href="/sample-management/trims-issue/list" class="btn btn-xs btn-danger">Back</a>
                        <a class="btn" title="PDF"
                           href="{{url('/sample-management/trims-issue/pdf/'. $sampleTrimsIssue->id)}}">
                           <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a class="btn" title="Excel"
                           href="{{url('/sample-management/trims-issue/excel/'. $sampleTrimsIssue->id)}}">
                           <i class="fa fa-file-excel-o"></i>
                        </a>
                    </div>
                </div>
                <div class="view-body">
                    @includeIf('sample::trims-issue.details')
                </div>
                @if ($sampleTrimsIssue->viewType != 'excel')
                    <br>
                    <br>
                    @include('skeleton::reports.downloads.signature')
                @endif
            </div>
        </div>
    </div>


@endsection
