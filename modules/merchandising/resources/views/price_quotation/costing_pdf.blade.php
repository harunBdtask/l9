<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Price Quotation Reports</title>


    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/print.css') }}" type="text/css"/>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>

    <style>
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: white;
            font: 7pt "Tahoma";
        }

        .page {
            /*width: 190mm;*/
            min-height: 297mm;
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
        }

        .header-section {
            /*padding: 10px;*/
        }

        .body-section {
            /*padding: 10px;*/
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
            width: 100% !important;
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

        table.border {
            border: 1px solid black;
            width: 20%;
            margin-left: auto;
            margin-right: auto;
        }

        .borderless td, .borderless th {
            border: none;
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

<body>
<main>
    <div class="page">
        <div class="header-section" style="padding-bottom: 0px;">
            <table class="borderless">
                <thead>
                <tr>
                    <td class="text-center">
                                <span
                                    style="font-weight: bold;">{{ factoryName() }}</span><br>
                        {{ factoryAddress() }}
                    </td>
                </tr>
                </thead>
            </table>
        </div>

        <div class="body-section" style="margin-top: 0px;">
            @include('merchandising::price_quotation.costing_table')
{{--            <div style="margin-top: 15px;">--}}
{{--                <table class="" style="border: 1px solid black; border-collapse: collapse;"--}}
{{--                       id="fixTable">--}}
{{--                    <tbody>--}}
{{--                    <tr>--}}
{{--                        <td>Date</td>--}}
{{--                        <td>{{ date_format(date_create($priceQuotation->quotation_date), 'd-M-Y') }}</td>--}}
{{--                        <td rowspan="9" class="text-center">--}}
{{--                            @if($priceQuotation->image)--}}
{{--                                <img style="height: 150px; width: 150px"--}}
{{--                                     src="{{ asset("storage/price_quotation_images/$priceQuotation->image")  }}"--}}
{{--                                     class="img-fluid">--}}
{{--                            @else--}}
{{--                                <img style="height: 150px; width: 150px" src="{{ asset('/images/no_image.jpg') }}"--}}
{{--                                     alt="No image found">--}}
{{--                            @endif--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>STYLE Name</td>--}}
{{--                        <td>{{ $priceQuotation->style_name }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>Buyer</td>--}}
{{--                        <td>{{ $priceQuotation->buyer->name }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>COUNTRY OF ORIGIN</td>--}}
{{--                        <td>Bangladesh</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>FACTORY</td>--}}
{{--                        <td>{{ factoryName() }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>Item DESCRIPTION</td>--}}
{{--                        <td>{{ $priceQuotation->style_desc }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>QUANTITY</td>--}}
{{--                        <td>{{ $priceQuotation->offer_qty }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>SIZE RANGE</td>--}}
{{--                        <td>{{ $priceQuotation->remarks }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>COLOURS</td>--}}
{{--                        <td></td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--            <div style="margin-top: 15px;">--}}
{{--                <table class="" style="border: 1px solid black; border-collapse: collapse;">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>FABRIC</th>--}}
{{--                        <th>QUALITY</th>--}}
{{--                        <th>DIA</th>--}}
{{--                        <th>GSM</th>--}}
{{--                        <th>Rate</th>--}}
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
{{--            <div style="margin-top: 15px;">--}}
{{--                <table class="" style="border: 1px solid black; border-collapse: collapse;">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th style="">Embellishment & Wash</th>--}}
{{--                        <th style="">PLACEMENT</th>--}}
{{--                        <th style="">UNIT/PC</th>--}}
{{--                        <th style="">QUANTITY</th>--}}
{{--                        <th style="">AMOUNT/USD</th>--}}
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
{{--            <div style="margin-top: 15px;">--}}
{{--                <table class="" style="border: 1px solid black; border-collapse: collapse;">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>Sewing Trims</th>--}}
{{--                        <th>PLACEMENT</th>--}}
{{--                        <th>Nominated Supplier</th>--}}
{{--                        <th>UNIT</th>--}}
{{--                        <th>UNIT/PC</th>--}}
{{--                        <th>QUANTITY</th>--}}
{{--                        <th>AMOUNT/USD</th>--}}
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
{{--            <div style="margin-top: 15px;">--}}
{{--                <table class="" style="border: 1px solid black; border-collapse: collapse;">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>PACKING & FINISHING</th>--}}
{{--                        <th>PLACEMENT</th>--}}
{{--                        <th>Nominated Supplier</th>--}}
{{--                        <th>UNIT</th>--}}
{{--                        <th>UNIT/PC</th>--}}
{{--                        <th>QUANTITY</th>--}}
{{--                        <th>AMOUNT/USD</th>--}}
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
            {{--                </div>--}}
        </div>
        @include('skeleton::reports.downloads.signature')
    </div>
</main>
</body>
</html>
