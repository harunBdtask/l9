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
<h3 style="text-align: center; margin-top: -5px;">Daily Report</h3>
<p style="text-align: center;font-weight: 700;">
    {{ isset($first_date) ? "For " . \Carbon\Carbon::create($first_date)->format("Y-M-d") : null  }}
    <br><br>
</p>

<main>
    <table class="reportTable">
        <thead>
        <tr>
            <th rowspan="2">Particulars</th>
            <th colspan="3">Inwards</th>
            <th colspan="3">Outwards</th>
        </tr>


        <tr>


            {{--inward--}}
            <th>Quantity</th>
            <th>Rate</th>
            <th>Value</th>
            {{--Outward--}}
            <th>Quantity</th>
            <th>Rate</th>
            <th>Value</th>
        </tr>
        </thead>

        @if(isset($items))
            <tbody>
                @php
                    $inwards_grand_value = 0;
                    $outwards_grand_value = 0;
                @endphp
                @foreach($items as $key => $item)
                @php
                    $stockDetails = $item->stock($first_date,$last_date);
                    $inwards_grand_value += $stockDetails['inward_value'];
                    $outwards_grand_value += $stockDetails['outward_value'];
                @endphp
                    @include('inventory::report.daily_report_table_row',[
                        "stockDetails"=>$stockDetails
                    ])
                @endforeach
                @include('inventory::report.daily_report_table_row_summery',[
                    "inwards_grand_value"=>$inwards_grand_value,
                    "outwards_grand_value"=>$outwards_grand_value,
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
