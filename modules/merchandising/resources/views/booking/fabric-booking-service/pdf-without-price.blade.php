<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Fabric Service Booking</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <style type="text/css">
        .v-align-top td,
        .v-align-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: white;
            font: 9pt "Tahoma";
        }

        .page {
            /*width: 190mm;*/
            /*min-height: 297mm;*/
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
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
            page-break-before: avoid;
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

        table.border {
            border: 1px solid black;
            width: 20%;
            margin-left: auto;
            margin-right: auto;

        }

        .borderless td,
        .borderless th {
            border: none;
        }

        .body-section .borderless td,
        th {
            text-align: left;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
    </style>
</head>

<body style="background: white;">
    <main>
        <div class="page">
            <table class="borderless">
                <thead>
                    <tr>
                        <td class="text-left">
                            <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                            {{ factoryAddress() }}
                            <br>
                        </td>
                        <td class="text-right" style="text-align: right;">
                            Booking No: <b> {{ $bookings->booking_no ?? '' }}</b><br>
                            Booking Date: <b> {{ $bookings->booking_date ? \Carbon\Carbon::parse($bookings->booking_date)->format('d-M-Y') : '' }}</b><br>
                        </td>
                    </tr>
                </thead>
            </table>
            <hr>
            <div class="body-section" style="margin-top: 0px;">
                <table class="border">
                    <thead>
                        <tr>
                            <td class="text-center">
                                <span style="font-size: 12pt; font-weight: bold;"><span>Fabric Service Bookings</span></span>
                                <br>
                            </td>
                        </tr>
                    </thead>
                </table>
                <br>
                <div class="row">
                    <div class="col-md-4" style="float: left; position:relative; margin-top:30px">
                        <table class="borderless">
                            <tbody>
                                <tr>
                                    <td style="padding-left: 0;" class="text-left">
                                        <strong>SUPPLIER :</strong>
                                    </td>
                                    <td style="padding-left: 30px;" class="text-left"> {{ isset($bookings) ? optional($bookings->supplier)->name : ''}} </td>
                                </tr>

                                <tr>
                                    <td style="padding-left: 0;" class="text-left">
                                        <strong>ATTN :</strong>
                                    </td>
                                    <td style="padding-left: 30px;" class="text-left">{{ $bookings->attention ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 0;" class="text-left">
                                        <strong>ORDER DATE :</strong>
                                    </td>
                                    <td style="padding-left: 30px;" class="text-left">{{ $bookings->booking_date ? \Carbon\Carbon::parse($bookings->booking_date)->format('d-M-Y') : '' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 0;" class="text-right">
                                        <strong>PROCESS:</strong>
                                    </td>
                                    <td style="padding-left: 30px;" class="text-right">{{ $bookings->processInfo->process_name ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4" style="float: left; position:relative; margin-top:30px">

                    </div>
                    <div class="col-md-4" style="float: right; position:relative;margin-top:30px">
                        <table class="borderless">
                            <tbody>
                                <tr>
                                    <td style="padding-left: 0;" class="text-right">
                                        <strong>BOOKING DATE:</strong>
                                    </td>
                                    <td style="padding-left: 30px;" class="text-right"> {{ isset($bookings->booking_date) ? \Carbon\Carbon::make($bookings->booking_date)->format('d-M-Y') : '' }} </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 0;" class="text-right">
                                        <strong>DELIVERY DATE:</strong>
                                    </td>
                                    <td style="padding-left: 30px;" class="text-right"> {{ isset($bookings->delivery_date) ? \Carbon\Carbon::make($bookings->delivery_date)->format('d-M-Y') : '' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 0;" class="text-right">
                                        <strong>APPROVAL STATUS:</strong>
                                    </td>
                                    <td style="padding-left: 30px;" class="text-right"> {{ $bookings->is_approved == 1 ? 'APPROVED' : 'UNAPPROVED' }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @php
                $totalWoQty = 0;
                $totalAmount = 0;
                @endphp

                @if(isset($bookings) && count((optional($bookings)->details)) > 0)
                <div style="margin-top: 15px;">
                    <table>
                        <tr>
                            <td colspan="19" class="text-center"><b>FABRIC DETAILS</b></td>
                        </tr>
                        <tr>
                            <th rowspan="2" style="max-width: 15px">SL</th>
                            <th rowspan="2">STYLE <br> & PO</th>
                            <th rowspan="2">COLOR <br> & LABDIP</th>
                            <th rowspan="2">FABRIC DESCRIPTION</th>
                            <th rowspan="2">COUNT, LOT <br> COMPOSITION, BRAND</th>
                            <th colspan="3" class="text-center">DIA</th>
                            <th rowspan="2">S.L</th>
                            <th rowspan="2">GAUGE</th>
                            <th rowspan="2">WO QTY</th>
                            <th rowspan="2">UOM</th>
                            <tr>
                            <th>M/C</th>
                            <th>FINISH</th>
                            <th>GSM</th>
                        </tr>

                        @foreach($bookings->FabricServiceDetails as $key => $item)
                        @php
                        $totalWoQty += $item['wo_qty'];
                        $totalAmount += $item['amount'];
                        @endphp
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>
                                {{ $item['style_name'] }},
                                {{ $item['po_no'] }}
                            </td>
                            <td>
                                {{ $item['gmts_color'] }},
                                {{ $item['labdip_no'] }},
                            </td>
                            <td>{{ $item['fabric_description'] }}</td>
                            <td>
                                {{ $item['yarn_count'] }},
                                {{ $item['lot'] }},
                                {{ $item['yarn_composition'] }},
                                {{ $item['brand'] }},
                            </td>
                            <td>{{ $item['mc_dia'] }}</td>
                            <td>{{ $item['finish_dia'] }}</td>
                            <td>{{ $item['finish_gsm'] }}</td>
                            <td>{{ $item['stich_length'] }}</td>
                            <td>{{ $item['mc_gauge'] }}</td>
                            <td>{{ round($item['wo_qty']) }}</td>
                            <td>{{ $item['uom'] }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-right" colspan="10"><b>Total</b></td>
                            <td class="text-right"><b>{{ round($totalWoQty) }}</b></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                @endif
            </div>
            <div style="margin-top: 16mm">
                <table class="borderless">
                    <tbody>
                        <tr>
                            <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
                        </tr>
                        @if(isset($termsConditions))
                        @foreach($termsConditions as $item)
                        <tr>
                            <td>{{ '* '. $item->terms_name }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @include('skeleton::reports.downloads.signature')
        </div>
    </main>
</body>

</html>
