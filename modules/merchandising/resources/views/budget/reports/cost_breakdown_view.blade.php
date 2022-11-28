@extends('skeleton::layout')
@section('title','Cost Breakdown Print')
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
            font-size: 10px;
        }

        th, td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
            height: 20px;
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
                        <div class="pull-right" style="margin-bottom: -5%;">

                            @if($type == 'view-1')
                                <a class="btn print1"
                                   href="javascript:void(0)"><i
                                        class="fa fa-print"></i>
                                </a>
                                <a class="btn"
                                   href="{{url('budgets/' . $mainPartData['id'] .'/cost-breakdown-pdf/view-1')}}"><i
                                        class="fa fa-file-pdf-o"></i></a>
                                <a class="btn"
                                   href="{{url('budgets/' . $mainPartData['id'] .'/cost-breakdown-excel/view-1')}}?page=excel"><i
                                        class="fa fa-file-excel-o"></i></a>
                            @endif
                            @if($type == 'view-2')
                                <a class="btn print2"
                                   href="javascript:void(0)"><i
                                        class="fa fa-print"></i>
                                </a>
                                <a class="btn"
                                   href="{{url('budgets/' . $mainPartData['id'] .'/cost-breakdown-pdf/view-2')}}"><i
                                        class="fa fa-file-pdf-o"></i></a>
                                <a class="btn"
                                   href="{{url('budgets/' . $mainPartData['id'] .'/cost-breakdown-excel/view-2')}}?page=excel"><i
                                        class="fa fa-file-excel-o"></i></a>
                            @endif
                            @if($type == 'view-akcl')
                                <a class="btn"
                                   href="{{url('budgets/' . $mainPartData['id'] .'/cost-breakdown-pdf/view-akcl')}}"><i
                                        class="fa fa-file-pdf-o"></i></a>
                                <a class="btn"
                                   href="{{url('budgets/' . $mainPartData['id'] .'/cost-breakdown-excel/view-akcl')}}?page=excel"><i
                                        class="fa fa-file-excel-o"></i></a>
                            @endif
                        </div>
                    </div>
                    <div class="view-body" style="margin-top: 50px">
                        @include('merchandising::budget.reports.cost_breakdown_view-body')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push("script-head")
    <script>
        $(document).ready(function () {
            $('.print1').click(function (e) {
                e.preventDefault();

                let url = "{{url('budgets/' . $mainPartData['id'] .'/cost-breakdown-print/view-1')}}";
                printPage(url);
            });
            $('.print2').click(function (e) {
                e.preventDefault();

                let url = "{{url('budgets/' . $mainPartData['id'] .'/cost-breakdown-print/view-2')}}";
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
                let oHiddFrame = document.createElement("iframe");
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
