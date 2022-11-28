<div class="body-section" style="margin-top: 0px;">
    <table class="border">
        <thead>
        <tr>
            <td class="text-center" style="white-space: nowrap;">
                <span style="font-size: 12pt; font-weight: bold;">
                    @if(request('type') == 'short')
                        Short Fabric Bookings Summary
                    @else
                        Fabric Bookings Summary
                    @endif
                </span>
                <br>
            </td>
        </tr>
        </thead>
    </table>
    <br>

    <table>
        <tr>
            <th align="left">Company Name:</th>
            <td align="left">{{ optional($fabricBookings)->factory->factory_name ?? ''}}</td>
            <th align="left">Buyer Name:</th>
            <td align="left">{{ optional($fabricBookings)->buyer->name ?? '' }}</td>
            <th align="left">Booking No/Reference:</th>
            <td align="left">{{ $fabricBookings->unique_id ?? '' }}</td>
        </tr>

        <tr>
            <th align="left">Address:</th>
            <td align="left" colspan="3">{{ optional($fabricBookings)->factory->factory_address ?? '' }}</td>
            <th align="left">Booking Date:</th>
            <td align="left">{{ $fabricBookings->booking_date ?? '' }}</td>
        </tr>

        <tr>
            <th align="left">Supplier Name:</th>
            <td align="left">{{  optional($fabricBookings)->supplier->name ?? '' }}</td>
            <th align="left"> Season:</th>
            <td align="left">{{ $fabricBookings->season ?? ''}}</td>
            <th align="left">Delivery Date:</th>
            <td align="left">{{ $fabricBookings->delivery_date ?? ''}}</td>
        </tr>

        <tr>
            <th align="left">Address:</th>
            <td align="left" colspan="3">{{ optional($fabricBookings)->supplier->address_1 ?? ''  }}</td>
            <th align="left">Approval Status:</th>
            <td align="left">{{ $fabricBookings ? ($fabricBookings->ready_to_approve == '1' ? 'yes' : 'No') : '' }}</td>
        </tr>

        <tr>
            <th align="left">Attention:</th>
            <td align="left">{{ $fabricBookings->attention ?? '' }}</td>
            <th align="left">Dept :</th>
            <td align="left">{{ $fabricBookings->productDept ?? '' }}</td>
            <th align="left">Dealing Merchant:</th>
            <td align="left">{{ $fabricBookings->dealing_merchant ?? '' }}</td>
        </tr>
        <tr>
            <th align="left">Currency:</th>
            <td align="left">{{ optional($fabricBookings)->currency->currency_name ?? '' }}</td>
            <th align="left">Order Qty :</th>
            <td align="left">{{ $fabricBookings->budget_qty ?? 0 }} {{ $fabricBookings->order_uom ?? '' }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th align="left">Fabric Composition:</th>
            <td align="left" colspan="5">{{ $fabricBookings->fabric_composition ?? ''}}</td>
        </tr>
        <tr>
            <th align="left">Remarks:</th>
            <td align="left" colspan="5">{{ $fabricBookings->remarks ?? '' }}</td>
        </tr>

    </table>

    @php
        $fabricDetailsSum=0;
        $total_amount_sum = 0;
        if (request()->get('type') != 'without-price')
            $withOutPrice = 0;
        else {
            $withOutPrice = 1;
        }
    @endphp

    @if(isset($fabricBookings) && count((optional($fabricBookings)->details)) > 0)
        <div style="margin-top: 15px;">
            @php
                $fabricDetailsUomWise = collect($fabricBookings->details)->groupBy('uom');
                //dump($fabricDetailsUomWise)
            @endphp
            <table>
                <tr>
                    <td colspan="14" class="text-center"><b>Fabric Details ({{ ($fabricDetailsUomWise)->keys()[0] }}
                            )</b></td>
                </tr>
                <tr>
                    <th>SL</th>
                    <th>Style</th>
                    <th>Gmts Item</th>
                    <th>Description</th>
                    <th>Code</th>
                    <th>Gmts Color</th>
                    <th>Contrast Color</th>
                    <th>CAD Consumption</th>
                    <th>Process Loss %</th>
                    <th>Fabric <br> Consumption</th>
                    <th>UOM</th>
                    <th>T. F. Qty</th>
                    @if(request()->get('type') != 'without-price')
                        <th>Avg Rate</th>
                        <th>Amount</th>
                    @endif
                </tr>
                @if(isset($fabricBookings) && optional($fabricBookings)->details)
                    @foreach($fabricDetailsUomWise as $index => $details)
                        @if( !($loop->first))
                            <tr>
                                <th colspan="13" class="text-center">Fabric Details ({{ $index }})</th>
                            </tr>
                        @endif
                        @foreach($details as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $item['style_name'] ?? ''  }}</td>
                                <td>{{ $item['gmts_item']  ?? '' }}</td>
                                <td>{{ $item['body_parts']  ?? ''}}, {{ $item['composition'] ?? '' }},
                                    Gsm- {{ $item['gsm'] }},
                                    Dia- {{ $item['dia'] }} {{ $item['dia_type'] ?? '' }}
                                    , {{ $item['color_type'] ?? '' }}</td>
                                <td>{{ $item['code'] }}</td>
                                <td>{{ $item['gmts_color']  ?? ''}}</td>
                                <td>{{ $item['fabric_color']  ?? ''}}</td>
                                <td style="text-align: right">{{ $item['cad_consumption'] ?? '' }}</td>
                                <td style="text-align: right">{{ $item['process_loss'] ?? '' . '%' }} </td>
                                <td style="text-align: right">{{ $item['fabric_consumption'] ?? '' }}</td>
                                <td>{{ $item['uom']  ?? ''}}</td>
                                <td style="text-align: right">{{ round($item['total_fabric_qty'])  ?? ''}}</td>
                                @if(!$withOutPrice)
                                    <td style="text-align: right">{{ number_format($item['rate'], 2)  ?? ''}}</td>
                                    <td style="text-align: right">{{ number_format($item['amount'], 4)  ?? ''}}</td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <th
                                colspan="11"
                                class="text-center">
                                Total
                            </th>

                            <th style="text-align: right">{{ round(collect($details)->sum('total_fabric_qty')) }}</th>
                            @if(!$withOutPrice)
                                <td></td>
                                @php
                                    $sum = collect($details)->sum('amount') ?? 0;
                                    $fabricDetailsSum += $sum;
                                @endphp
                                <th style="text-align: right">{{ number_format($sum, 4) }}</th>
                            @endif
                        </tr>
                    @endforeach

                @endif
            </table>
        </div>

    @endif

    @if(!$withOutPrice)
        <div style="margin-top: 16mm">
            @php
                $numberFormatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
                $inword =ucwords(in_words($sum ?? 0));
            @endphp
            <span><b>Total Fabric Amount: {{ number_format($sum ?? 0, 4) }} {{ optional($fabricBookings)->currency->currency_name ?? '' }}</b></span><br>
            <span><b>In Words: {{ $inword  }} {{ optional($fabricBookings)->currency->currency_name ?? '' }}</b> </span>
        </div>
    @endif

    <div style="margin-top: 10mm">
        <table class="borderless">
            <tbody>
            <tr>
                <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
            </tr>
            @if(isset($fabricBookings))
                @foreach($fabricBookings->terms_condition as $item)
                    <tr>
                        <td>{{ '* '. $item }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
