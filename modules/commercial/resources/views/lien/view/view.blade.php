@extends('skeleton::layout')
@section('title','Lien')
@section('content')
    <style>
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .v-align-top td,
        .v-algin-top th {
            vertical-align: top;
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
            padding: 0px 10px 10px;
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
            padding: 3px 5px;
        }
        table.borderless {
            border: none;
        }
        .borderless td, .borderless th {
            border: none;
        }
        @page {
            size: A4;
            margin: 5mm 15mm;
        }
        @media print {
            html, body {
                width: 210mm;
            }
            .page {
                margin: 0;
                width: initial;
                border: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                border-radius: initial;
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
                            <a class="btn print"
                               href="{{url('commercial/lien/'.$id.'/excel')}}">
                                <i class="fa fa-file-excel-o"></i>
                            </a>
                            <a class="btn"
                               href="{{url('commercial/lien/'.$id.'/pdf')}}">
                                <i class="fa fa-file-pdf-o"></i>
                            </a>
                        </div>
                    </div>
                    <div class="view-body">
                        <div>
                            <center>
                                <table style="border: 1px solid black;width: 20%;">
                                    <thead>
                                    <tr>
                                        <td class="text-center">
                                            <span style="font-size: 12pt; font-weight: bold;">Lien</span>
                                            <br>
                                        </td>
                                    </tr>
                                    </thead>
                                </table>
                            </center>
                            <br>
                            @include('commercial::lien.view.table')
                            <div style="margin-top: 16mm">
                                <table class="borderless">
                                    <tbody>
                                    <tr>
                                        <td class="text-center"><u>Prepared By</u></td>
                                        <td class='text-center'><u>Checked By</u></td>
                                        <td class="text-center"><u>Approved By</u></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
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
