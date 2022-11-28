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
            <table class="borderless">
                <tr>
                    <td style="width: 25%; border: none;">
                        <table style="border: none;">
                            <tr id="pdfGenerateInfo" style="border: none;">
                                <td rowspan="4">
                                    @if(factoryImage() && File::exists('storage/factory_image/'.factoryImage()))
                                        <img
                                            src="{{ asset('storage/factory_image/'. factoryImage()) }}"
                                            alt="Logo" style="min-width:100px;max-width:200px;max-height:200px;">
                                    @else
                                        <img src="{{ asset('images/no_image.jpg') }}" width="100"
                                             alt="no image">
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%; border: none;">
                        <table style="border: none;">
                            <tr id="pdfGenerateInfo" style="text-align: center; border: none;">
                                <td style="color: black; text-align: center;">
                                    <b style="font-size: 14px;">{{ $name ?? null}}</b> <br>
                                    <span
                                        style="text-align: center;{{ isset($name) ? 'font-size:13px' : 'font-size:15px;font-weight: bold;' }};color: black;">
                            {{ factoryName() }}
                        </span><br>
                                    <span style="text-align: right;font-size: 11px; color: black;">
                            {{ factoryAddress() }}
                        </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 25%; border: none;">
                        <table style="border: none;">
                            <tr id="pdfGenerateInfo" style="border: none;">
                                <td style="text-align: right; width: 30%; font-size: 10px; color: black;">
                        <span style="padding-right: 2%;">
                            Printed By- {{ auth()->user()->first_name }}
                        </span><br>
                                    <span style="padding-right: 2%;">
                            Print Date- {{ date('d-m-Y H:i') }}
                        </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <hr style="background: black">
            <br>
            <table class="table" style="margin-top: 10px; border:none;">
                <tr>

                    <td style="text-align: center; width: 50%; border:none;">
                            <span style="font-size: 18px; font-weight: bold;">KNIT CARD</u><br>
                            <span style="width: 9%;">&nbsp;<?php echo DNS1D::getBarcodeSVG(($data->knit_card_no), "C128A", 2, 24, '', false); ?> &nbsp;</span><br>
                            <span style="font-size: 14px;">{{ $data->knit_card_no }}</span>
                    </td>

                </tr>
            </table>
        </div>

        <div class="body-section" style="margin-top: 0px;">
            @includeIf('knitting::knit-card.view-2-body')
        </div>

        <div class="signature" style="margin-top: 60px;">
            <table class="borderless">
                <tbody>
                <tr>
                    <td class="text-center">
                        <span style="border-top: 1px solid black;">Prepared By</span>
                    </td>
                    <td class='text-center'>
                        <span style="border-top: 1px solid black;">In Charge/Production Officer</span>
                    </td>
                    <td class="text-center">
                        <span style="border-top: 1px solid black;">Asst.Manager/Manager</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</main>
</body>
</html>
