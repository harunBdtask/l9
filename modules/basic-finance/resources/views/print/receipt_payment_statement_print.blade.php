<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Receipt and Payment Statement</title>

    <link href="{{ asset('modules/skeleton/css/print.css') }}" rel="stylesheet" type="text/css"/>

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
            background-color: #FAFAFA;
            font: 10pt "Tahoma";
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
</head>

<body>
<div class="page">
    <div style="margin-bottom: 50px;">
        @include('basic-finance::includes.pdf_header')
    </div>

    <div class="body-section" style="margin-top: -10px;">
        <table class="borderless">
            <thead>
            <tr>
                <th class="text-right"></th>
                <th class="text-center" style="padding-right: 180px;"> <h3>Receipt and Payment Statement</h3>
                </th>
            </tr>
            <tr>
                <td nowrap class="text-left" style="padding:0px; margin: 0px">Date Between</td>
                <th nowrap class="text-left" style="padding:0px; margin: 0px">
                    : {{ $start_date->toFormattedDateString() }} to {{ $end_date->toFormattedDateString() }}</th>
            </tr>
            </thead>
        </table>
        <br>
        @includeIf('basic-finance::tables.receipt_payment_statement_table')
        <br>

        <div style="margin-top: 16mm">
            <table class="borderless">
                <tbody>
                <tr>
                    <td class="text-center"><u>Prepared By</u></td>
                    <td class='text-center'><u>Checked By</u></td>
                    <td class='text-center'><u>Audit Department</u></td>
                    <td class='text-center'><u>Manager (Account)</u></td>
                    <td class="text-center"><u>Authorized By</u></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
@includeIf('basic-finance::includes.pdf_footer')
</html>
