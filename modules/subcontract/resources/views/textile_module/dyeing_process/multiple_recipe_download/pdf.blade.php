<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Multiple Recipe Download PDF</title>
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
            font: 9pt "Tahoma";
        }

        .page {
            /*min-width: 190mm;*/
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


        th {
            padding-left: 0px;
            padding-right: 0px;
            padding-top: 0px;
            padding-bottom: 0px;
        }

        td {
            padding-left: 2px;
            padding-right: 2px;
            padding-top: 1px;
            padding-bottom: 1px;
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

        @page {
            size: A4;
            margin: 5mm;
            margin-left: 10mm;
            margin-right: 10mm;
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

        footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
    </style>
</head>

<body style="background: white;">
<main>
    <div class="page">
        <div style="width: 100%" class="header-section">
            @includeIf('subcontract::pdf.header', ['name' => 'Multiple Recipe Download'])
        </div>
        <table class="reportTable" style="width: 100%;margin-top: 30px;">
            <thead>
            <tr>
                <th>Item Name</th>
                <th>Dosing Percent</th>
                <th>Dosing Quantity</th>
                <th>G/Ltr</th>
                <th>GPL Quantity</th>
                <th>Unit</th>
                <th>Additional Quantity(KG)</th>
                <th>Remarks</th>
            </tr>

            </thead>
            <tbody>

            @foreach ($dyeingRecipe as $details)
                <tr>
                    <td class="text-center" style="background-color: lightgrey;" colspan="9">
                        <strong>{{ $details->first()->recipeOperation->name }}</strong>
                    </td>
                </tr>
                @foreach ($details as $item)
                    <tr>
                        <td>{{ $item->dsItem->name }}</td>
                        <td>{{ $item->total_percentage }}</td>
                        <td>
                            @if ($item->total_percentage)
                                {{ number_format($item->sum_total_qty, 3) }}
                            @endif
                        </td>
                        <td>{{$item->total_g_per_ltr}}</td>
                        <td>
                            @if ($item->total_g_per_ltr)
                                {{ number_format($item->sum_total_qty, 3) }}
                            @endif
                        </td>
                        <td>{{$item->unitOfMeasurement->name}}</td>
                        <td></td>
                        <td>{{$item->remarks}}</td>
                    </tr>
                @endforeach
            @endforeach

            </tbody>
        </table>
    </div>
</main>
</body>
</html>
