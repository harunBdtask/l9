<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Yarn Purchase Order</title>
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
            /*width: 190mm;*/
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

        .body-section .borderless td, th {
            text-align: left;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
    </style>
</head>

<body style="background: white;">
@include('skeleton::reports.downloads.footer')
<main>
    <div class="page">
        <div>
            <table class="borderless">
                <thead>
                <tr>
                    <td class="text-left">
                        <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                        {{ factoryAddress() }}
                        <br>
                    </td>
                    <th class="text-right" style="text-align: right;">
                        Order No: {{optional($order)->wo_no ?? ''}}</b><br>
                        @php
                            $orderWODate = optional($order)->wo_date;
                        @endphp
                        Order Date: {{ \Carbon\Carbon::make($orderWODate)->format('d M Y')?? ''}}</b><br>
                    </th>
                </tr>
                </thead>
            </table>
            <hr>
        </div>
        <center>
            <table style="border: 1px solid black;width: 30%;">
                <thead>
                <tr>
                    <td class="text-center">
                        <span style="font-size: 12pt; font-weight: bold;">Yarn Purchase Order</span>
                        <br>
                    </td>
                </tr>
                </thead>
            </table>
        </center>
        <br>
        <div>
            <center class="body-section" style="margin-top: 0px;">
                <table class="borderless">
                    <tr>
                        <th>Company Name:</th>
                        <td>{{optional($order)->factory->factory_name  ?? ''}}</td>
                        @if(!request('type') == 'reduced')
                            <th>Buyer Name:</th>
                            <td>{{optional($order)->buyer->name ?? ''}}</td>
                        @endif
                        <th>Supplier Name:</th>
                        <td>{{optional($order)->supplier->name ?? ''}}</td>
                    </tr>
                    <tr>
                        @if(!request('type') == 'reduced')
                            <th>Style Name:</th>
                            <td>{{optional(collect($order->details))->first()->style_name  ?? ''}}</td>
                        @endif
                        <th>Wo Date:</th>
                        <td>{{ \Carbon\Carbon::parse(optional($order)->wo_date)->format('d M Y') }}</td>
                        <th>Delivery Date:</th>
                        <td>{{ \Carbon\Carbon::parse(optional($order)->delivery_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Pay Mode:</th>
                        <td>{{ $order->pay_mode_value }}</td>
                        <th>Source:</th>
                        <td>{{ $order->source_value }}</td>
                        <th>WO Basis:</th>
                        @php
                            $woBasis = '';
                        @endphp
                        @if($order->wo_basis == 1)
                            @php $woBasis = 'Requisition Basis'; @endphp
                        @elseif($order->wo_basis == 2)
                            @php $woBasis = 'PO Basis'; @endphp
                        @elseif($order->wo_basis == 4)
                            @php $woBasis = 'Independent Basis'; @endphp
                        @endif
                        <td>{{ $woBasis }}</td>
                    </tr>
                    <tr>
                        <th>Attention:</th>
                        <td>{{ $order->attention }}</td>
                        <th>Remarks:</th>
                        <td>{{ $order->remarks }}</td>
                        <th>Currency:</th>
                        <td>{{ $order->currency }}</td>
                    </tr>
                </table>
                <br>
                <br>
                @if(isset($order->details))
                    <table class="reportTable">
                        <tr>
                            <th colspan="8" class="text-center"><b>Yarn Purchase Order Details</b></th>
                        </tr>
                        <tr>
                            <th>Yarn Description</th>
                            <th>Total WO Qty</th>
                            <th>UOM</th>
                            @if(!request('type') == 'reduced')
                                <th>Rate</th>
                                <th>Amount</th>
                            @endif
                            <th>Delivery Start Date</th>
                            <th>Delivery End Date</th>
                            <th>Remarks</th>
                        </tr>

                        @foreach($order->details as $key => $details)
                            @php
                                $totalWorkOrderQty = (($details['process_loss'] * $details['wo_qty']) / 100)
                                                        + $details['wo_qty'];
                            @endphp
                            <tr>
                                <td>
                                    {{ $details['yarnCount']['yarn_count'] ?? '' }}
                                    {{ $details['yarn_color'] ? ' - '. $details['yarn_color'] : '' }}
                                    {{ $details['yarnComposition']['yarn_composition'] ? ' - '. $details['yarnComposition']['yarn_composition'] : '' }}
                                    {{ $details['percentage'] ? ' - '. $details['percentage'] : '' }}
                                    {{  $details['yarnType']['yarn_type'] ? ' - '. $details['yarnType']['yarn_type'] :  '' }}
                                </td>
                                <td style="text-align: right">{{ $totalWorkOrderQty ?? '' }}</td>
                                <td>{{ $details['unitOfMeasurement']['unit_of_measurement'] ?? '' }}</td>
                                @if(!request('type') == 'reduced')
                                    <td style="text-align: right">{{ $details['rate'] ?? '' }}</td>
                                    <td style="text-align: right">{{ number_format($details['total_amount'], 2) ?? '' }}</td>
                                @endif
                                <td>{{ \Carbon\Carbon::parse($details['delivery_start_date'])->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($details['delivery_end_date'])->format('d M Y') }}</td>
                                <td>{{ $details['remarks'] ?? '' }}</td>
                            </tr>
                        @endforeach
                        @if(!request('type') == 'reduced')
                            <tr>
                                <td colspan="4" class="text-right"><b>Total</b></td>
                                @php $total = $order->details->sum('total_amount'); @endphp
                                <td style="text-align: right"><b>{{ number_format($total, 2)  }}</b></td>
                                <td colspan="3"/>
                            </tr>
                        @endif
                    </table>
                @endif
                <br>
                <div style="margin-top: 5mm">
                    @if(!request('type') == 'reduced')
                        <table class="borderless">
                            <tbody>
                            <tr>
                                @php
                                    $totalAmountt =  sprintf("%.4f",$total);
                                    $totalAmount = ucwords((new NumberFormatter("en", NumberFormatter::SPELLOUT))->format($totalAmountt));
                                @endphp
                                <td class="text-left" style="width: 350px"><b>Total Yarn Purchase Order Amount (In
                                        Words):</b></td>
                                <td> {{$totalAmount." ".$order->currency}}</td>
                            </tr>
                            </tbody>
                        </table>
                    @endif
                    <table class="borderless">
                        <tbody>
                        <tr>
                            <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
                        </tr>
                        @if(isset($termsConditions))
                            @foreach($termsConditions as $item)
                                <tr>
                                    <td>{{ '* '. $item->terms_name }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <br>
                <br>
                <center>
                    @include('skeleton::reports.downloads.signature')
                </center>
        </div>
    </div>

</main>
</body>
</html>
