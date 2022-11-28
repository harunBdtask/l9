<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!-- begin::Head -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Voucher Details</title>

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
                        <td class="text-center" style="height: 30px;">
                            <span style="font-size: 16pt; font-weight: bold;">{{ factoryName() }}</span><br>
                            {{ factoryAddress() }} <br> &nbsp;
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-center" style="height: 30px;">
                                <span
                                    style="font-size: 14pt; font-weight: bold;">{{strtoupper($voucher->type)}}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <hr>
            </div>

            <div class="body-section" style="margin-top: -10px;">
                <table class="borderless" style="width: 50%; float: left">
                    <thead>
                    <tr>
                        <th style="text-align:left; width:150px;">Voucher No:</th>
                        <td style="text-align:left; width:150px;">{{$voucher->voucher_no}}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:150px;">Project Name:</th>
                        <td style="text-align:left; width:150px;">{{$voucher->project->project}}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:150px;">Unit Name:</th>
                        <td style="text-align:left; width:150px;">{{$voucher->unit->unit}}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:150px;">Bill No:</th>
                        <td style="text-align:left; width:150px;">{{$voucher->bill_no??null}}</td>
                    </tr>
                    </thead>
                </table>
                <table class="borderless" style="width: 50%">
                    <thead>
                    <tr>
                        <th style="text-align:left; width:150px;">Transaction Date:</th>
                        <td style="text-align:left; width:150px;">{{$voucher->trn_date ? $voucher->trn_date->toFormattedDateString() : ''}}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:150px;">Currency:</th>
                        <td style="text-align:left; width:150px;">{{collect(\SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService::currencies())
                                                            ->where('id', $voucher->currency_id)->first()['name'] ?? null}}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:150px;">Reference:</th>
                        <td style="text-align:left; width:150px;">{{$voucher->reference_no}}</td>
                    </tr>
                    </thead>
                </table>
                <br>

                <table>
                    <thead class="thead-light">
                    <tr>
                        <th rowspan="2">ACCOUNT</th>
                        <th rowspan="2">UNIT</th>
                        <th rowspan="2">COST CENTER</th>
                        <th rowspan="2">CON. RATE</th>
                        <th colspan="2">FC</th>
                        <th colspan="2">BDT</th>
                    </tr>
                    <tr>
                        <th>DEBIT</th>
                        <th>CREDIT</th>
                        <th>DEBIT</th>
                        <th>CREDIT</th>
                    </tr>
                    </thead>
                    <tbody class="voucher-items">
                    @foreach($voucher->details->items as $item)
                        <tr>
                            <td style="text-align:left;">{{ $item->account_name }} ({{ $item->account_code }}) <br> {{ $item->narration }}
                            </td>
                            <td style="text-align:left;">{{ $item->department_name }}</td>
                            <td style="text-align:left;">{{ $item->const_center_name }}</td>
                            <td>{{ $voucher->currency_name . '@' . $item->conversion_rate }}</td>
                            <td style="text-align: right;">{{ $item->dr_fc != 0 ? number_format($item->dr_fc, 2) : '' }}</td>
                            <td style="text-align: right;">{{ $item->cr_fc != 0 ? number_format($item->cr_fc, 2) : '' }}</td>
                            <td style="text-align: right;">{{ $item->debit != 0 ? number_format($item->debit, 2) : '' }}</td>
                            <td style="text-align: right;">{{ $item->credit != 0 ? number_format($item->credit, 2) : '' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class='text-center' colspan="4"><strong>TOTAL</strong></td>
                        <td style="text-align: right;">
                            <strong>{{ $voucher->details->total_debit_fc ? number_format($voucher->details->total_debit_fc, 2) : '' }}</strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>{{ $voucher->details->total_credit_fc ? number_format($voucher->details->total_credit_fc, 2) : '' }}</strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>{{ number_format($voucher->details->total_debit, 2) }}</strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>{{ number_format($voucher->details->total_credit, 2) }}</strong>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <div class="reportTable">
                    @php
                        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                    @endphp
                    <table >
                        <tbody>
                        <tr>
                            <td  style="text-align:left; width:150px;" rowspan="2"><b>Amount in Words:</b></td>

                            <td style="text-align:left;" ><b>FC  :</b> {{ $voucher->details->total_debit_fc ? makeMoneyFormat($voucher->details->total_debit_fc) : null}}</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;" ><b>Home:</b> {{ $voucher->amount ? makeMoneyFormat($voucher->amount) : null}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 30mm">
                    @includeIf('basic-finance::print.voucher_signature')
                </div>
            </div>
        </div>
    </body>
</html>
