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
                    Booking No: <b> {{ $trimsBookings->unique_id ?? '' }}</b><br>
                    Booking Date: <b> {{ $trimsBookings->booking_date ?? ''}}</b><br>
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
                        style="font-size: 12pt; font-weight: bold;">{{ $type == 'short' ? 'Short Trims Bookings Sheet' : 'Trims Bookings Sheet' }}</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
        <br>


        <table class="borderless">
            <tr>
                <td><b>TO</b></td>
            </tr>
            <tr>
                <th class="text-left">Supplier Name</th>
                <td>{{ $trimsBookings ? optional($trimsBookings->supplier)->name : ''  }}</td>
                @if(request('type'))
                    <th class="text-left">Booking QTY:</th>
                    <td>{{ round($totalQty) ?? '' }}</td>
                @else
                    <th class="text-left">Booking Amount:</th>
                    <td>{{ $totalAmount ?? '' }} {{ $trimsBookings->currency ?? '' }}</td>
                @endif
            </tr>

            <tr>
                <th class="text-left">Address:</th>
                <td>{{ $trimsBookings->address ?? '' }} </td>
                <th class="text-left">Season:</th>
                <td>{{ $trimsBookings->season ?? ''}}</td>
            </tr>

            <tr>
                <th class="text-left">Attention:</th>
                <td>{{ $trimsBookings->attention ?? ''}}</td>
                <th class="text-left">Delivery Date:</th>
                <td>{{  $trimsBookings->delivery_date ?? '' }}</td>
            </tr>

            <tr>
                <th class="text-left">Dealing Merchant:</th>
                <td>{{ $trimsBookings->dealing_merchant ?? ''}}</td>
                <th class="text-left">Buyer:</th>
                <td>{{ $trimsBookings->buyer->name ?? ''}}</td>
            </tr>

            <tr>
                <th class="text-left">Delivery To:</th>
                <td>{{ $trimsBookings->delivery_to ?? ''}}</td>
                <th class="text-left">Remarks:</th>
                <td>{{ $trimsBookings->remarks ?? ''}}</td>
            </tr>

        </table>

        {{--    no sensitivity--}}

        @if( count($trimsBookingsDetailsNoSensitivity) >= 1 )
            <div style="margin-top: 15px;">
                <table>
                    @foreach((collect($trimsBookingsDetailsNoSensitivity)->flatten(1))->groupBy('style_name') as $style => $trimsDetails)
                        <tr style="border:none">
                            <td colspan="5">
                                No Sensitive (Unique Id):
                                {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}
                                Style:{{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}
                                Po
                                Qty:{{ collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum() }}
                            </td>
                            <td colspan="8">
                                Po No:
                                {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}
                            </td>
                        </tr>

                        <tr>
                            {{--                            <th>SL</th>--}}
                            <th>Item</th>
                            <th>Item Description</th>
                            <th>Color</th>
                            <th>Gmt Qty</th>
                            <th>Apr. Shade / Code</th>
                            <th>Ac Cons</th>
                            <th>T Cons</th>
                            <th>Ac Qty</th>
                            <th>T Qty</th>
                            <th>UOM</th>
                            @if(!request('type'))
                                <th>Rate</th>
                                <th>Amount</th>
                            @endif
                            <th>Remarks</th>
                        </tr>
                        @php
                            $index = 0;
                            $total = 0;
                            $totalQty = 0;
                        @endphp
                        @foreach($trimsDetails->groupBy('item_name') as $key => $bookingDetails)
                            @php
                                $index++;
                                $subTotal = 0;
                                $subQty = 0;
                            @endphp
                            @foreach($bookingDetails->pluck('details') as $item)
                                @if($item)
                                    <tr>
                                        {{--                                        <td>{{ $index }}</td>--}}
                                        <td>{{ $key }}</td>
                                        <td>{{ $item['item_description'] ?? '' }}</td>
                                        <td>{{ $item['color'] ?? '' }}</td>
                                        <td class="text-right">{{ $item['wo_qty'] ?? '' }}</td>
                                        <td>{{ $item['item_color'] ?? '' }}</td>
                                        <td class="text-right">{{  number_format($item['actual_cons'], 2) ?? 0 }}</td>
                                        <td class="text-right">{{  number_format($item['total_cons'], 2) ?? 0 }}</td>
                                        <td class="text-right">{{ isset($item['wo_total_qty'] ) ? number_format($item['wo_total_qty'] , 2) : 0}}</td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ? ceil($item['wo_total_qty']) : 0}}</td>
                                        <td>{{ $item['uom'] ?? null}}</td>
                                        @if(!request('type'))
                                            <td class="text-right">{{ $item['rate'] ?? null }}</td>
                                        @endif

                                        @php
                                            $total += $item['amount'] ?? 0;
                                            $subTotal += $item['amount'] ?? 0;
                                            $subQty +=  $item['wo_total_qty'] ?? 0;
                                            $totalQty += $item['wo_total_qty'] ?? 0;
                                        @endphp
                                        @if(!request('type'))
                                            <td class="text-right">{{ $item['amount'] ?? null }}</td>
                                        @endif
                                        <td>{{ $item['remarks'] ?? null }}</td>
                                    </tr>
                                @else
                                    <tr>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <th colspan="7" style="text-align: right">Sub Total</th>
                                <td class="text-right"><b>{{ $subQty }}</b></td>
                                <td class="text-right"><b>{{ null }}</b></td>
                                <td></td>
                                @if(!request('type'))
                                    <td></td>
                                    <td class="text-right"><b>{{ $subTotal }}</b></td>
                                @endif
                                <td></td>
                            </tr>
                        @endforeach
                    @endforeach
                </table>

            </div>
        @endif
        {{--        end no sensitivity--}}

        {{--        start contrast color sensitivity--}}
        @if( count($trimsBookingsDetailsContrastColorSensitivity) >= 1 )
            <div style="margin-top: 15px;">
                <table>
                    @foreach(collect($trimsBookingsDetailsContrastColorSensitivity)->flatten(1)->groupBy('style_name') as $style => $trimsDetails)
                        <tr style="border:none">
                            <td colspan="5">
                                Contrast Color (Unique Id):
                                {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}
                                Style:
                                {{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}
                                Po Qty:
                                {{ collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum()}}
                            </td>

                            <td colspan="8">
                                Po No:
                                {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}
                            </td>
                        </tr>

                        <tr>
                            {{--                            <th>SL</th>--}}
                            <th>Item</th>
                            <th>Item Description</th>
                            <th>Color</th>
                            <th>Gmt Qty</th>
                            <th>Apr. Shade / Code</th>
                            <th>Ac Cons</th>
                            <th>T Cons</th>
                            <th>Ac Qty</th>
                            <th>T Qty</th>
                            <th>UOM</th>
                            @if(!request('type'))
                                <th>Rate</th>
                                <th>Amount</th>
                            @endif
                            <th>Remarks</th>
                        </tr>
                        @php
                            $index = 0;
                            $total = 0;
                            $totalQty = 0;
                        @endphp

                        @foreach($trimsDetails->groupBy('item_name') as $key => $bookingDetails)
                            @php
                                $index++;
                                $subTotal = 0;
                                $subQty = 0;
                            @endphp
                            @foreach($bookingDetails->pluck('details') as $item)
                                @if($item)
                                    <tr>
                                        {{--                                        <td>{{ $index }}</td>--}}
                                        <td>{{ $key }}</td>
                                        <td>{{ $item['item_description'] ?? '' }}</td>
                                        <td>{{ $item['color'] ?? '' }}</td>
                                        <td class="text-right">{{ $item['wo_qty'] ?? '' }}</td>
                                        <td>{{ $item['item_color'] ?? '' }}</td>
                                        <td class="text-right">{{ number_format($item['actual_cons'], 2) }}</td>
                                        <td class="text-right">{{ number_format($item['total_cons'], 2) }}</td>
                                        <td class="text-right">{{ isset($item['wo_total_qty'] ) ? number_format($item['wo_total_qty'] , 2) : 0}}</td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ? ceil($item['wo_total_qty']) : 0}}</td>
                                        <td>{{ $item['uom'] ?? ''}}</td>
                                        @if(!request('type'))
                                            <td class="text-right">{{ $item['rate'] ?? '' }}</td>
                                        @endif
                                        @php
                                            $total += $item['amount'] ?? 0;
                                            $subTotal += $item['amount'] ?? 0;
                                            $subQty +=  $item['wo_total_qty'] ?? 0;
                                            $totalQty += $item['wo_total_qty'] ?? 0;
                                        @endphp
                                        @if(!request('type'))
                                            <td class="text-right">{{ $item['amount'] ?? '' }}</td>
                                        @endif
                                        <td>{{ $item['remarks'] ?? null  }}</td>
                                    </tr>
                                @else
                                    <tr></tr>
                                @endif
                            @endforeach
                            <tr>
                                <th colspan="7" style="text-align: right">Sub Total</th>
                                <td class="text-right"><b>{{ $subQty }}</b></td>
                                <td class="text-right"><b>{{ null }}</b></td>
                                <td></td>
                                @if(!request('type'))
                                    <td></td>
                                    <td class="text-right"><b>{{ $subTotal }}</b></td>
                                @endif
                                <td></td>
                            </tr>
                        @endforeach
                    @endforeach

                </table>
            </div>
        @endif
        {{--        end contrast color sensitivity--}}

        {{--        size sensitivity--}}
        @if(count($trimsBookingsDetailsSizeSensitivity) >= 1)
            <div style="margin-top: 15px;">
                <table>
                    @foreach(collect($trimsBookingsDetailsSizeSensitivity->flatten(1))->groupBy('style_name') as $style => $trimsDetails)
                        <tr style="border:none">
                            <td colspan="7">
                                Size Sensitivity(Unique Id):
                                {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}
                                Style:
                                {{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}
                                Po Qty:
                                {{  collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum() }}
                            </td>

                            <td colspan="8">
                                Po No:
                                {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}
                            </td>
                        </tr>
                        <tr>
                            {{--                            <th>SL</th>--}}
                            <th>Item</th>
                            <th>Item Description</th>
                            <th>Color</th>
                            <th>Gmt Qty</th>
                            <th>Apr. Shade / Code</th>
                            <th>Item Sizes</th>
                            <th>Gmts Sizes</th>
                            <th>Ac Cons</th>
                            <th>T Cons</th>
                            <th>Ac Qty</th>
                            <th>T Qty</th>
                            <th>UoM</th>
                            @if(!request('type'))
                                <th>Rate</th>
                                <th>Amount</th>
                            @endif
                            <th>Remarks</th>
                        </tr>
                        @php
                            $index = 0;
                            $total = 0;
                            $totalQty = 0;
                        @endphp
                        @foreach($trimsDetails->groupBy('item_name') as $key => $bookingDetails)
                            @php
                                $index++;
                                $subTotal = 0;
                                $subQty = 0;
                            @endphp
                            @foreach($bookingDetails->pluck('details') as $item)
                                @if($item)
                                    <tr>
                                        {{--                                        <td>{{ $index }}</td>--}}
                                        <td>{{ $key }}</td>
                                        <td>{{ $item['item_description'] ?? '' }}</td>
                                        <td>{{ $item['color'] ?? '' }}</td>
                                        <td class="text-right">{{ $item['wo_qty'] ?? '' }}</td>
                                        <td>{{ $item['item_color'] ?? '' }}</td>
                                        <td>{{ $item['item_size'] ?? '' }}</td>
                                        <td>{{ $item['size'] ?? '' }}</td>
                                        <td class="text-right">{{  number_format($item['actual_cons'], 2) }}</td>
                                        <td class="text-right">{{  number_format($item['total_cons'], 2) }}</td>
                                        <td class="text-right">{{ isset($item['wo_total_qty'] ) ? number_format($item['wo_total_qty'] , 2) : 0}}</td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ? ceil($item['wo_total_qty']) : 0}}</td>
                                        <td>{{ $item['uom'] ?? ''}}</td>
                                        @if(!request('type'))
                                            <td class="text-right">{{ $item['rate'] ?? '' }}</td>
                                        @endif
                                        @php
                                            $total += $item['amount'] ?? 0;
                                            $subTotal += $item['amount'] ?? 0;
                                            $subQty +=  $item['wo_total_qty'] ?? 0;
                                            $totalQty += $item['wo_total_qty'] ?? 0;
                                        @endphp
                                        @if(!request('type'))
                                            <td class="text-right">{{ $item['amount'] ?? '' }}</td>
                                        @endif
                                        <td>{{ $item['remarks'] ?? null }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <th colspan="9" style="text-align: right">Sub Total</th>
                                <td class="text-right"><b>{{ $subQty }}</b></td>
                                <td class="text-right"><b>{{ null }}</b></td>
                                <td></td>
                                @if(!request('type'))
                                    <td></td>
                                    <td class="text-right"><b>{{ $subTotal }}</b></td>
                                @endif
                                <td></td>
                            </tr>
                        @endforeach
                    @endforeach

                </table>
            </div>
        @endif
        {{--        end size sensitivity--}}

        {{--        color and size sensitivity--}}
        @if(count($trimsBookingsDetailsColorAndSizeSensitivity) >= 1)
            <div style="margin-top: 15px;">
                <table>
                    @foreach(collect($trimsBookingsDetailsColorAndSizeSensitivity)->flatten(1)->groupBy('style_name') as $style => $trimsDetails)
                        <tr style="border:none">
                            <td colspan="7">
                                Color & Size Sensitivity (Unique Id):
                                {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}
                                Style:{{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}
                                Po Qty:{{ collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum()  }}
                            </td>
                            <td colspan="8">
                                Po No:
                                {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}
                            </td>
                        </tr>
                        <tr>
                            {{--                            <th>SL</th>--}}
                            <th>Item</th>
                            <th>Item Description</th>
                            <th>Color</th>
                            <th>Gmt Qty</th>
                            <th>Apr. Shade / Code</th>
                            <th>Item Sizes</th>
                            <th>Gmt Size</th>
                            <th>Ac Cons</th>
                            <th>T Cons</th>
                            <th>Ac Qty</th>
                            <th>T Qty</th>
                            <th>UoM</th>
                            @if(!request('type'))
                                <th>Rate</th>
                                <th>Amount</th>
                            @endif
                            <th>Remarks</th>
                        </tr>
                        @php
                            $index = 0;
                            $total = 0;
                            $totalQty = 0;
                        @endphp
                        @foreach($trimsDetails->groupBy('item_name') as $key => $bookingDetails)
                            @php
                                $index++;
                                $subTotal = 0;
                                $subQty = 0;
                                $uom = $bookingDetails->unique('cons_uom_value')->pluck('cons_uom_value')->implode(',');
                            @endphp
                            @foreach($bookingDetails->pluck('details') as $item)
                                @if($item)
                                    <tr>
                                        {{--                                        <td>{{ $index }}</td>--}}
                                        <td>{{ $key }}</td>
                                        <td>{{ $item['item_description'] ?? '' }}</td>
                                        <td>{{ $item['color'] ?? '' }}</td>
                                        <td class="text-right">{{ $item['wo_qty'] ?? '' }}</td>
                                        <td>{{ $item['item_color'] ?? '' }}</td>
                                        <td>{{ $item['item_size'] ?? '' }}</td>
                                        <td>{{ $item['size'] ?? ''}}</td>
                                        <td class="text-right">{{  number_format($item['actual_cons'], 2) }}</td>
                                        <td class="text-right">{{  number_format($item['total_cons'], 2) }}</td>
                                        <td class="text-right">{{ isset($item['wo_total_qty'] ) ? number_format($item['wo_total_qty'] , 2):0}} </td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ? ceil($item['wo_total_qty']) : 0}}</td>
                                        <td>{{ $item['uom'] ?? ''}}</td>
                                        @if(!request('type'))
                                            <td class="text-right">{{ $item['rate'] ?? '' }}</td>
                                        @endif
                                        @php
                                            $total += $item['amount'] ?? 0;
                                            $subTotal += $item['amount'] ?? 0;
                                            $subQty +=  $item['wo_total_qty'] ?? 0;
                                            $totalQty += $item['wo_total_qty'] ?? 0;
                                        @endphp
                                        @if(!request('type'))
                                            <td class="text-right">{{ $item['amount'] ?? '' }}</td>
                                        @endif
                                        <td>{{ $item['remarks'] ?? null  }}</td>
                                    </tr>
                                @else
                                    <tr></tr>
                                @endif
                            @endforeach
                            <tr>
                                <th colspan="9" style="text-align: right">Sub Total</th>
                                <td class="text-right"><b>{{ $subQty }}</b></td>
                                <td class="text-right"><b>{{ null }}</b></td>
                                <td></td>
                                @if(!request('type'))
                                    <td></td>
                                    <td class="text-right"><b>{{ $subTotal }}</b></td>
                                @endif
                                <td></td>
                            </tr>
                        @endforeach
                    @endforeach

                </table>
            </div>
        @endif
        {{--        end color and size sensitivity--}}

        @if(!request('type'))
            <div style="margin-top: 15px;">
                <table class="borderless">
                    <thead>
                    <tr>
                        <td class="text-left">
                            <span style="font-size: 12pt; font-weight: bold;"> Total Booking Amount</span> :
                            {{ $totalAmount ?? ''   }} {{ $trimsBookings->currency ?? '' }}
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                        <span
                            style="font-size: 12pt; font-weight: bold;">Total Booking Amount (in word)</span>: {{ $amountInWord ?? '' }} {{$trimsBookings->currency ?? ''}}
                        </td>
                    </tr>
                    </thead>
                </table>
            </div>
        @endif
    </div>
    <div style="margin-top: 8mm">
        <table class="borderless">
            <tbody>
            <tr>
                <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
            </tr>
            @if(isset($trimsBookings))
                @php $index = 0; @endphp
                @foreach(collect($trimsBookings->terms_condition) as $key => $item)
                    <tr>
                        @if($item['term'])
                            @php $index += 1; @endphp
                            <td style="font-size: 12px">{{ $index  }}.{{ $item['term'] }}</td>
                        @endif
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    @include('skeleton::reports.downloads.signature')
    <style>
        footer {
            top: 150% !important;
        }
    </style>
</div>
