<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Knit Card</title>
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

        .signature {
            left: 0%;
            bottom: 0%;
            height: 30px;
            width: 100%;
        }

        /* IE 6 */
        * html .signature {
            top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
        }
</style>

    </style>
</head>

<body style="background: white;">
<main>
    <div>
        <div style="width: 100%;" class="header-section">
            @includeIf('knitting::pdf.header')
            <div style="text-align: center; margin-top: -30px;">
                <span style="font-size: 22px; font-weight: bold;">Knit Card</u><br>
                <span style="width: 9%;">&nbsp;<?php echo DNS1D::getBarcodeSVG(($data->knit_card_no), "C128A", 2, 24, '', false); ?> &nbsp;</span>
                <p style="font-size: 14px; font-weight: normal; margin-top:0;">{{ $data->knit_card_no ?? '' }}</p>
            </div>
        </div>

        <div class="body-section" style="margin-top: 0px;">
            @includeIf('knitting::knit-card.view-body')
        </div>

        <div class="signature" style="margin-top: 60px;">
            <table class="borderless">
                <tbody>
                <tr>
                    <td class="text-center">
                        <span style="border-top: 1px solid black;">PREPARED BY</span>
                    </td>
                    <td class='text-center'>
                        <span style="border-top: 1px solid black;">CHECKED BY</span>
                    </td>
                    <td class="text-center">
                        <span style="border-top: 1px solid black;">QC SIGNATURE</span>
                    </td>
                    <td class="text-center">
                        <span style="border-top: 1px solid black;">FEEDER MAN</span>
                    </td>
                    <td class="text-center">
                        <span style="border-top: 1px solid black;">AUTHORIZED BY</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</main>
</body>
</html>
