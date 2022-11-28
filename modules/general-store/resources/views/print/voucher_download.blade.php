<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Vourcher</title>
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
<div class="page">
    <div class="header-section" style="padding-bottom: 0px;">
        <table class="borderless">
            <thead>
            <tr>
                <td class="text-center">
                    <span
                        style="font-size: 12pt; font-weight: bold;">{{ factoryName() ?? '' }}</span><br>
                    {{ factoryAddress() ?? '' }}<br>
                    Dhaka,Bangladesh
                </td>
            </tr>
            </thead>
        </table>
        <hr>
    </div>
    <br>
    <div class="body-section" style="margin-top: -10px;">
        <center>
            <table class="border">
                <thead>
                <tr>
                    <td class="text-center">
                        <span style="font-size: 12pt; font-weight: bold;">{{ get_store_name($voucher->store) }}</span><br>
                    </td>
                </tr>
                </thead>
            </table>
        </center>
        <br>
        <div class="border-box">
            <div class="party-info-section">
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <td>{{ 'Stock ' . ucfirst($voucher->type) }}</td>
                        </tr>
                        <tr>
                            <th>Report Title</th>
                            <td>{{ $report_title ?? 'Voucher View' }}</td>
                        </tr>
                        <tr>
                            <th>
                                @if($voucher->type == 'in')
                                    <b>Receive Date</b>
                                @elseif($voucher->type == 'out')
                                    <b>Delivery Date</b>
                                @endif
                            </th>
                            <td>{{ $voucher->trn_date }}</td>
                        </tr>
                        <tr>
                            <th>Reference / Challan No.</th>
                            <td>{{ $voucher->reference ?? 'NONE' }}</td>
                        </tr>
                        <tr>
                            <th>
                                @if($voucher->type == 'in')
                                    Supplier
                                @elseif($voucher->type == 'out')
                                    Consumer
                                @endif
                            </th>
                            <td>
                                @if($voucher->type == 'in')
                                    <span>{{ $voucher->supplier->name }}</span>
                                @elseif($voucher->type == 'out')
                                    <span>{{ $voucher->consumer->screen_name ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>
        <br>

        <table>
            <thead class="thead-light">
            <tr>
                <th class="text-left">Sl No</th>
                <th class="text-left">Item</th>
                <th class="text-left">Category</th>
                @if($voucher->type == "in")
                    <th class="text-right">Qty</th>
                @else
                    <th class="text-right">Delivery Qty</th>
                @endif
                <th class="text-right">Rate</th>
                <th class="text-left">UOM</th>
            </tr>
            </thead>
            <tbody>
            @foreach($voucher->details as $detail)
                <tr>
                    <td class="text-left">{{$loop->iteration}}</td>
                    <td class="text-left">{{ ucwords($items->where('id', $detail->item_id)->first()->name) }}</td>
                    <td class="text-left">{{ ucwords($items->where('id', $detail->item_id)->first()->category->name) }}</td>
                    @if($voucher->type == "in")
                        <td class="text-right">{{ $detail->qty }}</td>
                    @else
                        <td class="text-right">{{ $detail->delivery_qty }}</td>
                    @endif


                    <td class="text-right">{{ $detail->rate }}/=</td>
                    <td class="text-left">{{ $items->where('id', $detail->item_id)->first()->uomDetails->name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br>

        <div style="margin-top: 16mm">
            <table class="borderless">
                <tbody>
                <tr>
                      <td class="text-center"><u>Prepared By</u></td>
                      <td class="text-center"><u>Authorized By</u></td>
                      <td class='text-center'><u>Issued By</u></td>
                      <td class='text-center'><u>Received By</u></td>
                      <td class='text-center'><u>Kardex Posted By</u></td>
                      <td class='text-center'><u>Ledger Posted </u></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>