@extends('skeleton::layout')
@section('title','Fabric Receive View')
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
            @if(Session::has('success'))
                <div class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <small>{{ Session::get('success') }}</small>
                </div>
            @elseif(Session::has('error'))
                <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <small>{{ Session::get('error') }}</small>
                </div>
            @endif
            <div class="box-body table-responsive b-t">
                <div class="">
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: -5%;">
                            @if($receive)
                                <a class="btn"
                                   href="{{url('inventory/fabric-receives/'. $receive->id.'/pdf')}}"><i
                                        class="fa fa-file-pdf-o"></i>
                                </a>
                                <a class="btn"
                                   href="{{url('inventory/fabric-receives/'. $receive->id.'/excel')}}"><i
                                        class="fa fa-file-excel-o"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="view-body">
                        <div>
                            <div>
                                <div>
                                    <div>
                                        <br>
                                        <hr>
                                    </div>
                                    <center>
                                        <table style="border: 1px solid black;width: 20%;">
                                            <thead>
                                            <tr>
                                                <td class="text-center">
                                                    <span style="font-size: 12pt; font-weight: bold;">FABRIC RECEIVE VIEW</span>
                                                    <br>
                                                </td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </center>
                                    <br>
                                </div>
                                @include('inventory::fabrics.pages.fabric_receive_view.view_body')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
