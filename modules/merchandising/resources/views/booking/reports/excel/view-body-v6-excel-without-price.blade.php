<div>
    <div class="body-section" style="margin-top: 0px;">
        <table>
            <tr>
                <td style="height: 32px" colspan="10">Factory Name: {{ factoryName() }}</td>
            </tr>
            <tr>
                <td style="height: 24px" colspan="10"><b>Trims Booking</b></td>
            </tr>
        </table>


        <table class="borderless">
            <tr>
                <td><b>TO</b></td>
            </tr>
            <tr>
                <th class="text-left"><b>Supplier Name</b></th>
                <td>{{ $trimsBookings ? optional($trimsBookings->supplier)->name : ''  }}</td>
                {{--                @if(request('type'))--}}
                {{--                    <th class="text-left"><b>Booking QTY:</b></th>--}}
                {{--                    <td>{{ round($totalQty) ?? '' }}</td>--}}
                {{--                @else--}}
                {{--                    <th class="text-left"><b>Booking Amount:</b></th>--}}
                {{--                    <td>{{ $totalAmount ?? '' }} {{ $trimsBookings->currency ?? '' }}</td>--}}
                {{--                @endif--}}
                <th></th>
                <td></td>
            </tr>

            <tr>
                <th class="text-left"><b>Address:</b></th>
                <td>{{ $trimsBookings->address ?? '' }} </td>
                <th class="text-left"><b>Season:</b></th>
                <td>{{ $trimsBookings->season ?? ''}}</td>
            </tr>

            <tr>
                <th class="text-left"><b>Attention:</b></th>
                <td>{{ $trimsBookings->attention ?? ''}}</td>
                <th class="text-left"><b>Delivery Date:</b></th>
                <td>{{  $trimsBookings->delivery_date ?? '' }}</td>
            </tr>

            <tr>
                <th class="text-left"><b>Dealing Merchant:</b></th>
                <td>{{ $trimsBookings->dealing_merchant ?? ''}}</td>
                <th class="text-left"><b>Remarks:</b></th>
                <td>{{ $trimsBookings->remarks ?? ''}}</td>
            </tr>

            <tr>
                <th class="text-left"><b>Delivery To:</b></th>
                <td>{{ $trimsBookings->delivery_to ?? ''}}</td>
            </tr>
        </table>


        @php
            $withoutSensitivity = collect($trimsBookingsDetailsWithoutSensitivity)->flatten(1);
        @endphp
        @if(count($withoutSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table style="border: 1px solid black;">
                    <tr style="border:none">
                        <td colspan="7" style="height: 40px">
                            <b> No Sensitivity(Unique Id)::</b>
                            {{ collect($withoutSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b>, Style: </b>
                            {{ collect($withoutSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            <b>, Po Qty:</b>
                            {{ collect($withoutSensitivity)->unique('total_qty')->pluck('total_qty')->sum() }} <br>
                            <b> Po No: </b>
                            {{ collect($withoutSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                        </td>
                    </tr>
                    <tr>
                        <th style="background-color: blanchedalmond;"><b>Item</b></th>
                        <th style="background-color: blanchedalmond;"><b>Item Description</b></th>
                        <th style="background-color: blanchedalmond;"><b>Item Color</b></th>
                        <th style="background-color: blanchedalmond;"><b>Ac. Qty</b></th>
                        <th style="background-color: blanchedalmond;"><b>T. Qty</b></th>
                        <th style="background-color: blanchedalmond;"><b>UOM</b></th>
                        <th style="background-color: blanchedalmond;"><b>Remarks</b></th>
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
                                            <td></td>
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
                            <th colspan="4">{{ collect($uomWiseData)->implode(", ") }}</th>
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
                <table style="border: 1px solid black;">
                    <tr style="border:none;">
                        <td colspan="7" style="height: 40px">
                            <b> Contrast Color(Unique Id)::</b>
                            {{ collect($contrastColor)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b>, Item Name: </b>
                            {{ collect($contrastColor)->pluck('item_subgroup_name')->unique()->implode(', ') }}
                            <b>, Style: </b>
                            {{ collect($contrastColor)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            <b>, Po Qty:</b>
                            {{ collect($contrastColor)->unique('total_qty')->pluck('total_qty')->sum() }} <br>
                            <b>Po No: </b>
                            {{ collect($contrastColor)->unique('po_no')->pluck('po_no')->join(', ') }}
                        </td>
                    </tr>
                    <tr style="background-color: blanchedalmond;">
                        @if(request('type')!='v5')
                            <th style="background-color: blanchedalmond;"><b>Item</b></th>
                        @endif
                        <th style="background-color: blanchedalmond;"><b>Item Description</b></th>
                        <th style="background-color: blanchedalmond;"><b>Item Color</b></th>
                        <th style="background-color: blanchedalmond;"><b>Ac. Qty</b></th>
                        <th style="background-color: blanchedalmond;"><b>T. Qty</b></th>
                        <th style="background-color: blanchedalmond;"><b>UOM</b></th>
                        <th style="background-color: blanchedalmond;"><b>Remarks</b></th>
                    </tr>
                    @if(request('type')=='v5')
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
                                            <td></td>
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
                                <th colspan="4">{{ collect($uomWiseData)->implode(', ') }}</th>
                            </tr>
                        @endforeach
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
                                                <td></td>
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
                                <th colspan="4">{{ collect($uomWiseData)->implode(", ") }}</th>
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
                <table style="border: 1px solid black;">
                    <tr style="border:none">
                        <td colspan="8">
                            <b> As per Gmts Color(Unique Id)::</b>
                            {{ collect($asPerGmtsColor)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b>, Style: </b>
                            {{ collect($asPerGmtsColor)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            <b>, Po Qty:</b>
                            {{ collect($asPerGmtsColor)->unique('total_qty')->pluck('total_qty')->sum() }}
                            <b> Po No: </b>
                            {{ collect($asPerGmtsColor)->unique('po_no')->pluck('po_no')->join(', ') }}
                        </td>
                    </tr>
                    <tr>
                        <th style="background-color: blanchedalmond">Item</th>
                        <th style="background-color: blanchedalmond">Item Description</th>
                        <th style="background-color: blanchedalmond">Gmts. Color</th>
                        <th style="background-color: blanchedalmond">Item Color</th>
                        <th style="background-color: blanchedalmond">Ac. Qty</th>
                        <th style="background-color: blanchedalmond">T. Qty</th>
                        <th style="background-color: blanchedalmond">UOM</th>
                        <th style="background-color: blanchedalmond">Remarks</th>
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
                                                <td></td>
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
                            <th colspan="4">{{ collect($uomWiseData)->implode(", ") }}</th>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif



        @php
            $sizeSensitivity = collect($trimsBookingsDetailsSizeSensitivity)->flatten(1);
            $sizes = collect($sizeSensitivity)->pluck('details.size')->unique();
        @endphp
        @if(count($sizeSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table style="border: 1px solid black;">
                    <tr style="border:none">
                        <td colspan="{{ count($sizes) + 5}}" style="height: 40px">
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
                        <th style="background-color: blanchedalmond;"><b>PO No</b></th>
                        <th style="background-color: blanchedalmond;"><b>Item</b></th>
                        <th style="background-color: blanchedalmond;"><b>Item Description</b></th>
                        @foreach($sizes as $sizeKey => $size)
                            <th style="background-color: blanchedalmond;"><b>{{ $size }}</b></th>
                        @endforeach
                        <th style="background-color: blanchedalmond;"><b>Total</b></th>
                        <th style="background-color: blanchedalmond;"><b>UoM</b></th>
                    </tr>
                    @php
                        $uomWiseDataArray = [];
                    @endphp
                    @foreach(collect($sizeSensitivity)->pluck('details')->groupBy('po_no') as $po => $poWise)
                        @foreach(collect($poWise)->groupBy('item_name') as $item => $itemWise)
                            @php
                                $totalUomWiseQty = 0;
                            @endphp
                            @foreach(collect($itemWise)->groupBy('uom') as $uom => $uomWise)
                                <tr>
                                    <td>{{ $po }}</td>
                                    <td>{{ $item }}</td>
                                    <td>{{ collect($itemWise)->whereNotNull('item_description')->first()['item_description'] ?? '' }}</td>
                                    @foreach($sizes as $sizeKey => $size)
                                        @php
                                            $woQty = collect($uomWise)->where('size', $size)->sum('wo_total_qty');
                                            $uomWiseDataArray[] = ['uom' => $uom, 'wo_qty' => round($woQty)];
                                            $totalUomWiseQty += round($woQty);
                                        @endphp
                                        <td>{{ round($woQty) }}</td>
                                    @endforeach
                                    <td>{{ $totalUomWiseQty }}</td>
                                    <td>{{ $uom }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                    <tr>
                        <th colspan="{{ count($sizes) + 3}}">Total</th>
                        @php
                            $uomWiseData = collect($uomWiseDataArray)->groupBy('uom')->map(function ($uomWiseItem, $uom){
                                                return (collect($uomWiseItem)->sum('wo_qty')) . ' ' . $uom;
                                           });

                        @endphp
                        <th colspan="2">{{ collect($uomWiseData)->implode(", ") }}</th>
                    </tr>
                </table>
            </div>
        @endif


        @php
            $colorAndSizeSensitivity = collect($trimsBookingsDetailsColorAndSizeSensitivity)->flatten(1);
            $sizes = collect($colorAndSizeSensitivity)->pluck('details.size')->unique();
        @endphp
        @if(count($colorAndSizeSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table style="border: 1px solid black;">
                    <tr style="border:none">
                        <td colspan="{{ count($sizes) + 5}}" style="height: 40px">
                            <b>Color and Size Sensitivity(Unique Id):</b><b> (Unique Id):</b>
                            {{ collect($colorAndSizeSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b> Style: </b>
                            {{ collect($colorAndSizeSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            <b> Po Qty:</b>
                            {{ collect($colorAndSizeSensitivity)->unique('total_qty')->pluck('total_qty')->sum() }}
                            <b> Po No: </b>
                            {{ collect($colorAndSizeSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                        </td>
                    </tr>
                    <tr>
                        <th style="background-color: blanchedalmond;"><b>PO No</b></th>
                        <th style="background-color: blanchedalmond;"><b>Item</b></th>
                        <th style="background-color: blanchedalmond;"><b>Item Description</b></th>
                        @foreach($sizes as $sizeKey => $size)
                            <th style="background-color: blanchedalmond;"><b>{{ $size }}</b></th>
                        @endforeach
                        <th style="background-color: blanchedalmond;"><b>Total</b></th>
                        <th style="background-color: blanchedalmond;"><b>UoM</b></th>
                    </tr>
                    @php
                        $uomWiseDataArray = [];
                    @endphp
                    @foreach(collect($colorAndSizeSensitivity)->pluck('details')->groupBy('po_no') as $po => $poWise)
                        @foreach(collect($poWise)->groupBy('item_name') as $item => $itemWise)
                            @php
                                $totalUomWiseQty = 0;
                            @endphp
                            @foreach(collect($itemWise)->groupBy('uom') as $uom => $uomWise)
                                <tr>
                                    <td>{{ $po }}</td>
                                    <td>{{ $item }}</td>
                                    <td>{{ collect($itemWise)->whereNotNull('item_description')->first()['item_description'] ?? '' }}</td>
                                    @foreach($sizes as $sizeKey => $size)
                                        @php
                                            $woQty = collect($uomWise)->where('size', $size)->sum('wo_total_qty');
                                            $uomWiseDataArray[] = ['uom' => $uom, 'wo_qty' => round($woQty)];
                                            $totalUomWiseQty += round($woQty);
                                        @endphp
                                        <td>{{  round($woQty) }}</td>
                                    @endforeach
                                    <td>{{ $totalUomWiseQty }}</td>
                                    <td>{{ $uom }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                    <tr>
                        <th colspan="{{ count($sizes) + 3}}">Total</th>
                        @php
                            $uomWiseData = collect($uomWiseDataArray)->groupBy('uom')->map(function ($uomWiseItem, $uom){
                                                return (collect($uomWiseItem)->sum('wo_qty')) . ' ' . $uom;
                                           });

                        @endphp
                        <th colspan="2">{{ collect($uomWiseData)->implode(", ") }}</th>
                    </tr>
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

</div>
