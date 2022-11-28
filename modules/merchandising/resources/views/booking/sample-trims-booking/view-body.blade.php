<div>

    <div class="body-section" style="margin-top: 0px;">

        <table class="borderless">
            <thead>
            <tr>
                <td class="text-left">
                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                    {{ factoryAddress() }}
                    <br>
                </td>
                <td class="text-right">
                    Booking No: <b> {{ $data->booking_no }}</b><br>
                    Booking Date: <b> {{ $data->booking_date }}</b><br>
                </td>
            </tr>
            </thead>
        </table>
        <hr>

        <table style="border: 1px solid black;width: 30%; margin-left: auto;margin-right: auto;">
            <thead>
            <tr>
                <td class="text-center">
                    <span
                        style="font-size: 12pt; font-weight: bold;">Sample Trims Booking</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
        <br>

        <table class="borderless">
            <tr>
                <th class="text-left">Supplier Name:</th>
                <td>{{ $data->supplier->name ?? '' }}</td>
                <th class="text-left">Pay Mode:</th>
                <td>{{ $data->pay_mode_value }}</td>
            </tr>

            <tr>
                <th class="text-left">Material Source:</th>
                <td>{{ $data->material_source_value }} </td>
                <th class="text-left">Dealing Merchant:</th>
                <td>{{ $data->details[0]['merchant']['screen_name'] ?? '' }}</td>
            </tr>

            <tr>
                <th class="text-left">Buyer Name:</th>
                <td>{{ $data->buyer->name ?? '' }}</td>
                <th class="text-left">Delivery Date:</th>
                <td>{{  $data->delivery_date ?? '' }}</td>
            </tr>

            <tr>
                <th class="text-left">Address:</th>
                <td>{{ $data->location }}</td>
                <th class="text-left">Delivery To:</th>
                <td>{{ $data->delivery_to }}</td>
            </tr>

            <tr>
                <th class="text-left">Approval Status:</th>
                <td></td>
                <th class="text-left">Trims Type:</th>
                <td></td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 15px">
        @if($data->details)
            <table>
                <thead>
                <tr>
                    <th>Requisition Id</th>
                    <th>Style Name</th>
                    <th style="min-width: 270px;">Item Description</th>
                    <th>Req Qty</th>
                    <th>UOM</th>
                    <th>Bal.WO Qty</th>
                    <th>WO Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
                </thead>

                <tbody>
                @foreach($data->details as $key => $detail)
                    <tr>
                        <td>{{ $detail->requisition_no }}</td>
                        <td>{{ $detail->style_name }}</td>
                        <td>
                            {{ $detail->item_names }},
                            {{ $detail->item_des }}
                        </td>
                        <td>{{ $detail->req_qty }}</td>
                        <td>{{ $detail->uom_values }}</td>
                        <td>{{ $detail->balance_wo_qty }}</td>
                        <td class="text-right">{{ $detail->wo_qty }}</td>
                        <td>{{ $detail->rate }}</td>
                        <td class="text-right">{{ $detail->amount }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="6"></td>
                        <td class="text-right">{{ collect($data->details)->sum('wo_qty') }}</td>
                        <td></td>
                        <td class="text-right">{{ collect($data->details)->sum('amount') }}</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>

    <div style="margin-top: 8mm">
        <table class="borderless">
            <tbody>
            <tr>
                <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
            </tr>
            @if($data)
                @php $index = 0; @endphp
                @if($data->terms_and_condition)
                    @foreach(json_decode($data->terms_and_condition) as $key => $item)
                        <tr>
                            @php $index += 1; @endphp
                            <td style="font-size: 12px">{{ $index  }}. {{ $item->term }}</td>
                        </tr>
                    @endforeach
                @endif
            @endif
            </tbody>
        </table>
    </div>
    @include('skeleton::reports.downloads.signature')
</div>
