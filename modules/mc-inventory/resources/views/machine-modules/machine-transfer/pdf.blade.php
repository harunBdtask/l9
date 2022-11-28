<!Doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Machine Transfer Challan</title>
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
            font: 9pt "Tahoma";
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
        .signature {
            left: 0;
            bottom: 0;
            height: 30px;
            width: 100%;
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
        </div>
        <div style="width: 100%">
            @include('McInventory::machine-modules.machine-transfer.view-body')
        </div>
        <div class="signature" style="margin-top: 70px;">
            <table class="borderless">
                <tbody>
                <tr>
                    <td class="text-left"><u>MECHANIC INCHARGE SIGNATURE</u></td>
                    <td class='text-left'><u>AGM/GM SIGNATURE</u></td>
                    <td class="text-right"><u>ED SIGNATURE</u></td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</main>
</body>
</html>
