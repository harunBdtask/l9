<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Trims Booking Sheet</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <style type="text/css">
        .v-align-top td,
        .v-algin-top th {
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
            /*min-width: 190mm;*/
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


        th {
            padding-left: 0px;
            padding-right: 0px;
            padding-top: 0px;
            padding-bottom: 0px;
        }

        td {
            padding-left: 2px;
            padding-right: 2px;
            padding-top: 1px;
            padding-bottom: 1px;
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

        @page {
            size: A4;
            margin: 5mm;
            margin-left: 10mm;
            margin-right: 10mm;
        }

        @media print {

            html,
            body {
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

        footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
    </style>
</head>

<body style="background: white;">
    <main>
        <div class="page">
            @if ( request('type') && (request('type') == 'v3'))
            @include('merchandising::booking.reports.view-body-v3-pdf')
            @elseif ( request('type') && (request('type') == 'v4' || request('type') == 'v5' || request('type') == 'v6') )
            @if( request('pdfNumber') == 1 )
            @if(request('type') == 'v6')
            @include('merchandising::booking.reports.view-body-v6-pdf')
            @else
            @include('merchandising::booking.reports.view-body-v4-pdf')
            @endif
            @elseif( request('pdfNumber') == 2)
            @if(request('type') == 'v6')
            @include('merchandising::booking.reports.view-body-v6-type2-pdf')
            @else
            @include('merchandising::booking.reports.view-body-v5-pdf')
            @endif
            @endif
            @elseif(request('type') && (request('type') == 'v7'))
            @include('merchandising::booking.reports.mondol.view-body-v7-pdf')
            @elseif(request('type') && (request('type') == 'v8'))
            @include('merchandising::booking.reports.gears.view-body-v8-pdf')
            @else
            @include('merchandising::booking.reports.view-body-pdf')
            @endif
        </div>
    </main>
</body>

</html>