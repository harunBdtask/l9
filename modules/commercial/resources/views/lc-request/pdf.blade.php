<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Budget</title>
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
<div class="page">
    <div class="">
        <div class="header-section" style="padding-bottom: 0px;">
            <table class="borderless">
                <thead>
                <tr>
                    <td class="text-left">
                        <img src="{{asset('storage/company/company_logo.png')}}" alt="logo" width="200">
                        <br>
                        {{ factoryAddress() }}
                        <span style="float:right;"> <b>{{ $approve_status == 1 ? 'Approved' : '' }} </b></span>
                        <br>
                    </td>
                </tr>
                </thead>
            </table>
            <hr>
        </div>
        <br>

        <div class="body-section" style="margin-top: 0px;">
            <div>
                <table class="borderless">
                    <tr>
                        <td style="width: 80px">ATTN:</td>
                        <td>{{ $attention ?? '' }}</td>
                        <td style="width: 120px">Request Date:</td>
                        <td>{{ $request_date ?? '' }} </td>
                    </tr>
                    <tr>
                        <td>MESSRS:</td>
                        <td>{{ $buyer ? $buyer['name'] : '' }}</td>
                        <td style="width: 120px">OPEN BY:</td>
                        <td>{{ $open_date ?? '' }}</td>
                    </tr>
                </table>
                <br>
                <span>We are pleased to offer you the following goods the terms and conditions stated below: </span>
                <br>
            </div>

            <div style="margin-top: 10px">
                <table>
                    <tr>
                        <th>Sl</th>
                        <th>style#</th>
                        <th>PO#</th>
                        <th>CUSTOMER</th>
                        <th>DESCRIPTION</th>
                        <th>Q'TY(PCS)</th>
                        <th>U/PRICE</th>
                        <th>AMOUNT</th>
                        <th>DELIVERY</th>
                        <th>SHIP MODE</th>
                        <th>C/0</th>
                    </tr>
                    @forelse($details as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item['style_name'] ?? '' }}</td>
                            <td>{{ $item['po_no'] ?? '' }}</td>
                            <td>{{ $item['customer'] ?? '' }}</td>
                            <td>{{ $item['description'] ?? '' }}</td>
                            <td>{{ $item['po_quantity'] ?? 0 }}</td>
                            <td>{{ $item['rate'] ?? 0 }}</td>
                            <td>{{ $item['amount'] ?? 0 }}</td>
                            <td>{{ $item['delivery_date'] ?? 0 }}</td>
                            <td>{{ $item['ship_mode'] ?? 0 }}</td>
                            <td>{{ $item['co'] ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">No Data Found</td>

                        </tr>
                    @endforelse
                </table>
            </div>

            <div style="margin-top: 10px">
                <span>DESCRIPTION: AS ABOVE</span> <br>
                <span>PAYMENT : BY IRREVOCABLE L/C AT SIGHT</span> <br>
                <br>
                <span>Advising Bank:</span> <br>
                <table class="borderless">
                    <tr>
                        <td rowspan="2" style="vertical-align: top;padding:0">Name</td>
                        <td><span>: ROYAL BANK OF CANADA, TRADE SERVICE CENTER</span></td>
                    </tr>
                    <tr>
                        <td><span>180 WELLINGTON STREET WEST, 7TG FLOOR, TORONTO, OM M5J 1J1</span></td>
                    </tr>
                    <tr>
                        <td>SWIFT CODE</td>
                        <td>: ROYCCAT2</td>
                    </tr>
                </table>
                <br>
                <table class="borderless">

                    <tr>
                        <td>SHIP MODE</td>
                        <td>: VESSEL OR AIR</td>
                    </tr>
                    <tr>
                        <td>PORT IN COUNTRY OF ORIGIN</td>
                        <td>: CHITTAGONG, BANGLADESH</td>
                    </tr>
                    <tr>
                        <td>PORT IN USA</td>
                        <td>: SAN PEDRO / LOS ANGELES, CA, USA</td>
                    </tr>
                    <tr>
                        <td>REMARK</td>
                        <td>: L/C TRANSFERABLE BY ROYAL BANK OF CANADA</td>
                    </tr>
                </table>
                <br>
                <span>YOUR VERY TRULY,</span> <br>
                <span>FOR APPAREL SOURCING INTERNATIONAL INC.</span>
            </div>

        </div>

    </div>
</div>
</body>
</html>
