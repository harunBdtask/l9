<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Erp</title>


    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/print.css') }}" type="text/css"/>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>

    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            background-color: #FAFAFA;
            font: 7pt "Tahoma";
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
    <div class="">
        <div class="header-section" style="padding-bottom: 0px;">
            <table class="borderless">
                <thead>
                <tr>
                    <td class="text-center">
                                <span
                                    style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                        {{ factoryAddress() }}
                    </td>
                </tr>
                </thead>
            </table>
            <hr>
        </div>

        <center>
            <table style="border: 1px solid black;width: 50%;">
                <thead>
                <tr>
                    <td class="text-center">
                        <span style="font-size: 12pt; font-weight: bold;">Price Quotation Costing</span>
                        <br>
                    </td>
                </tr>
                </thead>
            </table>
        </center>
        <br>
        @include('merchandising::price_quotation.costing_table')

        {{--        <div style="width: 100%; margin-top: 10px; display: block">--}}
{{--            <div style="width: 70%; float: left">--}}
{{--                <table class="reportTable">--}}
{{--                    <tbody>--}}
{{--                    <tr>--}}
{{--                        <th>Date</th>--}}
{{--                        <td>{{ date_format(date_create($priceQuotation->quotation_date), 'd-M-Y') }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>STYLE Name</th>--}}
{{--                        <td>{{ $priceQuotation->style_name }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>Buyer</th>--}}
{{--                        <td>{{ $priceQuotation->buyer->name }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>COUNTRY OF ORIGIN</th>--}}
{{--                        <td>Bangladesh</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>FACTORY</th>--}}
{{--                        <td>{{ factoryName() }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>Item DESCRIPTION</th>--}}
{{--                        <td>{{ $priceQuotation->style_desc }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>QUANTITY</th>--}}
{{--                        <td>{{ $priceQuotation->offer_qty }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>SIZE RANGE</th>--}}
{{--                        <td>{{ $priceQuotation->remarks }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>COLOURS</th>--}}
{{--                        <td></td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--            <div style="width: 30%; float: right; padding: 20px">--}}
{{--                <div class="col-sm-4">--}}
{{--                    @if($priceQuotation->image)--}}
{{--                        <img src="{{ asset("storage/price_quotation_images/$priceQuotation->image")  }}"--}}
{{--                             style="height: 150px; width: 150px" class="img-fluid">--}}
{{--                    @else--}}
{{--                        <img src="{{ asset('/images/no_image.jpg') }}" alt="No image found" style="height: 150px; width: 150px">--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <div class="col">--}}
{{--                <table class="borderless">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <div class="col-sm-12">--}}
{{--                <table class="reportTable" style="margin-top: 10px">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>FABRIC</th>--}}
{{--                        <th>QUALITY</th>--}}
{{--                        <th>DIA</th>--}}
{{--                        <th>GSM</th>--}}
{{--                        <th>UNIT/PC</th>--}}
{{--                        <th>UNIT</th>--}}
{{--                        <th>CONS/PC</th>--}}
{{--                        <th>AMOUNT/USD</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @if(isset($fabric_costing['fabricForm']))--}}
{{--                        @foreach($fabric_costing['fabricForm'] as $fabricCost)--}}
{{--                            <tr>--}}
{{--                                <td style="text-align: left">{{ $fabricCost['body_part_value'] ?? '' }}</td>--}}
{{--                                <td style="text-align: left">{{ $fabricCost['fabric_composition_value'] ?? '' }}</td>--}}
{{--                                <td style="text-align: left">{{ $fabricCost['fabricConsumptionForm'] ?  collect($fabricCost['fabricConsumptionForm'])->first()['dia'] : ''}}</td>--}}
{{--                                <td style="text-align: left">{{ $fabricCost['gsm'] ?? '' }}</td>--}}
{{--                                <td style="text-align: right">{{ $fabricCost['rate'] ?? '' }}</td>--}}
{{--                                <td style="text-align: center">{{ $fabricCost['uom'] ?? '' }}</td>--}}
{{--                                <td style="text-align: right">{{ $fabricCost['fabric_cons'] ? number_format($fabricCost['fabric_cons'], 4) : '' }}</td>--}}
{{--                                <td style="text-align: right">{{ $fabricCost['amount'] ? number_format($fabricCost['amount'], 4) : '' }}</td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        <tr>--}}
{{--                            <th style="text-align: right" colspan="4">Total</th>--}}
{{--                            <td style="text-align: right">--}}
{{--                                <b>{{ number_format((float)collect($fabric_costing['fabricForm'] )->sum('fabric_cons'), 4) }}</b>--}}
{{--                            </td>--}}
{{--                            <td style="text-align: right">--}}
{{--                                <b>{{ number_format((float)collect($fabric_costing['fabricForm'])->sum('amount'), 4) }}</b>--}}
{{--                            </td>--}}
{{--                            <td colspan="2"></td>--}}
{{--                        </tr>--}}
{{--                    @endif--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="row" style="margin-top: 10px">--}}
{{--            <div class="col-sm-12">--}}
{{--                <table class="reportTable">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th style="width: 20%">Embellishment & Wash</th>--}}
{{--                        <th style="width: 50%">PLACEMENT</th>--}}
{{--                        <th style="width: 10%">UNIT/PC</th>--}}
{{--                        <th style="width: 10%">QUANTITY</th>--}}
{{--                        <th style="width: 10%">AMOUNT/USD</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($embellishment_cost['details'] ?? [] as $embellishment)--}}
{{--                        <tr>--}}
{{--                            <td style="text-align: left">{{ $embellishment['name'] }}</td>--}}
{{--                            <td style="text-align: left">{{ $embellishment['type'] }}</td>--}}
{{--                            <td style="text-align: right">{{ number_format((float)$embellishment['rate'], 4) }}</td>--}}
{{--                            <td style="text-align: right">{{ $embellishment['cons_per_dzn'] }}</td>--}}
{{--                            <td style="text-align: right">{{ number_format((float)$embellishment['amount'], 4) }}</td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    <tr>--}}
{{--                        <th style="text-align: right" colspan="4">Total</th>--}}
{{--                        <td style="text-align: right">--}}
{{--                            <b>{{ number_format((float)collect($embellishment_cost['details'] ?? [])->sum('amount'), 4) }}</b>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="row" style="margin-top: 10px">--}}
{{--            <div class="col-sm-12">--}}
{{--                <table class="reportTable">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th style="width: 15%">Sewing Trims</th>--}}
{{--                        <th style="width: 35%">PLACEMENT</th>--}}
{{--                        <th style="width: 10%">Nominated Supplier</th>--}}
{{--                        <th style="width: 10%">UNIT</th>--}}
{{--                        <th style="width: 10%">UNIT/PC</th>--}}
{{--                        <th style="width: 10%">QUANTITY</th>--}}
{{--                        <th style="width: 10%">AMOUNT/USD</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach(collect($trims_costing)->where('type', 'Sewing Trims') as  $trim)--}}
{{--                        <tr>--}}
{{--                            <td style="text-align: left">{{ $trim['group_name'] }}</td>--}}
{{--                            <td style="text-align: left">{{ $trim['item_description'] }}</td>--}}
{{--                            <td style="text-align: left">{{ $trim['nominated_supplier_value'] }}</td>--}}
{{--                            <td style="text-align: center">{{ $trim['cons_uom_value'] }}</td>--}}
{{--                            <td style="text-align: right">{{ number_format((float)$trim['rate'], 4) }}</td>--}}
{{--                            <td style="text-align: right">{{ $trim['cons_gmts'] }}</td>--}}
{{--                            <td style="text-align: right">{{ number_format($trim['amount'], 4) }}</td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="row" style="margin-top: 10px">--}}
{{--            <div class="col-sm-12">--}}
{{--                <table class="reportTable">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th style="width: 1000px">PACKING & FINISHING</th>--}}
{{--                        <th style="width: 1500px">PLACEMENT</th>--}}
{{--                        <th style="width: 660px">Nominated Supplier</th>--}}
{{--                        <th style="width: 80px">UNIT</th>--}}
{{--                        <th style="width: 100px">UNIT/PC</th>--}}
{{--                        <th style="width: 100px">QUANTITY</th>--}}
{{--                        <th style="width: 100px">AMOUNT/USD</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach(collect($trims_costing)->where('type', 'Finishing Trims') as  $trim)--}}
{{--                        <tr>--}}
{{--                            <td style="text-align: left">{{ $trim['group_name'] }}</td>--}}
{{--                            <td style="text-align: left">{{ $trim['item_description'] }}</td>--}}
{{--                            <td style="text-align: left">{{ $trim['nominated_supplier_value'] }}</td>--}}
{{--                            <td style="text-align: center">{{ $trim['cons_uom_value'] }}</td>--}}
{{--                            <td style="text-align: right">{{ number_format((float)$trim['rate'], 4) }}</td>--}}
{{--                            <td style="text-align: right">{{ $trim['cons_gmts'] }}</td>--}}
{{--                            <td style="text-align: right">{{ number_format($trim['amount'], 4) }}</td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    <tr>--}}
{{--                        <th style="text-align: right" colspan="6">Total Amount</th>--}}
{{--                        <td style="text-align: right">--}}
{{--                            <b>{{ number_format(collect($trims_costing->where('type', 'Finishing Trims'))->sum('amount'), 4) }}</b>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th style="text-align: right" colspan="6">TOTAL MATERIAL COST</th>--}}
{{--                        @if($trims_costing)--}}
{{--                            @php $totalMaterialCost = (collect($trims_costing)->sum('amount')) + (collect(isset($fabric_costing['fabricForm']) ? $fabric_costing['fabricForm'] : [])->sum('amount')) + (collect($embellishment_cost['details'] ?? [])->sum('amount')) @endphp--}}
{{--                        @else--}}
{{--                            @php $totalMaterialCost = 0 @endphp--}}
{{--                        @endif--}}
{{--                        <td style="text-align: right">{{ number_format($totalMaterialCost, 4 ) }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th style="text-align: right" colspan="6">CUT & MAKE COST</th>--}}
{{--                        @if(isset($priceQuotation->cm_cost))--}}
{{--                            @php $priceQuotationCMCost = $priceQuotation->cm_cost @endphp--}}
{{--                        @else--}}
{{--                            @php $priceQuotationCMCost = 0 @endphp--}}
{{--                        @endif--}}
{{--                        <td style="text-align: right">{{ number_format($priceQuotationCMCost, 4 ) }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th style="text-align: right" colspan="6">DOC & HANDLING COST</th>--}}
{{--                        @if(isset($commercial_cost['calculation']['amount_sum']))--}}
{{--                            @php $commercialCostAmountSum = $commercial_cost['calculation']['amount_sum'] @endphp--}}
{{--                        @else--}}
{{--                            @php $commercialCostAmountSum = 0 @endphp--}}
{{--                        @endif--}}
{{--                        <td style="text-align: right">{{ number_format($commercialCostAmountSum, 4) }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th style="text-align: right" colspan="6">TOTAL COST</th>--}}
{{--                        <td style="text-align: right">{{ number_format($commercialCostAmountSum + $priceQuotationCMCost + $totalMaterialCost, 4) }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th style="text-align: right" colspan="6">PROFIT %</th>--}}
{{--                        <td style="text-align: right">{{ number_format((float)$priceQuotation->asking_profit_pc_set, 4) }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th style="text-align: right" colspan="6">TOTAL FOB PRICE</th>--}}
{{--                        <td style="text-align: right">{{ number_format((float)$priceQuotation->asking_quoted_pc_set, 4) }}</td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
        @include('skeleton::reports.downloads.footer')
        @include('skeleton::reports.downloads.signature')
    </div>
</div>
</body>
</html>
