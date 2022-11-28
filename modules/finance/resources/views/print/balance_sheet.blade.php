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
                <table class="borderless">
                    <thead>
                        <tr>
                            <td nowrap class="text-left" width="1%" style="padding:0px; margin: 0px">Report Title</td>
                            <th nowrap class="text-left" style="padding:0px; margin: 0px">: {{ $report_title }}</th>
                        </tr>
                        <tr>
                            <td nowrap class="text-left" style="padding:0px; margin: 0px">Date Between</td>
                            <th nowrap class="text-left" style="padding:0px; margin: 0px">: {{ $start_date->toFormattedDateString() }} to {{ $end_date->toFormattedDateString() }}</th>
                        </tr>
                    </thead>
                </table>
                <br>

                <table>
                    <thead class="thead-light">
                        <tr>
                            <th class="text-left">HEAD OF ACCOUNT</th>
                            <th class="text-right">BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="2" class="text-left">ASSET</th>
                        </tr>
                        @foreach($assets as $account)
                            <tr>
                                <td class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                                <td class="text-right">
                                    {{ $account->balance >= 0 ? number_format($account->balance, 2) : '('.number_format(abs($account->balance), 2).')' }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th class="text-left">Total of Asset</th>
                            <td class="text-right">
                                @php
                                    $totalAsset = $assets->sum('balance');
                                @endphp
                                <strong>
                                    {{ $totalAsset >= 0 ? number_format($totalAsset, 2) : '('.number_format(abs($totalAsset), 2).')' }}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-left" colspan="2">LIABILITY</th>
                        </tr>
                        @foreach($liabilities as $account)
                            <tr>
                                <td class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                                <td class="text-right">
                                    {{ $account->balance >= 0 ? '('.number_format(abs($account->balance), 2).')' : number_format(abs($account->balance), 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th class="text-left">Total of Liabililty</th>
                            <td class="text-right">
                                @php
                                    $totalLiability = $liabilities->sum('balance');
                                    $totalLiability = $totalLiability > 0 ? (-1*$totalLiability) : abs($totalLiability);
                                @endphp
                                <strong>
                                    {{ $totalLiability >= 0 ? number_format($totalLiability, 2) : '('.number_format(abs($totalLiability), 2).')' }}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2" class="text-left">EQUITY</th>
                        </tr>
                        @foreach($equities as $account)
                            <tr>
                                <td class="text-left">&nbsp;&nbsp;{{ $account->name }}</td>
                                <td class="text-right">
                                    {{ $account->balance >= 0 ? '('.number_format(abs($account->balance), 2).')' :  number_format(abs($account->balance), 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th class="text-left">Net Profit/Loss</th>
                            <td class="text-right"><strong>{{ number_format($net_profit, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th class="text-left">Total of Equity</th>
                            <td class="text-right">
                                @php
                                    $totalEquity = $equities->sum('balance');
                                    $totalEquity = $totalEquity > 0 ? (-1*$totalEquity) : abs($totalEquity);

                                    $totalEquity += $net_profit;
                                @endphp
                                <strong>
                                    {{ $totalEquity >= 0 ? number_format($totalEquity, 2) : '('.number_format(abs($totalEquity), 2).')' }}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-left">Total of Liability & Equity</th>
                            <td class="text-right">
                                @php

                                    $liabilityEquity = $totalLiability + $totalEquity;
                                @endphp
                                <strong>
                                    {{ $liabilityEquity >= 0 ? number_format($liabilityEquity, 2) : '('.number_format(abs($liabilityEquity), 2).')' }}
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
</html>
