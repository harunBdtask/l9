<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Budget</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <style type="text/css">
        .v-align-top td, .v-align-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: white;
            font: 10pt "Tahoma";
        }

        .page {
            width: 190mm;
            min-height: 297mm;
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

        table.border {
            border: 1px solid black;
            width: 20%;
            margin-left: auto;
            margin-right: auto;
        }

        .borderless td, .borderless th {
            border: none;
        }

    </style>
</head>

<body style="background: white;">
<div class="page">
    <div class="">
        <div class="header-section" style="padding-bottom: 0px;">
            <table class="borderless">
                <thead>
                <tr>
                    <td class="text-left">
                        <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                        {{ factoryAddress() }}
                        <br>
                    </td>
                    <td>
                        Booking No: <span> {{ $trimsBookings->unique_id ?? '' }}</span><br>
                        Booking Date: <span> {{ $trimsBookings->booking_date ?? ''}}</span><br>
                        Revise No: <span> {{ $trimsBookings->revised_no ?? '' }}</span><br>
                    </td>
                </tr>
                </thead>
            </table>
            <hr>
        </div>
        <br>

        <div class="body-section" style="margin-top: 0px;">
            <table class="borderless">
                <tr>
                    <th colspan="2" style="text-align: center"><b><u>PURCHASE ORDER</u></b></th>
                </tr>
                <tr>
                    <th style="width: 250px">SUPPLIER:</th>
                    <td>{{ isset($trimsBookings) ? optional($trimsBookings->supplier)->name : '' }}</td>
                </tr>
                <tr>
                    <th style="width: 250px">BUYER:</th>
                    <td>{{ isset($trimsBookings) ? optional($trimsBookings->buyer)->name : ''}}</td>
                </tr>
                <tr>
                    <th style="width: 250px">ATTN:</th>
                    <td>{{ $trimsBookings->attention ?? '' }}</td>
                </tr>
                <tr>
                    <th style="width: 250px">ORDER DATE:</th>
                    <td>{{  $trimsBookings->booking_date ?? '' }}</td>
                </tr>
                <tr>
                    <th style="width: 250px">REVISION NO:</th>
                    <td></td>
                </tr>
                <tr>
                    <th style="width: 250px">ISSUED BY:</th>
                    <td>{{ $trimsBookings->issued_by ?? '' }}</td>
                </tr>
                <tr>
                    <th style="width: 250px">APPROVED BY:</th>
                    <td></td>
                </tr>
            </table>

            @if(isset($trimsBookings) && count((optional($trimsBookings)->trimsDetails)) > 0)
                <div style="margin-top: 15px;">
                    <table>
                        <tr>
                            <td colspan="13" class="text-center"><b>TRIMS DETAILS</b></td>
                        </tr>
                        <tr>
                            <th>SL</th>
                            <th>STYLE</th>
                            <th>PO</th>
                            <th>GMT COLOR</th>
                            <th>GMT SIZE</th>
                            <th>GMT QTY</th>
                            <th>UNIT</th>
                            <th>ITEM DESCRIPTION</th>
                            <th>ITEM COLOR</th>
                            <th>ITEM SIZE</th>
                            <th>UOM</th>
                            <th>BOOKING QTY</th>
                            <th>REMARKS</th>
                        </tr>

                        @foreach($trimsBookings->trimsDetails as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $item['style_name'] }}</td>
                                <td>{{ $item['po'] }}</td>
                                <td>{{ $item['gmt_color'] }}</td>
                                <td>{{ $item['gmt_size'] }}</td>
                                <td>{{ $item['gmt_qty'] }}</td>
                                <td>{{ $item['unit'] }}</td>
                                <td>{{ $item['item_description'] }}</td>
                                <td>{{ $item['item_color'] }}</td>
                                <td>{{ $item['item_size'] }}</td>
                                <td>{{ $item['uom'] }}</td>
                                <td>{{ $item['booking_qty'] }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif



        </div>


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
</body>
</html>
