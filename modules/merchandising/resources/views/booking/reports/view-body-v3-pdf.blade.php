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
                <th class="text-left">Remarks:</th>
                <td>{{ $trimsBookings->remarks ?? ''}}</td>
            </tr>

            <tr>
                <th class="text-left">Delivery To:</th>
                <td>{{ $trimsBookings->delivery_to ?? ''}}</td>
            </tr>

        </table>
        {{--    no sensitivity--}}

        @if( count($trimsBookingsDetailsNoSensitivity) >= 1 )
            <div style="margin-top: 15px;">
                <table>
                    @foreach((collect($trimsBookingsDetailsNoSensitivity)->flatten(1))->groupBy('style_name') as $style => $trimsDetails)
                        <tr style="border:none">
                            <td colspan="3">
                                No Sensitive (Unique Id):
                                {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}
                                Style:{{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}
                                Po
                                Qty:{{ collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum() }}
                            </td>
                            <td colspan="5">
                                Po No:
                                {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}
                            </td>
                        </tr>

                        <tr>
                            {{--                            <th>SL</th>--}}
                            <th>Item</th>
                            <th>Item Description</th>
                            <th>Gmts. Color</th>
                            <th>Item Color</th>
                            <th>Ac. Qty</th>
                            <th>T. Qty</th>
                            <th>UOM</th>
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
                                        <td>{{ $item['item_color'] ?? '' }}</td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ?? 0}}</td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ? ceil($item['wo_total_qty']) : 0}}</td>
                                        <td>{{ $item['uom'] ?? null}}</td>

                                        @php
                                            $total += $item['amount'] ?? 0;
                                            $subTotal += $item['amount'] ?? 0;
                                            $subQty +=  $item['wo_total_qty'] ?? 0;
                                            $totalQty += $item['wo_total_qty'] ?? 0;
                                        @endphp
                                        <td>{{ $item['remarks'] ?? null }}</td>
                                    </tr>
                                @else
                                    <tr>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <th colspan="4" style="text-align: right">Sub Total</th>
                                <td class="text-right"><b>{{ $subQty }}</b></td>
                                <td class="text-right"><b>{{ null }}</b></td>
                                <td></td>
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
                            <td colspan="3">
                                Contrast Color (Unique Id):
                                {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}
                                Style:
                                {{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}
                                Po Qty:
                                {{ collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum()}}
                            </td>

                            <td colspan="5">
                                Po No:
                                {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}
                            </td>
                        </tr>

                        <tr>
                            {{--                            <th>SL</th>--}}
                            <th>Item</th>
                            <th>Item Description</th>
                            <th>Gmts. Color</th>
                            <th>Item Color</th>
                            <th>Ac. Qty</th>
                            <th>T. Qty</th>
                            <th>UOM</th>
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
                                        <td>{{ $item['item_color'] ?? '' }}</td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ?? 0}}</td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ? ceil($item['wo_total_qty']) : 0}}</td>
                                        <td>{{ $item['uom'] ?? ''}}</td>
                                        @php
                                            $total += $item['amount'] ?? 0;
                                            $subTotal += $item['amount'] ?? 0;
                                            $subQty +=  $item['wo_total_qty'] ?? 0;
                                            $totalQty += $item['wo_total_qty'] ?? 0;
                                        @endphp
                                        <td>{{ $item['remarks'] ?? null }}</td>
                                    </tr>
                                @else
                                    <tr></tr>
                                @endif
                            @endforeach
                            <tr>
                                <th colspan="4" style="text-align: right">Sub Total</th>
                                <td class="text-right"><b>{{ $subQty }}</b></td>
                                <td class="text-right"><b>{{ null }}</b></td>
                                <td></td>
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
                            <td colspan="3">
                                Size Sensitivity(Unique Id):
                                {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}
                                Style:
                                {{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}
                                Po Qty:
                                {{  collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum() }}
                            </td>

                            <td colspan="7">
                                Po No:
                                {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}
                            </td>
                        </tr>
                        <tr>
                            {{--                            <th>SL</th>--}}
                            <th>Item</th>
                            <th>Item Description</th>
                            <th>Gmts. Color</th>
                            <th>Item Color</th>
                            <th>Item Sizes</th>
                            <th>Gmts Sizes</th>
                            <th>Ac. Qty</th>
                            <th>T. Qty</th>
                            <th>UoM</th>
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
                                        <td>{{ $item['item_color'] ?? '' }}</td>
                                        <td>{{ $item['item_size'] ?? '' }}</td>
                                        <td>{{ $item['size'] ?? '' }}</td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ?? 0}}</td>
                                        <td class="text-right">{{ $item['wo_total_qty'] ? ceil($item['wo_total_qty']) : 0}}</td>
                                        <td>{{ $item['uom'] ?? ''}}</td>
                                        @php
                                            $total += $item['amount'] ?? 0;
                                            $subTotal += $item['amount'] ?? 0;
                                            $subQty +=  $item['wo_total_qty'] ?? 0;
                                            $totalQty += $item['wo_total_qty'] ?? 0;
                                        @endphp
                                        <td>{{ $item['remarks'] ?? null }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <th colspan="6" style="text-align: right">Sub Total</th>
                                <td class="text-right"><b>{{ $subQty }}</b></td>
                                <td class="text-right"><b>{{ null }}</b></td>
                                <td></td>
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
                            @php $itemCount = count(collect($trimsDetails)->pluck('details')->whereNotIn('item_color',[null, " "])) @endphp
                            <td colspan="2">
                                Color & Size Sensitivity (Unique Id):
                                {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}
                                Style:{{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}
                                Po Qty:{{ collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum()  }}
                            </td>
                            <td colspan="7">
                                Po No:
                                {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}
                            </td>
                        </tr>
                        <tr>
                            {{--                            <th>SL</th>--}}
                            <th>Item</th>
                            <th>Item Description</th>
                            @if($itemCount > 0)
                                <th>Item Color</th>
                            @else
                                <th>Gmts. Color</th>
                            @endif
                            <th>Item Sizes</th>
                            <th>Gmts Sizes</th>
                            <th>Ac. Qty</th>
                            <th>T. Qty</th>
                            <th>UoM</th>
                            <th>Remarks</th>
                        </tr>
                        @php
                            $index = 0;
                            $total = 0;
                            $totalQty = 0;
                            $itemIndex = 1;
                        @endphp
                        @foreach($trimsDetails->groupBy('item_name') as $key => $bookingDetails)
                            @php
                                $index++;
                                $subTotal = 0;
                                $subQty = 0;
                                $uom = $bookingDetails->unique('cons_uom_value')->pluck('cons_uom_value')->implode(',');
                            @endphp
                            @foreach($bookingDetails->pluck('details')->groupBy('color') as $colorWise)
                                @foreach($colorWise->groupBy('item_size') as $sizeWise)
                                    @foreach($sizeWise->groupBy('size') as $gmtSizeWise)
                                        @foreach($gmtSizeWise->groupBy('item_color') as $item)
                                            @if($item)
                                                <tr>
                                                    {{--                                                    <td>{{ $itemIndex }}</td>--}}
                                                    <td>{{ $key }}</td>
                                                    <td>{{ $item->first()['item_description'] ?? '' }}</td>
                                                    @if($itemCount > 0)
                                                        <td>{{ $item->first()['item_color'] ?? '' }}</td>
                                                    @else
                                                        <td>{{ $item->first()['color'] ?? '' }}</td>
                                                    @endif
                                                    <td>{{ $item->first()['item_size'] ?? '' }}</td>
                                                    <td>{{ $item->first()['size'] ?? ''}}</td>
                                                    <td class="text-right">{{ $item->sum('wo_total_qty') ?? 0}}</td>
                                                    <td class="text-right">{{ $item->sum('wo_total_qty') ? ceil($item->sum('wo_total_qty')) : 0}}</td>
                                                    <td>{{ $item->first()['uom'] ?? ''}}</td>
                                                    @php
                                                        $total += $item->sum('amount') ?? 0;
                                                        $subTotal += $item->sum('amount') ?? 0;
                                                        $subQty +=  $item->sum('wo_total_qty') ?? 0;
                                                        $totalQty += $item->sum('wo_total_qty') ?? 0;
                                                    @endphp
                                                    <td>{{ $item->first()['remarks'] ?? null }}</td>
                                                </tr>
                                            @else
                                                <tr></tr>
                                            @endif
                                            @php
                                                $itemIndex++;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                            <tr>
                                <th colspan="5" style="text-align: right">Sub Total</th>
                                <td class="text-right"><b>{{ $subQty }}</b></td>
                                <td class="text-right"><b>{{ null }}</b></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endforeach

                </table>
            </div>
        @endif
        {{--        end color and size sensitivity--}}

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
            top: 170% !important;
        }
    </style>
</div>
