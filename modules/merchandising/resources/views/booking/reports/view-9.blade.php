@extends('skeleton::layout')
@section('title','goRMG | Trims Booking')
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
            <div class="box-body table-responsive b-t">
                <div class="">
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: 0%;">
                            {{--                            @if($trimsBookings)--}}
                            {{--                                <a class="btn print" href="{{url('trims-bookings-views/'.  $trimsBookings->id .'/print')}}"><i--}}
                            {{--                                        class="fa fa-print"></i>--}}
                            {{--                                </a>--}}
                            {{--                            @endif--}}

                            <a class="btn btn-xs btn-success" target="__blank" title="Rate & Amount Wise Pdf"
                               href="{{url('trims-bookings-views/'. $trimsBookings->id.'/pdf/view-9' )}}">
                                <i class="fa fa-file-pdf-o"></i>
                            </a>
                            <a class="btn btn-xs btn-warning" target="__blank" title="Rate & Amount Without Pdf"
                               href="{{url('trims-bookings-views/'. $trimsBookings->id.'/pdf/view-9?pdf-type=2' )}}">
                                <i class="fa fa-file-pdf-o"></i>
                            </a>

                            <a class="btn btn-xs btn-info" target="__blank" title=""
                               href="{{url('trims-bookings-views/'. $trimsBookings->id.'/excel/view-9?type=v9' )}}">
                                <i class="fa fa-file-excel-o"></i>
                            </a>

                        </div>
                    </div>
                    <div class="view-body">
                        @include('merchandising::booking.reports.view-body-v9')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
