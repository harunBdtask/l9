@extends('skeleton::layout')
@section('title','Export Import Status')
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
                <h2>Export Import Status</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    {!! Form::open(['url'=>'/commercial/export-import-status', 'method'=>'get']) !!}
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>From</label>
                                    {!! Form::date('from_date', request('from_date') ?? \Carbon\Carbon::now(), ['class'=>'form-control form-control-sm', 'id'=>'from_date']) !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>To</label>
                                    {!! Form::date('to_date',request('to_date') ?? \Carbon\Carbon::now(), ['class'=>'form-control form-control-sm', 'id'=>'to_date']) !!}
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                        title="search" id="search" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            <div class="col-sm-5"></div>
                            <div class="col-sm-2 text-right" style="margin-top: 1%">
                                <a href="{{ url('commercial/export-import-status-pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div>
                    <br>
                    <div>
                        @includeIf('commercial::reports.export-import-status.table')
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
            </div>
        </div>
    </div>
@endsection

