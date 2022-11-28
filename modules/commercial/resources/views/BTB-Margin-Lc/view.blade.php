<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>B2B Margin LC</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            /*width: 190mm;*/
            /*min-height: 297mm;*/
            /*margin: 10mm auto;*/
            /*border-radius: 5px;*/
            background: white;
        }

        /*.header-section {*/
        /*    padding: 10px;*/
        /*}*/

        /*.body-section {*/
        /*    padding: 10px;*/
        /*    padding-top: 0px;*/
        /*}*/

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
            /*bottom: -60px;*/
            /*left: 0px;*/
            /*right: 0px;*/
            /*height: 50px;*/
        }
    </style>
</head>

<body style="background: white;">
<main>
    <div class="page">
        <div>
            <table class="borderless" style="color: red;">
                <tbody>
                @for($row=0; $row<9; $row++)
                    <tr>
                        @for($col=0; $col<24; $col++)
                            <td style="border: none;">
                                &nbsp;
                            </td>
                        @endfor
                    </tr>
                @endfor
                <tr>
                    <td colspan="12" style="padding-top:10px;">&nbsp;</td>
                    <td colspan="6">{{factoryName()}}</td>
                    <td colspan="4">&nbsp;</td>
                    <td colspan="4">{{$b2bData['lc_number']}}</td>
                </tr>
                <tr>
                    <td colspan="20" rowspan="5">{{factoryAddress()}}</td>
                    <td colspan="1">&nbsp;</td>
                    <td colspan="6">{{\Carbon\Carbon::parse($b2bData['lc_date'])->format('d-M-Y')}}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="10"></td>
                </tr>
                <tr>
                    @php
                        $totalAmountt =  sprintf("%.4f",$b2bData['lc_value']);
                        $totalAmount = strtoupper(ucwords((new NumberFormatter("en", NumberFormatter::SPELLOUT))->format($totalAmountt)));
                    @endphp
                    <td colspan="10" rowspan="2"><br>{{'US$ '. number_format($b2bData['lc_value'],4)}}<br>{{"IN WORD UNITED STATE DOLLARS ".$totalAmount}}</td>
                </tr>
                <tr>
                    <td colspan="24"></td>
                </tr>
                <tr>
                    <td colspan="24">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="24"></td>

                </tr>
                <tr>
                    <td colspan="20">&nbsp;</td>
                    <td colspan="9" rowspan="6">{{$b2bData['supplier']['name']}}
                        <br>{{$b2bData['supplier']['address_1']}} <br> {{$b2bData['supplier']['address_2']}}</td>
                </tr>
                <tr>
                    <td colspan="24">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="14">&nbsp;</td>
                    <td colsapn="3">{{$b2bData['tenor'].' Days'}}</td>
                </tr>
                <tr>
                    <td colspan="1">&nbsp;</td>
                    <td colspan="4">&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="12"></td>
                    <td colspan="18">&emsp;{{ strtoupper($b2bData['item']['item_name']) }}
                        FOR {{ $b2bData['garments_qty'] }} {{ $b2bData['unit_of_measurement']['unit_of_measurement'] }}
                    </td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                    <td colspan="22">EXPORT ORIENTED READYMADE GARMENTS INDUSTRIES AS
                        PER {{ $b2bData['proformaInvoice'] }} OF THE BENEFICIARY</td>
                </tr>
                <tr >
                    <td colspan="14" style="padding-top:10px;">&nbsp;</td>
                    <td colspan="3">BENEFICIARY FACTORY</td>
{{--                    <td colspan="3">{{$b2bData['port_of_discharge']}}</td>--}}
                    <td colspan="3">&nbsp;</td>
                    <td colspan="4">APPLICANTS FACTORY</td>
{{--                    <td colspan="4">{{$b2bData['port_of_loading']}}</td>--}}
                    <td colspan="1">&nbsp;</td>
                    @if($b2bData['delivery_mode'] == 1)
                        <td colspan="3" style="padding-right:60px;">Sea</td>
                    @elseif($b2bData['delivery_mode'] == 2)
                        <td colspan="3">Air</td>
                    @elseif($b2bData['delivery_mode'] == 3)
                        <td colspan="3">Road</td>
                    @else
                        <td colspan="3">Road/Air</td>
                    @endif
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                    <td colspan="6" style="padding-right:50px;">15 Days</td>
{{--                    <td colspan="6" style="padding-right:50px;">{{\Carbon\Carbon::parse($b2bData['last_shipment_date'])->format('d-M-Y')}}</td>--}}
                    <td colspan="4" style="text-align:right;">{{\Carbon\Carbon::parse($b2bData['lc_expiry_date'])->format('d-M-Y')}}</td>
                    <td colspan="3" style="padding-right:130px;">&nbsp;</td>
                    <td colspan="3">BANGLADESH</td>
                </tr>
                <tr>
                    <td colspan="24">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="11">&nbsp;</td>
                    <td colspan="4">{{$b2bData['partial_shipment'] > 0 ? 'Allowed' : 'Not Allowed'}}</td>
                    <td colspan="6"></td>
                    <td colspan="6">{{$b2bData['transhipment'] > 0 ? 'Allowed' : 'Not Allowed'}}</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>

                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    @if($b2bData['origin'])
                        <td colspan="22"></td>
                        <td clospan="4">{{$b2bData['origin'] ?? ""}}</td>
                    @else
                        <td colspan="24">&nbsp;</td>
                    @endif
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    @if($b2bData['cover_note_no']):
                    <td colspan="24">&nbsp;</td>
                    <td colspan="2">{{$b2bData['cover_note_no']}}</td>
                    @else:
                    <td colspan="22">&nbsp;</td>
                    @endif
                </tr>
                <tr>
                    <td colspan="7"> </td>
                    <td colspan="17">{{$b2bData['insurance_company']}}</td>
                </tr>
                <tr>
                    <td colspan="24">&nbsp;</td>
                </tr>
                <tr style="padding-top: 10px;">
                    <td colspan="10">&nbsp;</td>
                    <td colspan="2">{{$b2bData['cover_note_no']}}</td>
                    <td colspan="3">&nbsp;</td>
                    <td colspan="4">{{\Carbon\Carbon::parse($b2bData['cover_note_date'])->format('d-M-Y')}}</td>
                </tr>
                <tr>
                    <td colspan="24">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="24">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">* {{$exportLc->pluck('lc_no_date')->implode(', ')}}</td>
                </tr>
                <tr>
                    <td colspan="22">* MATURITY DATE SHOULD BE COUNTED FROM THE DATE OF
                        @php
                            if($b2bData['maturity_from'] == '1'){
                                echo 'ACCEPTANCE DATE';
                            }elseif ($b2bData['maturity_from'] == '2'){
                                echo 'SHIPMENT DATE';
                            }elseif ($b2bData['maturity_from'] == '3'){
                                echo 'NEGOTIATION DATE';
                            }elseif ($b2bData['maturity_from'] == '4'){
                                echo 'B/L DATE';
                            }
                        @endphp
                        AND PAYMENTS IN US DOLLAR BY BANGLAGESH BANK FDD.
                    </td>
                </tr>
                <tr>
                        <td colspan="24">&nbsp;</td>
                </tr>
                <tr>
                        <td colspan="24">&nbsp;</td>
                </tr>
                <tr>
                        <td colspan="24">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="18">&nbsp;</td>
                    <td colspan="4">{{$b2bData['lcaf_no']}}</td>
                    <td colspan="2">&nbsp;</td>
                    <td colspan="4">{{$b2bData['remarks']}}</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="22">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>
