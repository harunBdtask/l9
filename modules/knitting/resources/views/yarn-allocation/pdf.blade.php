<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Yarn Receive Return</title>
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
            width: 190mm;
            min-height: 297mm;
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

    </style>
</head>

<body style="background: white;">
<main>
    <div>
        <div style="width: 100%" class="header-section">
            @includeIf('knitting::pdf.header', ['name' => 'Yarn Allocation Report'])
        </div>

        <div class="body-section" style="margin-top: 0px;">
            <table>
                <tbody>
                <tr>
                    <th>Company:</th>
                    <td>{{ $data->factory->factory_name ?? '' }}</td>
                    <th>Buyer:</th>
                    <td>{{ $data->buyer->name ?? '' }}</td>
                </tr>
                <tr>
                    <th>Uniq Id:</th>
                    <td>{{ $data->uniq_id }}</td>
                    <th>Order Number:</th>
                    <td>{{ $data->order_number }}</td>
                </tr>
                <tr>
                    <th>Allocation Date:</th>
                    <td colspan="3">{{ $data->allocation_date }}</td>
                </tr>
                </tbody>
            </table>
            <div style="margin-top: 40px"></div>
            <table>
                <thead style="background-color: #c8f6c2;">
                <tr>
                    <th>SL</th>
                    <th>Supplier</th>
                    <th>Lot No</th>
                    <th>Allocated Quantity</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data->details as $key => $value)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $value->supplier->name ?? '' }}</td>
                        <td>{{ $value->yarn_lot }}</td>
                        <td>{{ $value->allocated_qty }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #fcffc6;">
                    <td colspan="3" class="text-right"><strong style="margin-right: 5px">Total: </strong></td>
                    <td><strong>{{ $data->details->sum('allocated_qty') }}</strong></td>
                </tr>
                </tbody>
            </table>
            <div style="margin-top: 20px"></div>
            @php
                $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            @endphp
            <strong>
                Total Allocated Quantity: {{ $data->details->sum('allocated_qty') }}
            </strong> <br>
            <strong>In word: {{ ucwords($digit->format($data->details->sum('allocated_qty'))) }} Kg Zero
                Grams </strong>
            <div style="margin-top: 16mm">
                <table class="borderless">
                    <tbody>
                    <tr>
                        <td class='text-center'><u>Checked By</u></td>
                        <td class="text-center"><u>Approved By</u></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</body>
</html>
