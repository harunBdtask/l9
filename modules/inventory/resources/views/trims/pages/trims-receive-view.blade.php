@extends('skeleton::layout')
@section('title','Trims Receive View')
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
    </style>

    <div class="padding">
        <div class="box">
            <div class="box-body table-responsive b-t">
                <div class="">
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: -5%;">

                                <a class="btn"
                                   href="{{url('inventory/trims-receives/'. $trimsReceive->id.'/pdf')}}"><i
                                        class="fa fa-file-pdf-o"></i>
                                </a>

                        </div>
                    </div>
                    <div class="view-body">
                        <div>
                            <div>
                                <br>
                                <hr>
                            </div>

                            @include('inventory::trims.pages.trims-receive-view-body')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
