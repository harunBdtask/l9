<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @include('inventory::report.report_style')

    <style>
        .bg-danger {
            color: #fff !important;
            background-color: #ea070087 !important;
        }

        .bg-gwhite {
            background: ghostwhite;
        }
    </style>
</head>
<body>
<header>
    <h1> <img src="{{ public_path('/modules/inventory/pdf/pn-logo.png') }}" width="150px"  alt="..."> </h1>
    <h2> {{ get_company_details()->name ?? 'PN COMPOSITE LTD' }} </h2>
    <h4>{{ $store_name }}</h4>
{{--    <h4>{{ get_company_details()->address ?? '' }}</h4>--}}
</header>
<h4 style="text-align: center;">{{ get_company_details()->address ?? '' }}</h4>
<h3 style="text-align: center; margin-top: -5px;">Category Wise Stock Summery Report</h3>
<p style="text-align: center;font-weight: 700;">
    {{ isset($first_date) ? "For " . \Carbon\Carbon::create($first_date)->format("Y-M-d") : null  }}
    {{ isset($last_date) ? "To ". \Carbon\Carbon::create($last_date)->format("Y-M-d") : null }} <br> <br></p>

<main>
    <table class="reportTable" id="header-fixed">
        <thead>
        <tr>
            <th rowspan="2">Particulars</th>
            <th colspan="3">Opening Balance</th>
            <th colspan="3">Inwards</th>
            <th colspan="3">Outwards</th>
            <th colspan="3">Closing Balance</th>
        </tr>
        <tr>
            {{--opening--}}
            <th>Quantity</th>
            <th>Rate</th>
            <th>Value</th>
            {{--inward--}}
            <th>Quantity</th>
            <th>Rate</th>
            <th>Value</th>
            {{--Outward--}}
            <th>Quantity</th>
            <th>Rate</th>
            <th>Value</th>
            {{--Closing--}}
            <th>Quantity</th>
            <th>Rate</th>
            <th>Value</th>
        </tr>
        </thead>

        @if(isset($categories))
            <tbody>
                @php
                    $total_opening_grand_value = 0;
                    $total_inwards_grand_value = 0;
                    $total_outwards_grand_value = 0;
                    $total_closing_grand_value = 0;
                @endphp
                @foreach($categories as $key => $category)
                    <tr>
                        <td class="text-left padding-1"><b>{{ $category->name }}</b></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                        <td class="text-left padding-1"></td>
                    </tr>
                    @php
                        $opening_grand_value = 0;
                        $inwards_grand_value = 0;
                        $outwards_grand_value = 0;
                        $closing_grand_value = 0;
                    @endphp
                    @foreach ($items as $item)
                        @if ($item['category_id'] == $category->id)
                            @php
                                $stockDetails = $item->stock($first_date, $last_date);
                                $opening_grand_value += $stockDetails['opening_value'];
                                $inwards_grand_value += $stockDetails['inward_value'];
                                $outwards_grand_value += $stockDetails['outward_value'];
                                $closing_grand_value += $stockDetails['closing_value'];
                            @endphp
                            @include('inventory::report.category_wise_report_table_row', [
                                "type" => $type,
                                "items" => $items,
                                "first_date"=> $first_date,
                                "last_date"=> $last_date,
                            ])
                        @endif
                    @endforeach
                    @php
                        $total_opening_grand_value += $opening_grand_value;
                        $total_inwards_grand_value += $inwards_grand_value;
                        $total_outwards_grand_value += $outwards_grand_value;
                        $total_closing_grand_value += $closing_grand_value;
                    @endphp
                    <tr>
                        <td class="text-left padding-1"><b>Sub Total</b></td>
                        <td class="text-right padding-1"></td>
                        <td class="text-right padding-1"></td>
                        <td class="text-right padding-1"><b>{{ number_format($opening_grand_value, 2)  }}</b></td>
                        <td class="text-right padding-1"></td>
                        <td class="text-right padding-1"></td>
                        <td class="text-right padding-1"><b>{{ number_format($inwards_grand_value, 2) }}</b></td>
                        <td class="text-right padding-1"></td>
                        <td class="text-right padding-1"></td>
                        <td class="text-right padding-1"><b>{{ number_format($outwards_grand_value, 2) }}</b></td>
                        <td class="text-right padding-1"></td>
                        <td class="text-right padding-1"></td>
                        <td class="text-right padding-1"><b>{{ number_format($closing_grand_value, 2) }}</b></td>
                    </tr>
                @endforeach
                @include('inventory::report.category_wise_report_summery',[
                    "total_opening_grand_value"=> $total_opening_grand_value,
                    "total_inwards_grand_value"=> $total_inwards_grand_value,
                    "total_outwards_grand_value"=> $total_outwards_grand_value,
                    "total_closing_grand_value"=> $total_closing_grand_value,
                ])
            </tbody>
        @else
            <tbody>
            <tr>
                <td colspan="13">Data Not Found</td>
            </tr>
            </tbody>
        @endif
    </table>
</main>
<footer>
    © Copyright <strong>goRMG-ERP</strong>. Developed by Skylark Soft Limited.
</footer>
</body>
</html>
