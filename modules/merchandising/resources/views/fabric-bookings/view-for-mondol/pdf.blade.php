<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Fabric Booking</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: white;
            font: 8pt "Tahoma";
        }

        /*.page {*/
        /*    size: landscape;*/
        /*    width: 190mm;*/
        /*    min-height: 297mm;*/
        /*    margin: 10mm auto;*/
        /*    border-radius: 5px;*/
        /*    background: white;*/
        /*}*/

        .header-section {
            /*padding: 10px;*/
        }

        .body-section {
            /*padding: 10px;*/
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

        /*th, td {*/
        /*    padding-left: 5px;*/
        /*    padding-right: 5px;*/
        /*    padding-top: 3px;*/
        /*    padding-bottom: 3px;*/
        /*}*/

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

        table, tr, th {
            text-align: left;
        }


    </style>
</head>

<body style="background: white;">
<main>
    <div class="page">
        <div class="">
            <div class="header-section" style="padding-bottom: 0px;">
{{--                <table class="borderless">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <td class="text-center">--}}
{{--                            <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>--}}
{{--                            <b>{{ factoryAddress() }}</b><br>--}}
{{--                            <br>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                </table>--}}
                <table class="borderless">
                    <thead>
                    <tr>
                        <td class="text-left">
                            <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                            {{ factoryAddress() }}
                            <br>
                        </td>
                        <td class="text-right">
                            Booking No: <b> {{ $fabricBookings->unique_id ?? '' }}</b><br>
                            Booking Date: <b> {{ $fabricBookings->booking_date ?? ''}}</b><br>
                        </td>
                    </tr>
                    </thead>
                </table>
                <hr>
            </div>
            <br>

            @include('merchandising::fabric-bookings.view-for-mondol.view-body')
            <br>
            <br>
            @include('skeleton::reports.downloads.signature')
        </div>
    </div>
</main>
</body>
</html>
