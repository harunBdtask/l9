@extends('skeleton::layout')
@section('title','Sales Contract')
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
            size: A4;
            margin: 5mm;
            margin-left: 15mm;
            margin-right: 15mm;
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
                        <div class="row">
                            <div class="col-md-8">
                                <table class="borderless">
                                    <thead>
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{asset('storage/company/company_logo.png')}}" alt="logo" width="50" height="50">
                                        </td>
                                        <td><h1>{{ factoryName() }}</h1></td>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="pull-right" style="margin-bottom: -5%;">
                                {{--                            <a class="btn print"--}}
                                {{--                               href="{{url('commercial/export-lc/'.$contract->id.'/print')}}"><i--}}
                                {{--                                    class="fa fa-print"></i>--}}
                                {{--                            </a>--}}
                                <a class="btn"
                                   href="{{url('commercial/sales-contract/'.$id.'/pdf')}}"><i
                                            class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="view-body">
                        <div>
                            <center>
                                <table class="borderless" style="width: 20%;">
                                    <thead>
                                    <tr>
                                        <td class="text-center">
                                            <span style="font-size: 12pt; font-weight: bold;">PURCHASE CONTRACT</span>
                                            <br>
                                        </td>
                                    </tr>
                                    </thead>
                                </table>
                            </center>
                            <br>
                            @include('commercial::sales-contract.report.view-body')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push("script-head")
    <script>
        $(document).ready(function () {
            $('.print').click(function (e) {
                e.preventDefault();

                var url = $(this).attr('href');

                printPage(url);
            });

            function closePrint() {
                document.body.removeChild(this.__container__);
            }

            function setPrint() {
                this.contentWindow.__container__ = this;
                this.contentWindow.onbeforeunload = closePrint;
                this.contentWindow.onafterprint = closePrint;
                this.contentWindow.focus(); // Required for IE
                this.contentWindow.print();
            }

            function printPage(sURL) {
                var oHiddFrame = document.createElement("iframe");
                oHiddFrame.onload = setPrint;
                oHiddFrame.style.visibility = "hidden";
                oHiddFrame.style.position = "fixed";
                oHiddFrame.style.right = "0";
                oHiddFrame.style.bottom = "0";
                oHiddFrame.src = sURL;
                document.body.appendChild(oHiddFrame);
            }
        });
    </script>
@endpush
