<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sales Contract</title>
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
    <div class="page" style="padding-top: 1.5in">
        <div>
            <p>
                <b>DATE: {{ isset($contract->contract_date) ? \Carbon\Carbon::make($contract->contract_date)->format('F d, Y') : null }}</b>
            </p>
            <p><b>TO</b>
                <br><b>{{ !empty($contract->lienBank) ? $contract->lienBank->contact_person : null}}</b>
                <br>{{ !empty($contract->lienBank) ? $contract->lienBank->name : null}}
                <br>{!! !empty($contract->lienBank) ? $contract->lienBank->address : null  !!}</p>
            <p><b>SUBJECT: <u>Application for Lien Expo S/C
                        NO: {{ $contract->contract_number ?? null}}
                        Date: {{ isset($contract->contract_date) ? \Carbon\Carbon::make($contract->contract_date)->format('d.m.Y') : null }}
                        for
                        US$ {{isset($contract->contract_value) ? number_format($contract->contract_value, 2) : 0.00}}
                        For {{ !empty($contract->details) ? number_format(collect($contract->details)->sum('attach_qty'),2) : 0.00 }}
                        Pcs, A/C of
                        M/s. {{ factoryName() }}
                    </u>
                </b>
            </p>
            <p><b>Dear Sir,</b></p>
            <p><b>
                    With reference to above we would like to inform you that, we are submitting here with our
                    <br>above mentioned of Export Cont. The details of the Export Contract as follows:
                </b>
            </p>
            <div class="row">
                <div class="col-lg-6">
                    <table class="reportTable">
                        <tbody>
                        <tr>
                            <th><b>01</b></th>
                            <th style="text-align:left; width:20%;"><b>Export Contract No. & Dated</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>{{$contract->contract_number ?? null}}
                                    <br>DATE: {{ isset($contract->contract_date) ? \Carbon\Carbon::parse($contract->contract_date)->format('d.m.Y') : null }}
                                </b>
                            </th>
                        </tr>
                        <tr>
                            <th><b>02</b></th>
                            <th style="text-align:left; width:20%;"><b>Qty</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>{{ !empty($contract->details) ? number_format(collect($contract->details)->sum('attach_qty'),2) : 0.00 }}
                                    PCS</b>
                            </th>
                        </tr>
                        <tr>
                            <th><b>03</b></th>
                            <th style="text-align:left; width:20%;"><b>Value</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>US${{isset($contract->contract_value) ? number_format($contract->contract_value, 2) : 0.00}}</b>

                            </th>
                        </tr>
                        <tr>
                            <th><b>04</b></th>
                            <th style="text-align:left; width:20%;"><b>Garments Desc. & H.S. Code</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                {{$contract->styles ?? ""}}
                                <br><b>H.S CODE: {{$contract->hs_code ?? ''}}</b>

                            </th>
                        </tr>
                        <tr>
                            <th><b>05</b></th>
                            <th style="text-align:left; width:20%;"><b>Export Cont. Opening Bank</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>{{isset($contract->lienBank) ? $contract->lienBank->name : null }}</b>
                            </th>
                        </tr>
                        <tr>
                            <th><b>06</b></th>
                            <th style="text-align:left; width:20%;"><b>Name of Buyer & Notify Party</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>
                                    @if(isset($contract->buyer))
                                        @foreach($contract->buyer as $buyer)
                                            {{ $buyer->name ?? ''}}
                                            @if(isset($buyer->address_1) )
                                                <br>  {!! $buyer->address_1 !!}<br>
                                            @endif
                                            @if(isset($buyer->address_2))
                                                {!! $buyer->address_2 !!}<br>
                                            @endif
                                        @endforeach
                                    @endif
                                    <br> AND <br>
                                    @if(isset($contract->notifying_party))
                                        @foreach($contract->notifying_party as $notifyingParty)
                                            {{ $notifyingParty->name ?? ''}}
                                            @if(isset($notifyingParty->address_1) )
                                                <br>  {!! $notifyingParty->address_1 !!} <br>
                                            @endif
                                            @if(isset($notifyingParty->address_2))
                                                {!! $notifyingParty->address_2 !!}<br>
                                            @endif
                                        @endforeach
                                    @endif
                                </b>
                            </th>
                        </tr>
                        <tr>
                            <th><b>07</b></th>
                            <th style="text-align:left; width:20%;"><b>Payment Terms</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>{{$contract->pay_term ?? null }}</b>
                            </th>
                        </tr>
                        <tr>
                            <th><b>08</b></th>
                            <th style="text-align:left; width:20%;"><b>Latest Shipment Date</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>{{isset($contract->last_shipment_date) ? \Carbon\Carbon::parse($contract->last_shipment_date)->format('d.m.Y') : null }}</b>
                            </th>
                        </tr>
                        <tr>
                            <th><b>09</b></th>
                            <th style="text-align:left; width:20%;"><b>S/C Expire Date</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>{{isset($contract->expiry_date) ? \Carbon\Carbon::parse($contract->expiry_date)->format('d.m.Y') : null }}</b>
                            </th>
                        </tr>
                        <tr>
                            <th><b>10</b></th>
                            <th style="text-align:left; width:20%;"><b>Terms of Delivery</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>{{$contract->inco_term ?? null }}
                                    , {{ $contract->inco_term_place ?? null  }}</b>
                            </th>
                        </tr>
                        <tr>
                            <th><b>11</b></th>
                            <th style="text-align:left; width:20%;"><b>Bank File</b></th>
                            <th><b>&nbsp;:&nbsp;</b></th>
                            <th style="text-align:left;">
                                <b>{{$contract->bank_file_no ?? null }}</b>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <p>
            <b>
                Your co-operation will be highly appreciated, <br>
                THANKING YOU
            </b>
        </p>

    </div>
</main>
</body>
</html>
