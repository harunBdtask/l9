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
                @if( (request('type') == 'v5')  && (request('pdfNumber') == '1'))
                    <th class="text-left">Booking Value:</th>
                    <td>{{ $totalAmount ?? '' }} {{ $trimsBookings->currency ?? '' }}</td>
                @else
                    <th></th>
                    <th></th>
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
                <td>{{ $trimsBookings->deliveryTo->factory_name ?? ''}}</td>
            </tr>

        </table>

        @php
            $withoutSensitivity = collect($trimsBookingsDetailsWithoutSensitivity)->flatten(1);
        @endphp

        @if(count($withoutSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="7">
                            <b> No Sensitivity(Unique Id)::</b>
                            {{ collect($withoutSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b> Style: </b>
                            {{ collect($withoutSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            <b> Po Qty:</b>
                            {{ collect($withoutSensitivity)->unique('total_qty')->pluck('total_qty')->sum() }} <br>
                            <b> Po No: </b>
                            {{ collect($withoutSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                        </td>
                    </tr>
                    <tr>
                        {{--                            <th>SL</th>--}}
                        <th>Item</th>
                        <th>Item Description</th>
                        <th>Item Color</th>
                        <th>Ac. Qty</th>
                        <th>T. Qty</th>
                        <th>UOM</th>
                        {{--                        <th style="width: 2%;">Rate</th>--}}
{{--                        <th style="width: 2%;">Amount</th>--}}
                        <th style="width: 2%;">Remarks</th>
                    </tr>
                    @foreach(collect($withoutSensitivity)->groupBy('item_name') as $item => $itemWise)
                        @foreach(collect($itemWise)->pluck('details')->groupBy('item_description') as $itemDescription => $itemDescriptionDetails)
                            @foreach(collect($itemDescriptionDetails)->groupBy('item_color') as $itemColor => $itemColorWise)
                                @foreach(collect($itemColorWise)->groupBy('uom') as $uom => $uomWise)
                                    @if(isset($uomWise) && count($uomWise) > 0 )
                                        <tr>
                                            <td>{{ $item }}</td>
                                            <td>{{ $itemDescription }}</td>
                                            <td>{{ $itemColor }}</td>
                                            <td class="text-right">{{ collect($uomWise)->sum('wo_total_qty')  }}</td>
                                            <td class="text-right">{{ round(collect($uomWise)->sum('wo_total_qty'))  }}</td>
                                            <td>{{ $uom }}</td>
{{--                                            <td align="right">{{ collect($uomWise)->sum('amount')  }}</td>--}}
                                            {{--                                                @if(!request('type'))--}}
                                            {{--                                                    <td class="text-right">{{ $item['rate'] ?? null }}</td>--}}
                                            {{--                                                @endif--}}
                                            <td>{{ $uomWise->first()['remarks'] ?? '' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                        <tr>
                            <th colspan="3">Total</th>
                            @php
                                $uomWiseData = collect($itemWise)->pluck('details')->groupBy('uom')->map(function ($uomWiseItem, $uom){
                                                    return round(collect($uomWiseItem)->sum('wo_total_qty')) . ' ' . $uom;
                                               });

                            @endphp
                            <th colspan="4" align="left">{{ collect($uomWiseData)->implode(", ") }}</th>
{{--                            <th align="rignt">{{ collect($itemWise)->pluck('details')->sum('amount') }}</th>--}}
{{--                            <td></td>--}}
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif


        @php
            $contrastColor = collect($trimsBookingsDetailsContrastColorSensitivity)->flatten(1);
        @endphp

        @if(count($contrastColor) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="9">
                            <b> Contrast Color(Unique Id)::</b>
                            {{ collect($contrastColor)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b>Item Name: </b>
                            {{ collect($contrastColor)->pluck('item_subgroup_name')->unique()->implode(', ') }}
                            <b> Style: </b>
                            {{ collect($contrastColor)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            <b> Po Qty:</b>
                            {{ collect($contrastColor)->unique('total_qty')->pluck('total_qty')->sum() }} <br>
                            <b> Po No: </b>
                            {{ collect($contrastColor)->unique('po_no')->pluck('po_no')->join(', ') }} <br>
                        </td>
                    </tr>
                    <tr>
                        {{--                            <th>SL</th>--}}
                        @if(request('type')!='v5')
                            <th>Item</th>
                        @endif
                        <th>Item Description</th>
                        <th>Item Color</th>
                        <th>Ac. Qty</th>
                        <th>T. Qty</th>
                        <th>UOM</th>
                        {{--                        <th style="width: 2%;">Rate</th>--}}
{{--                        <th style="width: 2%;">Amount</th>--}}
                        <th style="width: 2%;">Remarks</th>
                    </tr>
                    @if(request('type')=='v5')
                        {{--                        @foreach(collect($contrastColor)->groupBy('item_name') as $item => $itemWise)--}}
                        @foreach(collect($contrastColor)->pluck('details')->groupBy('item_description') as $itemDescription => $itemDescriptionDetails)
                            @foreach(collect($itemDescriptionDetails)->groupBy('item_color') as $itemColor => $itemColorWise)
                                @foreach(collect($itemColorWise)->groupBy('uom') as $uom => $uomWise)
                                    @if(isset($uomWise) && count($uomWise) > 0 )
                                        <tr>
                                            <td>{{ $itemDescription }}</td>
                                            <td>{{ $itemColor }}</td>
                                            <td>{{ collect($uomWise)->sum('wo_total_qty')  }}</td>
                                            <td>{{ round(collect($uomWise)->sum('wo_total_qty'))  }}</td>
                                            <td>{{ $uom }}</td>
{{--                                            <td align="right">{{ collect($uomWise)->sum('amount')  }}</td>--}}
                                            {{--                                                @if(!request('type'))--}}
                                            {{--                                                    <td class="text-right">{{ $item['rate'] ?? null }}</td>--}}
                                            {{--                                                @endif--}}
                                            <td>{{ $uomWise->first()['remarks'] ?? '' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            <tr>
                                <th colspan="2">Total</th>
                                @php
                                    $uomWiseData = collect($itemDescriptionDetails)
                                        ->groupBy('uom')
                                        ->map(function ($uomDetails, $uom){
                                           return round(collect($uomDetails)->sum('wo_total_qty')).' '.$uom;
                                        });
                                @endphp
                                <th colspan="4" align="left">{{ collect($uomWiseData)->implode(', ') }}</th>
{{--                                <th  align="right">{{ collect($itemWise)->pluck('details')->sum('amount') }}</th>--}}
{{--                                <td></td>--}}
                            </tr>
                        @endforeach
                        {{--                        @endforeach--}}
                    @elseif(request('type')=='v4')
                        @foreach(collect($contrastColor)->groupBy('item_name') as $item => $itemWise)
                            @foreach(collect($itemWise)->pluck('details')->groupBy('item_description') as $itemDescription => $itemDescriptionDetails)
                                @foreach(collect($itemDescriptionDetails)->groupBy('item_color') as $itemColor => $itemColorWise)
                                    @foreach(collect($itemColorWise)->groupBy('uom') as $uom => $uomWise)
                                        @if(isset($uomWise) && count($uomWise) > 0 )
                                            <tr>
                                                <td>{{ $item }}</td>
                                                <td>{{ $itemDescription }}</td>
                                                <td>{{ $itemColor }}</td>
                                                <td>{{ collect($uomWise)->sum('wo_total_qty')  }}</td>
                                                <td>{{ round(collect($uomWise)->sum('wo_total_qty'))  }}</td>
                                                <td>{{ $uom }}</td>
{{--                                                <td align="right">{{ collect($uomWise)->sum('amount')  }}</td>--}}
                                                {{--                                                @if(!request('type'))--}}
                                                {{--                                                    <td class="text-right">{{ $item['rate'] ?? null }}</td>--}}
                                                {{--                                                @endif--}}
                                                <td>{{ $uomWise->first()['remarks'] ?? '' }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                            <tr>
                                <th colspan="3">Total</th>
                                @php
                                    $uomWiseData = collect($itemWise)->pluck('details')->groupBy('uom')->map(function ($uomWiseItem, $uom){
                                                        return round(collect($uomWiseItem)->sum('wo_total_qty')) . ' ' . $uom;
                                                   });

                                @endphp
                                <th colspan="4" align="left">{{ collect($uomWiseData)->implode(", ") }}</th>
{{--                                <th  align="right">{{ collect($itemWise)->pluck('details')->sum('amount') }}</th>--}}
{{--                                <td></td>--}}
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        @endif

        @php
            $asPerGmtsColor = collect($trimsBookingsDetailsAsPerGmtsColor)->flatten(1);
        @endphp

        @if(count($asPerGmtsColor) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="8">
                            <b> As per Gmts Color(Unique Id)::</b>
                            {{ collect($asPerGmtsColor)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b> Style: </b>
                            {{ collect($asPerGmtsColor)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            <b> Po Qty:</b>
                            {{ collect($asPerGmtsColor)->unique('total_qty')->pluck('total_qty')->sum() }} <br>
                            <b> Po No: </b>
                            {{ collect($asPerGmtsColor)->unique('po_no')->pluck('po_no')->join(', ') }}
                        </td>
                    </tr>
                    <tr>
                        {{--                            <th>SL</th>--}}
                        <th>Item</th>
                        <th style="width: 50%;">Item Description</th>
                        <th>Gmts. Color</th>
                        <th>Item Color</th>
                        <th>Ac. Qty</th>
                        <th>T. Qty</th>
                        <th>UOM</th>
{{--                        <th>Amount</th>--}}
                        <th>Remarks</th>
                    </tr>
                    @foreach(collect($asPerGmtsColor)->groupBy('item_name') as $item => $itemWise)
                        @foreach(collect($itemWise)->pluck('details')->groupBy('item_description') as $itemDescription => $itemDescriptionDetails)
                            @foreach(collect($itemDescriptionDetails)->groupBy('color') as $gmtsColor => $gmtsColorWise)
                                @foreach(collect($gmtsColorWise)->groupBy('item_color') as $itemColor => $itemColorWise)
                                    @foreach(collect($itemColorWise)->groupBy('uom') as $uom => $uomWise)
                                        @if(isset($uomWise) && count($uomWise) > 0 )
                                            <tr>
                                                <td>{{ $item }}</td>
                                                <td>{{ $itemDescription }}</td>
                                                <td>{{ $gmtsColor }}</td>
                                                <td>{{ $itemColor }}</td>
                                                <td class="text-right">{{ collect($uomWise)->sum('wo_total_qty')  }}</td>
                                                <td class="text-right">{{ round(collect($uomWise)->sum('wo_total_qty'))  }}</td>
                                                <td>{{ $uom }}</td>
{{--                                                <td align="right">{{ collect($uomWise)->sum('amount')  }}</td>--}}
                                                {{--                                                @if(!request('type'))--}}
                                                {{--                                                    <td class="text-right">{{ $item['rate'] ?? null }}</td>--}}
                                                {{--                                                @endif--}}
                                                <td>{{ $uomWise->first()['remarks'] ?? '' }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                        <tr>
                            <th colspan="4">Total</th>
                            @php
                                $uomWiseData = collect($itemWise)->pluck('details')->groupBy('uom')->map(function ($uomWiseItem, $uom){
                                                    return round(collect($uomWiseItem)->sum('wo_total_qty')) . ' ' . $uom;
                                               });

                            @endphp
                            <th colspan="4" align="left">{{ collect($uomWiseData)->implode(", ") }}</th>
{{--                            <th align="right">{{ collect($itemWise)->pluck('details')->sum('amount') }}</th>--}}
{{--                            <td></td>--}}
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif


        @php

            $sizeSensitivity = collect($trimsBookingsDetailsSizeSensitivity)->flatten(1);
        @endphp

        @if(count($sizeSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="8">
                            <b>Size Sensitivity(Unique Id):</b>
                            <b> (Unique Id):</b>
                            {{ collect($sizeSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b> Style: </b>
                            {{ collect($sizeSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            <b> Po Qty:</b>
                            {{ collect($sizeSensitivity)->unique('total_qty')->pluck('total_qty')->sum() }} <br>
                            <b> Po No: </b>
                            {{ collect($sizeSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                        </td>

                    </tr>
                    <tr>
                        <th>Item</th>
                        <th>Item Description</th>
                        <th>Item Sizes</th>
                        <th>Gmts Sizes</th>
                        <th>Ac. Qty</th>
                        <th>T. Qty</th>
                        <th>UoM</th>
{{--                        <th>Amount</th>--}}
                        <th>Remarks</th>
                    </tr>
                    @foreach(collect($sizeSensitivity)->groupBy('item_name') as $item => $itemWise)
                        @foreach(collect($itemWise)->pluck('details')->groupBy('item_description') as $itemDescription => $itemDescriptionDetails)
                            @foreach(collect($itemDescriptionDetails)->groupBy('item_size') as $itemSize => $itemSizeWise )
                                @foreach(collect($itemSizeWise)->groupBy('size') as $gmtsSize => $gmtsSizeWise)
                                    @foreach(collect($gmtsSizeWise)->groupBy('uom') as $uom => $uomWise)
                                        @if(isset($uomWise) && count($uomWise) > 0)
                                            <tr>
                                                <td>{{ $item }}</td>
                                                <td>{{ $itemDescription }}</td>
                                                <td>{{ $itemSize }}</td>
                                                <td>{{ $gmtsSize }}</td>
                                                <td class="text-right">{{ collect($uomWise)->sum('wo_total_qty') }}</td>
                                                <td class="text-right">{{ round(collect($uomWise)->sum('wo_total_qty')) }}</td>
                                                <td>{{ $uom }}</td>
{{--                                                <td align="right">{{ collect($uomWise)->sum('amount')  }}</td>--}}
                                                <td>{{ $uomWise->first()['remarks'] ?? '' }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                        <tr>
                            <th colspan="4">Total</th>
                            @php
                                $uomWiseData = collect($itemWise)->pluck('details')->groupBy('uom')->map(function ($uomWiseItem, $uom){
                                                    return round(collect($uomWiseItem)->sum('wo_total_qty')) . ' ' . $uom;
                                               });

                            @endphp
                            <th colspan="4" align="left">{{ collect($uomWiseData)->implode(", ") }}</th>
{{--                            <th align="right">{{ collect($itemWise)->pluck('details')->sum('amount') }}</th>--}}
{{--                            <td></td>--}}
                        </tr>
                    @endforeach

                </table>
            </div>
        @endif
        @php
            $colorAndSizeSensitivity = collect($trimsBookingsDetailsColorAndSizeSensitivity)->flatten(1);
        @endphp

        @if(count($colorAndSizeSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="10">
                            <b>Color & Size Sensitivity(Unique Id):</b>
                            <b> (Unique Id):</b>
                            {{ collect($colorAndSizeSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b> Style: </b>
                            {{ collect($colorAndSizeSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            <b> Po Qty:</b>
                            {{ collect($colorAndSizeSensitivity)->unique('total_qty')->pluck('total_qty')->sum() }} <br>
                            @if (request('type') != 'v6')
                                <b> Po No: </b>
                                {{ collect($colorAndSizeSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                            @endif
                        </td>

                    </tr>
                    <tr>
                        @if (request('type') == 'v6')
                            <th>Po No</th>
                        @endif
                        <th>Item</th>
                        <th>Item Description</th>
                        <th>Gmts. Color</th>
                        <th>Item Color</th>
                        <th>Item Sizes</th>
                        <th>Gmts Sizes</th>
                        <th>Ac. Qty</th>
                        <th>T. Qty</th>
                        <th>UoM</th>
{{--                        <th>Amount</th>--}}
                        <th>Remarks</th>
                    </tr>
                    @foreach(collect($colorAndSizeSensitivity)->groupBy('item_name') as $item => $itemWise)
                        @foreach(collect($itemWise)->pluck('details')->groupBy('item_description') as $itemDescription => $itemDescriptionDetails)
                            @foreach(collect($itemDescriptionDetails)->groupBy('color') as $gmtsColor => $gmtsColorWise)
                                @foreach(collect($gmtsColorWise)->groupBy('item_color') as $itemColor => $itemColorWise)
                                    @foreach(collect($itemColorWise)->groupBy('item_size') as $itemSize => $itemSizeWise )
                                        @foreach(collect($itemSizeWise)->groupBy('size') as $gmtsSize => $gmtsSizeWise)
                                            @foreach(collect($gmtsSizeWise)->groupBy('uom') as $uom => $uomWise)
                                                @if(isset($uomWise) && count($uomWise) > 0)
                                                    <tr>
                                                        @if (request('type') == 'v6')
                                                            <td>{{ collect($uomWise)->pluck('po_no')->join(', ') }}</td>
                                                        @endif
                                                        <td>{{ $item }}</td>
                                                        <td>{{ $itemDescription }}</td>
                                                        <td>{{ $gmtsColor }}</td>
                                                        <td>{{ $itemColor }}</td>
                                                        <td>{{ $itemSize }}</td>
                                                        <td>{{ $gmtsSize }}</td>
                                                        <td class="text-right">{{ collect($uomWise)->sum('wo_total_qty') }}</td>
                                                        <td class="text-right">{{ round(collect($uomWise)->sum('wo_total_qty')) }}</td>
                                                        <td>{{ $uom }}</td>
{{--                                                        <td align="right">{{ collect($uomWise)->sum('amount')  }}</td>--}}
                                                        <td>{{ $uomWise->first()['remarks'] ?? '' }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                        <tr>
                            <th colspan="{{ request('type') == 'v6' ? '7' : '6' }}">Total</th>
                            @php
                                $uomWiseData = collect($itemWise)->pluck('details')->groupBy('uom')->map(function ($uomWiseItem, $uom){
                                                    return round(collect($uomWiseItem)->sum('wo_total_qty')) . ' ' . $uom;
                                               });

                            @endphp
                            <th colspan="4" align="left">{{ collect($uomWiseData)->implode(", ") }}</th>
{{--                            <th align="right">{{ collect($itemWise)->pluck('details')->sum('amount') }}</th>--}}
{{--                            <td></td>--}}
                        </tr>
                    @endforeach

                </table>
            </div>
        @endif


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
            top: 170% !important;
        }
    </style>
</div>
