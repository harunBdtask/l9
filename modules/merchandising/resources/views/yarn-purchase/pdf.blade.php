<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Yarn Purchase Requisition</title>
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
                        Requisition No: {{optional($requisition)->requisition_no  ?? ''}} <br>
                        Requisition Date: {{optional($requisition)->requisition_date  ?? ''}}
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
                        <span style="font-size: 12pt; font-weight: bold;">Yarn Purchase Requisition</span>
                        <br>
                    </td>
                </tr>
                </thead>
            </table>
        </center>
        <br>
        <center class="body-section" style="margin-top: 0px;">
            <table class="borderless">
                <tr>
                    <th>Requisition Date:</th>
                    <td>{{optional($requisition)->requisition_date  ?? ''}}</td>
                    <th>Required Date:</th>
                    <td>{{optional($requisition)->required_date ?? ''}}</td>
                </tr>
                <tr>
                    <th>Factory Name:</th>
                    <td>{{optional($requisition)->factory->factory_name  ?? ''}}</td>
                    <th>Requisition No:</th>
                    <td>{{optional($requisition)->requisition_no ?? ''}}</td>
                </tr>
                <tr>
                    <th>Dealing Merchant:</th>
                    <td>{{optional($requisition)->merchant->screen_name  ?? ''}}</td>
                    <th>Source:</th>
                    <td>{{optional($requisition)->source_value ?? ''}}</td>
                </tr>
            </table>
            <br>
            <br>
            @if(isset($requisition->details))
                <table class="reportTable">
                    <tr>
                        <th colspan="14" class="text-center"><b>Yarn Purchase Requisition Details</b></th>
                    </tr>
                    <tr>
                        <th>Unique Id</th>
                        <th>Buyer Name</th>
                        <th>Style Name</th>
                        <th>Yarn Count</th>
                        <th>Yarn Color</th>
                        <th>%</th>
                        <th>Yarn Type</th>
                        <th>UOM</th>
                        <th>Requisition Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Yarn In-House-Date</th>
                        <th>Remarks</th>
                    </tr>
                    @php
                        $total_req_qty = 0;
                        $total_amount = 0;
                    @endphp
                    @foreach($requisition->details as $key => $details)
                        @php
                            $total_req_qty += $details['requisition_qty'];
                            $total_amount += $details['amount'];
                        @endphp
                        <tr>
                            <td>{{ $details['unique_id'] ?? '' }}</td>
                            <td>{{ $details['buyer']['name'] ?? '' }}</td>
                            <td>{{ $details['style_name'] ?? '' }}</td>
                            <td>{{ $details['yarnCount']['yarn_count'] ?? '' }}</td>
                            <td>{{ $details['yarn_color'] ?? '' }}</td>
                            <td class="text-right">{{ $details['percentage'] ?? '' }}</td>
                            <td>{{ $details['yarnType']['yarn_type'] ?? '' }}</td>
                            <td>{{ $details['unitOfMeasurement']['unit_of_measurement'] ?? '' }}</td>
                            <td class="text-right">{{ round($details['requisition_qty']) ?? 0 }}</td>
                            <td class="text-right">{{ $details['rate'] ?? 0 }}</td>
                            <td class="text-right">{{ number_format($details['amount'], 4) ?? 0 }}</td>
                            <td>{{ $details['yarn_in_house_date'] ?? '' }}</td>
                            <td>{{ $details['remarks'] ?? '' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="8" class="text-right"><b>Total</b></td>
                        <td class="text-right"><b>{{ round($total_req_qty) }}</b></td>
                        <td></td>
                        <td class="text-right"><b>{{ number_format($total_amount, 4) }}</b></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            @endif
            <br>
            <div style="margin-top: 5mm">
                <table class="borderless">
                    <tbody>
                    <tr>
                        @php
                            $totalAmountt =  sprintf("%.4f",$total_amount);
                            $totalAmount = ucwords((new NumberFormatter("en", NumberFormatter::SPELLOUT))->format($totalAmountt));
                        @endphp
                        <td class="text-left" style="width: 400px"><b>Total Yarn Purchase Requisition Amount (In Words):</b></td>
                        <td> {{$totalAmount." ".$requisition->currency}}</td>
                    </tr>
                    </tbody>
                </table>
                <table class="borderless">
                    <tbody>
                    <tr>
                        <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
                    </tr>
                    @if(isset($requisition->terms_condition))
                        @foreach($requisition->terms_condition as $terms)
                            <tr>
                                <td>{{ '* '. $terms }}</td>
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
