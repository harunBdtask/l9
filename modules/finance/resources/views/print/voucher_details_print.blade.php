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

            #tabular-form table, #tabular-form th, #tabular-form td {
                border: 1px solid black;
            }

            

            table.borderless {
                border: none;
            }

            .borderless td, .borderless th {
                border: none;
            }
            
            .form-control-label {
                padding: 0 0 0 5px !important;
            }

            .form-group {
                margin-bottom: 0 !important;
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

            
            .signature table {
                width: 100%;
                border-collapse: collapse;
            }

            .signature table, .signature th, .signature td {
                border: 1px solid black;
            }

            .signature th, .signature td {
                padding-left: 5px;
                padding-right: 5px;
                padding-top: 3px;
                padding-bottom: 3px;
            }

            .signature table.borderless {
                border: none;
            }

            .signature .borderless td, .borderless th {
                border: none;
            }
        </style>
    </head>

    <body>
        <div class="page">
            <div class="box-body b-t">
                <div class="header-section" align="center" style="padding-bottom: 0px;">
                    <table class="borderless">
                        <thead>
                            <tr>
                                <td class="text-center" style="height: 30px;">
                                    <span style="font-size: 16pt; font-weight: bold;">{{ factoryName() }}</span><br>
                                    {{ factoryAddress() }} <br> &nbsp;
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <div class="text-center" style="height: 30px; width:60%; border: 1px solid black;">
                        <span style="font-size: 14pt; font-weight: bold;">{{ $voucher_name }}</span>
                    </div>
                    <hr>
                </div>
                <div class="voucher-meta">
                    <div class="col-md-4">
                        <table class="" style="padding-top: 20px;">
                            <tr>
                                <th style="text-align:left; width:150px;">Voucher No:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->voucher_no}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Company:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->company->name}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Project:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->project->project}}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4">
                        <table class="" style="padding-top: 20px;">
                            <tr>
                                <th style="text-align:left; width:150px;">Transaction Date:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->trn_date ? $voucher->trn_date->toFormattedDateString() : ''}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Currency:</th>
                                <td style="text-align:left; width:150px;">{{collect(\SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService::currencies())
                                                            ->where('id', $voucher->currency_id)->first()['name'] ?? null}}</td>
                            </tr>
                            @if ($voucher->paymode != 1)    
                            <tr>
                                <th style="text-align:left; width:150px;">Bank Name:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->bank->account->name}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Cheque No:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->cheque_no}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Due Date:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->cheque_due_date ? $voucher->cheque_due_date : ''}}</td>
                            </tr>
                            @endif
                            <tr>
                                <th style="text-align:left; width:150px;">Reference:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->reference_no}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <div class="table-responsive" style="padding-top:20px;">
                    <table class="table table-bordered" id="tabular-form">
                        <thead class="thead-light">
                        <tr>
                            <th rowspan="2">ACCOUNT CODE</th>
                            <th rowspan="2">ACCOUNT NAME</th>
                            <th rowspan="2">LEDGER ACCOUNT</th>
                            <th rowspan="2">NARRATION</th>
                            <th rowspan="2">COST CENTER</th>
                            @if ($voucher->currency_id != 1)
                            <th rowspan="2">CON. RATE</th>
                            <th colspan="2">FC</th>
                            @endif
                            <th colspan="2">BDT</th>
                        </tr>
                        <tr>
                            @if ($voucher->currency_id != 1)
                            <th>DEBIT</th>
                            <th>CREDIT</th>
                            @endif
                            <th>DEBIT</th>
                            <th>CREDIT</th>
                        </tr>
                        </thead>
                        <tbody class="voucher-items">
                        @php
                            $currency =  collect(\SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService::currencies())->where('id', $voucher->details->currency_id)->first()['name'] ?? null;
                        @endphp
                        @foreach($voucher->details->items as $item)
                            <tr>
                                <td style="text-align:left;">{{ $item->account_code }}</td>
                                <td style="text-align:left;">{{ $item->account_name }}</td>
                                <td style="text-align:left;">{{ $item->ledger_name }}</td>
                                <td style="text-align:left;">{{ $item->narration }}</td>
                                <td style="text-align:left;">{{ $item->const_center_name }}</td>
                                @if ($voucher->currency_id != 1)
                                <td>{{ $currency.'@'.$item->conversion_rate }}</td>
                                <td style="text-align:right;">{{isset($item->dr_fc) && $item->dr_fc != 0 ? number_format($item->dr_fc, 2) : '' }}</td>
                                <td style="text-align:right;">{{isset($item->cr_fc) && $item->cr_fc != 0 ? number_format($item->cr_fc, 2) : '' }}</td>
                                @endif
                                <td style="text-align:right;">{{isset($item->debit) && $item->debit != 0 ? number_format($item->debit, 2) : '' }}</td>
                                <td style="text-align:right;">{{isset($item->credit) && $item->credit != 0 ? number_format($item->credit, 2) : '' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            
                            @if ($voucher->currency_id != 1)
                            <td class='text-center' colspan="6"><strong>TOTAL</strong></td>
                            <td style="text-align:right;">
                                <strong>{{ isset($voucher->details->total_debit_fc) ? number_format($voucher->details->total_debit_fc, 2) : '' }}</strong>
                            </td>
                            <td style="text-align:right;">
                                <strong>{{ isset($voucher->details->total_debit_fc) ? number_format($voucher->details->total_debit_fc, 2) : '' }}</strong>
                            </td>
                            @else
                            <td class='text-center' colspan="5"><strong>TOTAL</strong></td>
                            @endif
                            <td style="text-align:right;">
                                <strong>{{ number_format($voucher->details->total_debit, 2) }}</strong>
                            </td>
                            <td style="text-align:right;">
                                <strong>{{ number_format($voucher->details->total_credit, 2) }}</strong>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="reportTable">
                    @php
                        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                    @endphp
                    <table >
                        <tbody>
                        <tr>
                            <td  style="text-align:left; width:150px;" rowspan="2"><b>Amount in Words:</b></td>
                            @if ($voucher->currency_id != 1)
                            <td style="text-align:left;" ><b>FC  :</b> {{ $voucher->details->total_debit_fc ? ucwords($digit->format($voucher->details->total_debit_fc)) : null}} Only</td>
                            @endif
                        </tr>
                        <tr>
                            <td style="text-align:left;" ><b>Home:</b> {{ $voucher->amount ? ucwords($digit->format($voucher->amount)) : null}} Only</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                @include('skeleton::reports.downloads.signature')
                
            </div>
            <div class="text-right" style="opacity: 0.4; padding:10px;">
                <p>Voucher Status: {{ $voucherStatuses[$voucher->status_id]??'' }}</p>
            </div>
        </div>
    </body>
</html>
