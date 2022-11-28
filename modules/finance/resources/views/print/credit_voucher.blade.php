<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!-- begin::Head -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Lead Time Bangladesh</title>

        <link href="{{ asset('modules/skeleton/css/print.css') }}" rel="stylesheet" type="text/css" />

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

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
            <div class="header-section" style="padding-bottom: 0px;">
                <table class="borderless">
                    <thead>
                        <tr>
                            <td class="text-center">
                                <span style="font-size: 12pt; font-weight: bold;">{{ companyName() }}</span><br>
                                {{ companyAddress() }} <br> &nbsp;
                            </td>
                        </tr>
                    </thead>
                </table>
                <hr>
            </div>

            <div class="body-section" style="margin-top: -10px;">
                <table class="borderless" style="width: 50%; float: right">
                    <thead>
                        <tr>
                            <th nowrap class="text-right" style="padding:0px; margin: 0px">Transaction Date :</th>
                            <td nowrap class="text-right" width="1%">{{ $voucher->trn_date->toFormattedDateString() }}</td>
                        </tr>
                        <tr>
                            <th nowrap class="text-right" style="padding:0px; margin: 0px">File No. :</th>
                            <td nowrap class="text-right">{{ $voucher->file_no }}</td>
                        </tr>
                    </thead>
                </table>
                <table class="borderless" style="width: 50%">
                    <thead>
                        <tr>
                            <th nowrap class="text-left" width="1%" style="padding:0px; margin: 0px">Voucher</th>
                            <td nowrap class="text-left">: {{ $voucher->type }}</td>
                        </tr>
                        <tr>
                            <th nowrap class="text-left" style="padding:0px; margin: 0px">Voucher No.</th>
                            <td nowrap class="text-left">: {{ str_pad($voucher->id, 8, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                    </thead>
                </table>
                <br>

                <table>
                    <thead class="thead-light">
                        <tr>
                            <th>ACCOUNT CODE</th>
                            <th>HEAD OF ACCOUNT</th>
                            <th>PARTICULARS</th>
                            <th class="text-right">DEBIT</th>
                            <th class="text-right">CREDIT</th>
                        </tr>
                    </thead>
                    <tbody class="voucher-items">
                        @foreach($voucher->details->items as $item)
                            <tr>
                                <td>{{ $item->account_code }}</td>
                                <td>{{ $item->account_name }}</td>
                                <td>{{ $item->particulars ?? '' }}</td>
                                <td class="text-right"></td>
                                <td class="text-right">{{ $item->credit != 0 ? number_format($item->credit, 2) : '' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>{{ $voucher->details->debit_account_code }}</td>
                            <td>{{ $voucher->details->debit_account_name }}</td>
                            <td></td>
                            <td class="text-right">
                                {{ number_format($voucher->details->total_debit, 2) }}
                            </td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            <td class='text-center' colspan="3"><strong>TOTAL</strong></td>
                            <td class="text-right">
                                <strong>{{ number_format($voucher->details->total_debit, 2) }}</strong>
                            </td>
                            <td class="text-right">
                                <strong>{{ number_format($voucher->details->total_credit, 2) }}</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <p><strong>Amount In Words: </strong>{{ ucwords(in_words($voucher->amount)) }}</p>

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
</html>