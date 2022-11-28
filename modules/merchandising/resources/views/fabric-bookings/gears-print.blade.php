<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Fabric Booking Print</title>


    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/print.css') }}" type="text/css"/>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>

    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            background-color: #FAFAFA;
            font: 10pt "Tahoma";
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            /*width: 210mm;*/
            /*min-height: 297mm;*/
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

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
                /*height: 293mm;*/
            }

            table {
                page-break-inside: avoid;
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
</head>

<body>
<main>

    <div class="page">
        <div class="">
            <div class="header-section" style="padding-bottom: 0px;">
                <table class="borderless">
                    <thead>
                    <tr>
                        <td class="text-center">
                            <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                            <b>{{ factoryAddress() }}</b><br>
{{--                            <span>Tel: +8809610-864328, Mail: info@gears-group.com</span>--}}
                            <br>
                        </td>
                    </tr>
                    </thead>
                </table>
                <hr>
            </div>
            <br>
            @include('merchandising::fabric-bookings.gears-pdf-table')

            @include('skeleton::reports.downloads.signature')

        </div>
        @include('skeleton::reports.downloads.footer')

    </div>

</main>
</body>
</html>
