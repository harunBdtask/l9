@extends('skeleton::layout')
@section('title','Export LC Status')
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
            text-align: center;
            border-color: #aaa;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }
        .p0{
            padding: 0px !important;
        }
        .multi{
            border-bottom: 1px solid #000;
            display: block;
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
                <h2>Export LC Status</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    {!! Form::open(['url'=>'/commercial/export-lc-status', 'method'=>'get']) !!}
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Company</label>
                                    {!! Form::select('factory', $factories, request()->get('factory'), ['class'=>'form-control form-control-sm select2-input', 'id'=>'factory', 'required'=>true]) !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Year</label>
                                    {!! Form::selectRange('year', date('Y'), date('Y')-15, request()->get('year')??date('Y'), ['class'=>'form-control form-control-sm select2-input', 'id'=>'year','required'=>true]); !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Month</label>
                                    {!! Form::selectMonth('month', request()->get('month')??date('n'), ['class'=>'form-control form-control-sm select2-input', 'id'=>'month', 'required'=>true]); !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Export LC Number</label>
                                    {!! Form::select('export_lc_no',$export_lcs, request()->get('export_lc_no')??null, ['class'=>'form-control form-control-sm select2-input', 'id'=>'buyer']) !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Buyer</label>
                                    {!! Form::select('buyer',$buyers, request()->get('buyer'), ['class'=>'form-control form-control-sm select2-input', 'id'=>'buyer']) !!}
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                        title="search" id="search" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-12 text-right" style="margin-top: 1%">
                                <a class="btn btn-default" href="{{ url('commercial/export-lc-status-pdf?'.http_build_query(request()->query())) }}"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div>

                    <div>
                        @includeIf('commercial::reports.export-lc-status.table')
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
            </div>
        </div>
    </div>
@endsection
