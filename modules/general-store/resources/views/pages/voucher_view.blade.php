@extends('general-store::layout')
@section('content')
    <style type="text/css">
        .party-info-section {
            float: left;
            width: 40%;
        }

        .challan-info-section {
            float: right;
            width: 40%;
        }

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
                        <div class="pull-right" style="margin-bottom: -5%;">
                            <a class="btn btn-xs btn-primary print"
                               href="{{ url('/general-store/vouchers/' . $voucher->id . '/print') }}"><i
                                    class="fa fa-print"></i></a>
                            <a class="btn btn btn-xs btn-danger"
                               href="{{ url('/general-store/vouchers/' . $voucher->id . '/download') }}"><i
                                    class="fa fa-file-pdf-o"></i></a>
                        </div>
                        <table class="borderless">
                            <thead>
                            <tr>
                                <td class="text-center">
                                <span
                                    style="font-size: 12pt; font-weight: bold;">{{ factoryName() ?? '' }}
                                </span><br>
                                    {{ factoryAddress() ?? '' }}<br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                        <hr>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span
                                        style="font-size: 12pt; font-weight: bold;">{{ get_store_name($voucher->store) }}</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="body-section">
                        <div class="border-box">
                            <div class="party-info-section">
                                <table>
                                    <thead>
                                    <tr>
                                        <th>Report Title</th>
                                        <td>{{ $report_title ?? 'Voucher View' }}</td>
                                    </tr>
                                    <tr>
                                        <th>
                                            @if($voucher->type == 'in')
                                                <b>Receive Date</b>
                                            @elseif($voucher->type == 'out')
                                                <b>Delivery Date</b>
                                            @endif
                                        </th>
                                        <td>{{ $voucher->trn_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reference / Challan No.</th>
                                        <td>{{ $voucher->reference ?? 'NONE' }}</td>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="challan-info-section">
                                <table>
                                    <thead>
                                    <tr>
                                        <th>Type</th>
                                        <td>{{ 'Stock ' . ucfirst($voucher->type) }}</td>
                                    </tr>
                                    <tr>
                                        <th>
                                            @if($voucher->type == 'in')
                                                Supplier
                                            @elseif($voucher->type == 'out')
                                                Consumer
                                            @endif
                                        </th>
                                        <td>
                                            @if($voucher->type == 'in')
                                                <span>{{ $voucher->supplier->name }}</span>
                                            @elseif($voucher->type == 'out')
                                                <span>{{ $voucher->consumer->screen_name ?? '' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

                    <div class="body-section">
                        <table>
                            <thead class="thead-light">
                            <tr>
                                <th class="text-left">Sl No</th>
                                <th class="text-left">Item</th>
                                <th class="text-left">Category</th>
                                @if($voucher->type == "in")
                                    <th class="text-right">Qty</th>
                                @else
                                    <th class="text-right">Delivery Qty</th>
                                @endif
                                <th class="text-right">Rate</th>
                                <th class="text-left">UOM</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($voucher->details as $detail)
                                <tr>
                                    <td class="text-left">{{$loop->iteration}}</td>
                                    <td class="text-left">{{ ucwords($items->where('id', $detail->item_id)->first()->name) }}</td>
                                    <td class="text-left">{{ ucwords($items->where('id', $detail->item_id)->first()->category->name) }}</td>
                                    @if($voucher->type == "in")
                                        <td class="text-right">{{ $detail->qty }}</td>
                                    @else
                                        <td class="text-right">{{ $detail->delivery_qty }}</td>
                                    @endif


                                    <td class="text-right">{{ $detail->rate }}/=</td>
                                    <td class="text-left">{{ $items->where('id', $detail->item_id)->first()->uomDetails->name }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br>

                        <div style="margin-top: 16mm">
                            <table class="borderless">
                                <tbody>
                                <tr>
                                    <td class="text-center"><u>Prepared By</u></td>
                                    <td class="text-center"><u>Authorized By</u></td>
                                    <td class='text-center'><u>Issued By</u></td>
                                    <td class='text-center'><u>Received By</u></td>
                                    <td class='text-center'><u>Kardex Posted By</u></td>
                                    <td class='text-center'><u>Ledger Posted </u></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
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
