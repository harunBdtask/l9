<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gate Pass Challan</title>
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

        table, th, td {
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

        .borderless td, .borderless th {
            border: none;
        }

        @page {
            size: A4;
            margin: 5mm;
            margin-left: 10mm;
            margin-right: 10mm;
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
        <table class="borderless">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                    {{ factoryAddress() }}
                    <br>
                </td>
            </tr>
            </thead>
        </table>
        <table>
            <tr>
                <td style="border: none">
                    <p style="font-weight: 500; font-size: 16px;">CONSIGNEE/FROM: </p>
                    <span
                        style="font-size: 20px; font-weight: bold; text-decoration: underline;">{{ $data['factory']['factory_name'] ?? '' }}</span><br>
                    <span style="font-size: 16px;">{{ $data['factory']['factory_address'] ?? '' }}</span>
                </td>
                <td style="border: none">
                    <p style="font-weight: 500; font-size: 16px;">BENEFICIARY/TO: </p>
                    <span
                        style="font-size: 20px; font-weight: bold; text-decoration: underline;">{{ $data['party']['name'] ?? '' }}</span><br>
                    <span>Attn: {{ $data['party_attn'] ?? '' }}</span><br>
                    <span>Contact: {{ $data['party_contact_no'] ?? '' }}</span><br>
                    <span>{{ $data['supplier_address'] ?? '' }}</span><br>
                </td>
                <td style="border-left: none; text-align: right; padding-right: 10px;">
                    <span><?php echo DNS1D::getBarcodeSVG($data['barcode'] ?? '', "C128A", 1.9, 40, '', false); ?></span>
                </td>
            </tr>
        </table>
        <br>
        @include('merchandising::gate-pass-challan.report.table')

        <p style="margin-top: 1rem"><strong>Remarks: </strong>{{ $data['remarks'] }}</p>

        @include('skeleton::reports.downloads.signature')

        <div style="margin-top: 10px !important;" style="display:flex;">
            @if ($data->is_approve)
                @foreach($signatures as $signature)
                    @if($signature && File::exists('storage/'.$signature))
                        <img src="{{asset('storage/'. $signature)}}"
                             class="ml-3" width="300px" height="70px" alt="signature">
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</main>
</body>
</html>
