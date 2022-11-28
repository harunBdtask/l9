<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>B2B Margin LC</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding-left: 13px;
            background-color: white;
            font: 10pt "Tahoma";
        }

        .page {
            background: white;
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
            border: 1px solid white;
            padding-top: 0;
            margin: 0;
            vertical-align: top;
        }

        table.borderless {
            border: none;
        }

        table.border {
            border: 1px solid white !important;
            width: 20%;
            margin-left: auto;
            margin-right: auto;
        }

        .borderless td, .borderless th {
            border: none;
        }

        .body-section .borderless td, th {
            text-align: left;
        }

        footer {
            position: fixed;
        }
    </style>
</head>

<body style="background: white;">
<main>
    <div class="page" style="padding-top: 1.2in">
        <div>
            <p>
                <b>DATE: {{ isset($b2BMarginLC->application_date) ? \Carbon\Carbon::make($b2BMarginLC->application_date)->format('d.m.Y') : null }}</b>
            </p>
            <p><b>TO</b></p>
            <p><b>{{ $b2BMarginLC->lienBank->contact_person }}</b></p>
            <p>{{ $b2BMarginLC->lienBank->name }}<br>{!! $b2BMarginLC->lienBank->address !!}</p>
            <p><b>SUBJECT: <u>APPLICATION FOR OPENING OF BACK TO BACK LC FOR
                        @for($i = 0; $i<34; $i++)
                            &nbsp;
                        @endfor
                        <span>${{ number_format($b2BMarginLC->lc_value, 2) }}</span></u></b>
            </p>
            <p><b>DEAR SIR</b></p>
            <p><b>WE SHALL BE HIGHLY PLEASE IF YOU KINDLY OPEN A BACK TO BACK LC FOR US
                    <span
                        style="float: right;margin-right: 20%;">${{ number_format($b2BMarginLC->lc_value, 2) }}</span></b>
            </p>
            <p><b>THE DETAILS PARTICULARS OF THE LC'S AS GIVEN BELOW:</b></p>
            <table>
                <tbody>
                <tr>
                    <td style="width:200px !important;"><b>APPLICANT</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>{{ $b2BMarginLC->factory->factory_name }}
                            <br>{{ $b2BMarginLC->factory->factory_address }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>BENEFICIARY</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>{{ $b2BMarginLC->supplier->name }}<br>{{ $b2BMarginLC->supplier->address_1 }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>ADVISING BANK</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b></b>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>SWIFT CODE</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>{{ $b2BMarginLC->lienBank->swift_code ?? '' }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>CREDIT AMOUNT</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>${{ number_format($b2BMarginLC->lc_value, 2) }}</b></td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>TENNOR</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>AT {{ $b2BMarginLC->tenor }} DAYS FROM THE DATE OF {{ $tennorStatus }}</b></td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>COMMODITY</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>{{ strtoupper($b2BMarginLC->item->item_name) }}
                            FOR {{ $b2BMarginLC->garments_qty }} {{ $b2BMarginLC->unitOfMeasurement->unit_of_measurement }}
                            EXPORT ORIENTED READYMADE GARMENTS INDUSTRIES AS
                            PER {{ $b2BMarginLC->proformaInvoice }}
                            OF
                            THE BENEFICIARY</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>SHIPMENT DATE</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td>
                        <b>{{ isset($b2BMarginLC->last_shipment_date) ? \Carbon\Carbon::make($b2BMarginLC->last_shipment_date)->format('d.m.Y') : null }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>EXPIRY DATE</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td>
                        <b>{{ isset($b2BMarginLC->lc_expiry_date) ? \Carbon\Carbon::make($b2BMarginLC->lc_expiry_date)->format('d.m.Y') : null }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>PARTIAL SHIPMENT</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>ALLOWED</b></td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>TRANS SHIPMENT</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>ALLOWED</b></td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>SHIPMENT FROM</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>BENEFICIARY'S FACTORY</b></td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>FOR TRANSPORTATION TO</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>APPLICANTS FACTORY</b></td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>TERMS AND CONDITIONS</b></td>
                    <td><b>&nbsp;:&nbsp;</b></td>
                    <td><b>(01) {{ $exportLc->pluck('lc_no_date')->implode(', ') }} (2) MATURITY
                            DATE SHOULD BE COUNTED {{ $b2BMarginLC->tenor }} DAYS FROM THE DATE OF DELIVERY AND
                            PAYMENT IN US DOLLAR BY BANGLADESH BANK FDD.</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px !important;"><b>BANK FILE NO: </b></td>
                    <td><b>:</b></td>
                    <td><b>{{ $exportLc->pluck('bank_file_no')->implode(', ') }}</b></td>
                </tr>
                </tbody>
            </table>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <b>THANKING YOU</b>
    </div>
</main>
</body>
</html>
