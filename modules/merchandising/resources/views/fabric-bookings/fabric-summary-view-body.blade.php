<div>
    {{--    <div>--}}
    {{--        <table class="borderless">--}}
    {{--            <thead>--}}
    {{--            <tr>--}}
    {{--                <td class="text-left">--}}
    {{--                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>--}}
    {{--                    {{ factoryAddress() }}--}}
    {{--                    <br>--}}
    {{--                </td>--}}
    {{--                <td>--}}
    {{--                    Booking No: <span> {{ $trimsBookings->unique_id ?? '' }}</span><br>--}}
    {{--                    Booking Date: <span> {{ $trimsBookings->booking_date ?? ''}}</span><br>--}}
    {{--                    Revise No: <span> {{ '1' }}</span><br>--}}
    {{--                </td>--}}
    {{--            </tr>--}}
    {{--            </thead>--}}
    {{--        </table>--}}
    {{--        <hr>--}}
    {{--    </div>--}}
    <center>
        <table style="border: 1px solid black;width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    @if((request('type') == 'short'))
                        <span style="font-size: 12pt; font-weight: bold;">Short Fabric Bookings Summary</span>
                    @else
                        <span style="font-size: 12pt; font-weight: bold;">Fabric Bookings Summary</span>
                    @endif
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
    <br>
    @php
        $fabricDetailsSum=0;
        $total_amount_sum = 0;

    @endphp

    <div class="body-section" style="margin-top: 0px;">
        <table>
            <tr>
                <th>Company Name:</th>
                <td>{{ optional($fabricBookings)->factory->factory_name ?? ''}}</td>
                <th>Buyer Name:</th>
                <td>{{ optional($fabricBookings)->buyer->name ?? '' }}</td>
                <th>Booking No/Reference:</th>
                <td>{{ $fabricBookings->unique_id ?? '' }}</td>
            </tr>

            <tr>
                <th>Address:</th>
                <td colspan="3">{{ optional($fabricBookings)->factory->factory_address ?? '' }}</td>
                <th>Booking Date:</th>
                <td>{{ $fabricBookings->booking_date ?? '' }}</td>
            </tr>

            <tr>
                <th>Supplier Name:</th>
                <td>{{  optional($fabricBookings)->supplier->name ?? '' }}</td>
                <th> Season:</th>
                <td>{{ $fabricBookings->season ?? ''}}</td>
                <th>Delivery Date:</th>
                <td>{{ $fabricBookings->delivery_date ?? ''}}</td>
            </tr>

            <tr>
                <th>Address:</th>
                <td colspan="3">{{ optional($fabricBookings)->supplier->address_1 ?? ''  }}</td>
                <th>Approval Status:</th>
                <td>{{ $fabricBookings ? ($fabricBookings->ready_to_approve == '1' ? 'yes' : 'No') : '' }}</td>
            </tr>

            <tr>
                <th>Attention:</th>
                <td>{{ $fabricBookings->attention ?? '' }}</td>
                <th>Dept :</th>
                <td>{{ $fabricBookings->productDept ?? '' }}</td>
                <th>Dealing Merchant:</th>
                <td>{{ $fabricBookings->dealing_merchant ?? '' }}</td>
            </tr>
            <tr>
                <th>Currency:</th>
                <td>{{ optional($fabricBookings)->currency->currency_name ?? '' }}</td>
                <th>Order Qty :</th>
                <td>{{ $fabricBookings->budget_qty ?? 0 }} {{ $fabricBookings->order_uom ?? '' }}</td>
            </tr>
            <tr>
                <th>Fabric Composition:</th>
                <td colspan="5">{{ $fabricBookings->fabric_composition ?? ''}}</td>
            </tr>
            <tr>
                <th>Remarks:</th>
                <td colspan="5">{{ $fabricBookings->remarks ?? '' }}</td>
            </tr>

        </table>

        @if(isset($fabricBookings) && count((optional($fabricBookings)->details)) > 0)
            <div style="margin-top: 15px;">
                @php
                    $fabricDetailsUomWise = collect($fabricBookings->details)->groupBy('uom');
                    //dump($fabricDetailsUomWise)
                @endphp
                <table>
                    <tr>
                        <td colspan="13" class="text-center"><b>Fabric Details ({{ ($fabricDetailsUomWise)->keys()[0] }}
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
                        <th>Total Fabric Qty</th>
                        <th>Avg Rate</th>
                        <th>Amount</th>
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
                                    <td>{{ $item['code']  ?? ''}}</td>
                                    <td>{{ $item['gmts_color']  ?? ''}}</td>
                                    <td>{{ $item['fabric_color']  ?? ''}}</td>
                                    <td>{{ $item['cad_consumption'] ?? '' }}</td>
                                    <td>{{ $item['process_loss'] ?? '' . '%' }} </td>
                                    <td>{{ $item['fabric_consumption'] ?? '' }}</td>
                                    <td>{{ $item['uom']  ?? ''}}</td>
                                    <td class="text-right">{{ round($item['total_fabric_qty'])  ?? ''}}</td>
                                    <td class="text-right">{{ number_format($item['rate'], 2)  ?? ''}}</td>
                                    <td class="text-right">{{ number_format($item['amount'], 4)  ?? ''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="11" class="text-center">Total</th>
                                <td class="text-right">{{ round(collect($details)->sum('total_fabric_qty')) }}</td>
                                <td></td>
                                @php
                                    $sum  = collect($details)->sum('amount') ?? 0;
                                    $fabricDetailsSum += $sum ?? '' ;
                                @endphp
                                <td class="text-right">{{ number_format($sum, 4) }}</td>
                            </tr>
                        @endforeach

                    @endif
                </table>
            </div>

        @endif


    </div>

    <div style="margin-top: 16mm">
        @php
            $numberFormatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
            $inword =ucwords(in_words($sum ?? 0));
        @endphp
        <span><b>Total Fabric Amount: {{ $sum ?? 0 }} {{ optional($fabricBookings)->currency->currency_name ?? '' }}</b></span><br>
        <span><b>In Words: {{ $inword  }} {{ optional($fabricBookings)->currency->currency_name ?? '' }}</b> </span>
    </div>
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
