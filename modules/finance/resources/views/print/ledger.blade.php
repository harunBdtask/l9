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
                            <th nowrap class="text-left" style="padding:0px; margin: 0px">: Ledger of {{ $account->name }} ({{ $account->code }})</th>
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
                            <th class='text-left'>TRANSACTION DATE</th>
                            <th class='text-left'>VOUCHER NO</th>
                            <th class="text-left">PARTICULARS</th>
                            <th class="text-right">DEBIT</th>
                            <th class="text-right">CREDIT</th>
                            <th class="text-right">BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class='text-left'>
                                @php
                                    $date = \Carbon\Carbon::today();

                                    if (request()->has('start_date')) {
                                        $date = \Carbon\Carbon::parse(request('start_date'));
                                    }
                                    $balance = $account->openingBalance($date);
                                @endphp

                                {{ $date->toFormattedDateString() }}
                            </td>
                            <td class='text-left' colspan="3"><strong>Balance Forward</strong></td>

                            <td class="text-right">
                                @if($balance >= 0)
                                    <strong>{{ number_format(abs($balance), 2).' Dr' }}</strong>
                                @else
                                    <strong>{{ number_format(abs($balance), 2).' Cr' }}</strong>
                                @endif
                            </td>
                        </tr>
                        @forelse($account->journalEntries as $journalEntry)
                            @php
                                if ($journalEntry->trn_type == 'dr') {
                                    $balance += $journalEntry->trn_amount;
                                } else {
                                    $balance -= $journalEntry->trn_amount;
                                }
                            @endphp

                            <tr>
                                <td class='text-left'>{{ $journalEntry->trn_date->toFormattedDateString() }}</td>
                                <td class='text-left'>{{ str_pad($journalEntry->voucher_id, 8, '0', STR_PAD_LEFT) }}</td>
                                <td class='text-left'>{{ $journalEntry->particulars ?? '' }}</td>
                                <td class="text-right">
                                    {{ $journalEntry->trn_type == 'dr' ? number_format($journalEntry->trn_amount, 2) : '' }}
                                </td>
                                <td class="text-right">
                                    {{ $journalEntry->trn_type == 'cr' ? number_format($journalEntry->trn_amount, 2) : '' }}
                                </td>
                                <td class="text-right">
                                    @if($loop->last)
                                        @if($balance >= 0)
                                            <strong>{{ number_format(abs($balance), 2).' Dr' }}</strong>
                                        @else
                                            <strong>{{ number_format(abs($balance), 2).' Cr' }}</strong>
                                        @endif
                                    @else
                                        @if($balance >= 0)
                                            {{ number_format(abs($balance), 2).' Dr' }}
                                        @else
                                            {{ number_format(abs($balance), 2).' Cr' }}
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger">No transaction</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td class='text-center' colspan="3"><strong>TOTAL</strong></td>
                            <td class="text-right">
                                {{ 
                                    number_format($account->journalEntries->sum(function($item) { 
                                        return $item->trn_type == 'dr' ? $item->trn_amount : 0;
                                    }), 2)
                                }}
                            </td>
                            <td class="text-right">
                                {{ 
                                    number_format($account->journalEntries->sum(function($item) { 
                                        return $item->trn_type == 'cr' ? $item->trn_amount : 0;
                                    }), 2)
                                }}
                            </td>
                            <td class="text-right"></td>
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