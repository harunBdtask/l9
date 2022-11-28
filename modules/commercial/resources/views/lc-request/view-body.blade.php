<div>
    <div>
        <table class="borderless">
            <thead>
            <tr>
                <td class="text-left">
                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                    {{ factoryAddress() }}
                    <br>
                </td>
            </tr>
            </thead>
        </table>
        <hr>
    </div>
    <center>
        <table style="border: 1px solid black;width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span
                            style="font-size: 12pt; font-weight: bold;">Lc Request</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
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
