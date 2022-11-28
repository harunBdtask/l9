<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Yarn Booking</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <style>
        .v-align-top td, .v-align-top th {
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
            width: 190mm;
            min-height: 297mm;
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
        }

        .body-section {
            padding-top: 0;
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

        table.border {
            border: 1px solid black;
            width: 20%;
            margin-left: auto;
            margin-right: auto;
            margin-top: 100px;
        }

        .borderless td, .borderless th {
            border: none;
        }

        table, tr, td, th, tbody, thead, tfoot {
            padding-top: 10px;
            page-break-inside: auto !important;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
        }
    </style>
</head>

<body style="background: white;">
<main>
    <div>
        <div style="width: 100%" class="header-section">
            @includeIf('merchandising::pdf.header', ['name' => 'Yarn Booking'])
        </div>
        <div style="width: 100%">
            @includeIf('merchandising::yarn-purchase.Order.booking-view-body')
        </div>
    </div>
</main>

</body>
</html>
