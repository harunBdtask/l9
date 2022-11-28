<!Doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sub Dyeing Goods Delivery PDF</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <style>
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: white;
            font: 10pt 'Calibri';
            color: #000;
        }

        .page {
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
        }

        .header-section {
            padding: 10px;
        }

        .body-section {
            padding: 0 10px 10px;
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


        th {
            padding: 0;
        }

        td {
            padding: 1px 2px;
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

        @page {
            size: A4;
            margin: 5mm 10mm;
        }

        @media print {
            html, body {
                width: 210mm;
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
            left: 0;
            right: 0;
            height: 50px;
        }
    </style>
</head>

<body style="background: white;">
<main>
    <div class="page">
        <div style="width: 100%" class="header-section">
            @includeIf('subcontract::pdf.header')
        </div>
        <div style="width: 100%">
            @include('subcontract::textile_module.sub-dyeing-goods-delivery.bill.pdf-body')
        </div>

    </div>
</main>
</body>
</html>
