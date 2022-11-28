<div>
    <div>
        <table class="borderless">
            <thead>
            <tr>
                <td style="text-align: center" colspan="12"><b>{{ factoryName() }}</b></td>
            </tr>
            <tr>
                <td style="text-align: center" colspan="12">{{ factoryAddress() }}</td>
            </tr>
            <tr>
                <td style="text-align: center" colspan="12"></td>
            </tr>
            <tr>
                <td style="text-align: center; border: 1px solid" colspan="12"><b>Lc Request</b></td>
            </tr>
            </thead>
        </table>
    </div>
    <br>

    <div style="border: 1px solid">
        <table class="borderless" style="border: 2px solid">
            <tr>
                <th>ATTN:</th>
                <td>{{ $attention ?? '' }}</td>
                <td></td>
                <th>Request Date:</th>
                <td>{{ $request_date ?? '' }} </td>
            </tr>
            <tr>
                <th>MESSRS:</th>
                <td>{{ $buyer ? $buyer['name'] : '' }}</td>
                <td></td>
                <th>OPEN BY:</th>
                <td>{{ $open_date ?? '' }}</td>
            </tr>
        </table>
        <br>
        <span>We are pleased to offer you the following goods the terms and conditions stated below: </span>
        <br>
    </div>

    <br>

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

    <br>
    <br>
    <br>

    <div style="margin-top: 40px">
        <span>DESCRIPTION: AS ABOVE</span> <br>
        <span>PAYMENT : BY IRREVOCABLE L/C AT SIGHT</span> <br>
        <br>
        <span>Advising Bank:</span> <br>
        <table class="borderless">
            <tr>
                <td rowspan="2">Name</td>
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
