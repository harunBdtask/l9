<style>
    .signature {
        left: 0;
        bottom: 0;
        height: 30px;
        width: 100%;
    }
</style>
<div>

    <div class="body-section" style="margin-top: 0px;">

        <table class="borderless">
            <thead>
            <tr>
                <td class="text-left">
                    @if(factoryImage() && File::exists('storage/factory_image/'.factoryImage()))
                        <img
                            src="{{ asset('storage/factory_image/'. factoryImage()) }}"
                            alt="Logo" style="min-width:100px;max-width:200px;max-height:200px;">
                    @else
                        <img src="{{ asset('images/no_image.jpg') }}" width="100"
                             alt="no image">
                    @endif
                </td>
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
        <hr style="border-top: 1px solid rgb(6 6 6);">
        <span class="text-left" style="font-weight: bold;">Trims Bookings Sheet</span>
        <hr style="border-top: 1px solid rgb(6 6 6);">
        <hr style="border-top: 1px solid rgb(6 6 6);">
        <br>

        @php
            $withoutSensitivity = collect($trimsBookingsDetailsWithoutSensitivity)->flatten(1);
            $contrastColor = collect($trimsBookingsDetailsContrastColorSensitivity)->flatten(1);
            $asPerGmtsColor = collect($trimsBookingsDetailsAsPerGmtsColor)->flatten(1);
            $sizeSensitivity = collect($trimsBookingsDetailsSizeSensitivity)->flatten(1);
            $colorAndSizeSensitivity = collect($trimsBookingsDetailsColorAndSizeSensitivity)->flatten(1);
        @endphp
        <table class="borderless">
            <tr>
                <th class="text-left">Supplier Name:</th>
                <td>{{ $trimsBookings ? optional($trimsBookings->supplier)->name : ''  }}</td>
                <th class="text-left">Buyer:</th>
                <td>{{ $trimsBookings->buyer->name ?? ''}}</td>
            </tr>

            <tr>
                <th class="text-left">Style:</th>
                <td>{{ collect($trimsBookings->bookingDetails)->flatten(1)->unique('style_name')->pluck('style_name')->join(', ') }}</td>
                <th class="text-left">Booking No:</th>
                <td>{{ $trimsBookings->bookingDetails->first()->budget->order->reference_no ?? ''}}</td>
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
                <th class="text-left">Order Qty:</th>
                <td>{{ $trimsBookings->bookingDetails->first()->budget->job_qty ?? 0 }}</td>
                <th class="text-left">Delivery To:</th>
                <td>{{ $trimsBookings->deliveryTo->factory_name ?? ''}}</td>
            </tr>
            <tr>
                <th colspan="4">&nbsp;</th>
            </tr>
            <tr>
                <th class="text-left">Subject:</th>
                @if(count($withoutSensitivity) > 0)
                    <th colspan="3">ORDER FOR
                        <u>{{ collect($withoutSensitivity)->pluck('item_name')->unique()->join(', ') }}</u>
                        AGAINST BUYER {{ $trimsBookings->buyer->name ?? '' }}</th>
                @endif
                @if(count($contrastColor) > 0)
                    <th colspan="3">ORDER FOR
                        <u>{{ collect($contrastColor)->pluck('item_name')->unique()->join(', ') }}</u>
                        AGAINST BUYER {{ $trimsBookings->buyer->name ?? '' }}</th>
                @endif
                @if(count($asPerGmtsColor) > 0)
                    <th colspan="3">ORDER FOR
                        <u>{{ collect($asPerGmtsColor)->pluck('item_name')->unique()->join(', ') }}</u>
                        AGAINST BUYER {{ $trimsBookings->buyer->name ?? '' }}</th>
                @endif
                @if(count($sizeSensitivity) > 0)
                    <th colspan="3">ORDER FOR
                        <u>{{ collect($sizeSensitivity)->pluck('item_name')->unique()->join(', ') }}</u>
                        AGAINST BUYER {{ $trimsBookings->buyer->name ?? '' }}</th>
                @endif
                @if(count($colorAndSizeSensitivity) > 0)
                    <th colspan="3">ORDER FOR
                        <u>{{ collect($colorAndSizeSensitivity)->pluck('item_name')->unique()->join(', ') }}</u>
                        AGAINST BUYER {{ $trimsBookings->buyer->name ?? '' }}</th>
                @endif
            </tr>
        </table>

        <table>
            @php
                $mtrPerCone = collect($withoutSensitivity)->whereNotNull('mtr_per_gmts')->pluck('mtr_per_gmts')->count();
            @endphp
            @if($mtrPerCone > 0)
                @foreach(collect($withoutSensitivity)->pluck('item_name')->unique() as $item)
                    <tr>
                        <th>Per Body</th>
                        <th>{{ $item }}</th>
                        <th>{{ collect($asPerGmtsColor)->where('item_name', $item)->first()['mtr_per_gmts'] }}</th>
                    </tr>
                @endforeach
            @endif
            @php
                $mtrPerCone = collect($contrastColor)->whereNotNull('mtr_per_gmts')->pluck('mtr_per_gmts')->count();
            @endphp
            @if($mtrPerCone > 0)
                @foreach(collect($contrastColor)->pluck('item_name')->unique() as $item)
                    <tr>
                        <th>Per Body</th>
                        <th>{{ $item }}</th>
                        <th>{{ collect($contrastColor)->where('item_name', $item)->first()['mtr_per_gmts'] }}</th>
                    </tr>
                @endforeach
            @endif
            @php
                $mtrPerCone = collect($asPerGmtsColor)->whereNotNull('mtr_per_gmts')->pluck('mtr_per_gmts');
            @endphp
            @if($mtrPerCone->count() > 0)
                @foreach(collect($asPerGmtsColor)->pluck('item_name')->unique() as $item)
                    <tr>
                        <th>Per Body</th>
                        <th>{{ $item }}</th>
                        <th>{{ collect($asPerGmtsColor)->where('item_name', $item)->first()['mtr_per_gmts'] }}</th>
                    </tr>
                @endforeach
            @endif
            @php
                $mtrPerCone = collect($sizeSensitivity)->whereNotNull('mtr_per_gmts')->pluck('mtr_per_gmts')->count();
            @endphp
            @if($mtrPerCone > 0)
                @foreach(collect($sizeSensitivity)->pluck('item_name')->unique() as $item)
                    <tr>
                        <th>Per Body</th>
                        <th>{{ $item }}</th>
                        <th>{{ collect($sizeSensitivity)->where('item_name', $item)->first()['mtr_per_gmts'] }}</th>
                    </tr>
                @endforeach
            @endif
            @php
                $mtrPerCone = collect($colorAndSizeSensitivity)->whereNotNull('mtr_per_gmts')->pluck('mtr_per_gmts')->count();
            @endphp
            @if($mtrPerCone > 0)
                @foreach(collect($colorAndSizeSensitivity)->pluck('item_name')->unique() as $item)
                    <tr>
                        <th>Per Body</th>
                        <th>{{ $item }}</th>
                        <th>{{ collect($colorAndSizeSensitivity)->where('item_name', $item)->first()['mtr_per_gmts'] }}</th>
                    </tr>
                @endforeach
            @endif
        </table>

        <hr style="border-top: 1px solid rgb(6 6 6);">

        @php

            $withoutSensitivity = collect($trimsBookingsDetailsWithoutSensitivity)->flatten(1);
            $extra_fields_key = collect($withoutSensitivity)->pluck('extra_field_keys')->flatten(1)->unique()->values()->toArray() ?? [];

        @endphp

        @if(count($withoutSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 16 + ($trimsBookings->level == 2 ? 1 : 0) }}">
                            <b> No Sensitivity(Unique Id)::</b>
                            {{ collect($withoutSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b> Style: </b>
                            {{ collect($withoutSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($withoutSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th>Gmts Item</th>
                        <th>Item Name</th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th>Body Part</th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th>Item Color</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th>PANTONE / CODE</th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th>Item Size</th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th>CARE LABEL TYPE</th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th>AGE GROUP</th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th>Description</th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th>Computer Ref</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th>FACTORY REF NO</th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>PRODUCTION BATCH</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th>LENGTH (inch)</th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th>WIDTH (inch)</th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th>LENGTH (CM)</th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th>Width (CM)</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CARD COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th>PLASTER FASTENER & ADJUSTABLE STRAPS QUALITY</th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th>QUALITY</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>FIBER COMPOSITION</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>
                                CARE SYMBOL
                            </th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>CARE INSTRUCTION</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>
                                SWATCH
                            </th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>
                                POLY BAG ART WORK
                            </th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th>Item Sizes</th>
                        @endif
                        <th>Order Qty</th>
                        <th>UOM</th>
                        <th>Attachment</th>
                        @if(!request('pdf-type'))
                            <th>Rate</th>
                            <th>BD TAKA</th>
                            <th>Amount</th>
                        @endif
                        <th>Remarks</th>
                    </tr>
                    @php
                        $totalAmount = 0;
                        $totalBdTaka = 0;
                        $totalOrderQty = 0;
                        $avgRate = collect($withoutSensitivity)->pluck('details')->avg('rate');
                    @endphp
                    @foreach(collect($withoutSensitivity)->groupBy('item_name') as $item => $itemWise)
                        @php
                            $subTotalAmount = 0;
                            $subTotalBdTaka = 0;
                            $subTotalOrderQty = 0;
                            $subAvgRate = collect($itemWise)->pluck('details')->avg('rate');
                        @endphp
                        @foreach(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name') as $index => $data)
                            @if($index === 0)
                                @php
                                    $count = collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count();
                                @endphp
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    <td rowspan="{{ $count }}">{{ $data['gmts_item_name'] ?? '' }}</td>
                                    <td rowspan="{{ $count }}">{{ $item }}</td>
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt=""
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @else
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt=""
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @endif
                            @php
                                $totalAmount += $data['amount'] ?? 0;
                                $totalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $totalOrderQty += $data['wo_order_qty'] ?? 0;

                                $subTotalAmount += $data['amount'] ?? 0;
                                $subTotalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $subTotalOrderQty += $data['wo_order_qty'] ?? 0;
                            @endphp
                        @endforeach
                        @if(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count() > 0)
                            <tr>
                                {{--                        <th>Style</th>--}}
                                <th></th>
                                <th></th>
                                @if(in_array('body_part', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('pantone_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_label_type', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('size_range', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_description', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('factory_ref_no', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('production_batch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('team_id', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fold_over', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zip_puller_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_tape_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_instruction', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_bag_art_work', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_sizes', $extra_fields_key))
                                    <th></th>
                                @endif
                                <th>{{ number_format(round($subTotalOrderQty), 2) }}</th>
                                <th></th>
                                <th></th>
                                @if(!request('pdf-type'))
                                    <th>{{ number_format($subAvgRate, 2) }}</th>
                                    <th>{{ number_format($subTotalBdTaka, 2) }}</th>
                                    <th>{{ number_format($subTotalAmount, 2) }}</th>
                                @endif
                                <th></th>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th></th>
                        <th></th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th></th>
                        @endif
                        <th>{{ number_format(round($totalOrderQty), 2) }}</th>
                        <th></th>
                        <th></th>
                        @if(!request('pdf-type'))
                            <th>{{ number_format($avgRate, 2) }}</th>
                            <th>{{ number_format($totalBdTaka, 2) }}</th>
                            <th>{{ number_format($totalAmount, 2) }}</th>
                        @endif
                        <th></th>
                    </tr>
                </table>
            </div>
        @endif


        @php
            $contrastColor = collect($trimsBookingsDetailsContrastColorSensitivity)->flatten(1);
            $extra_fields_key = collect($contrastColor)->pluck('extra_field_keys')->flatten(1)->unique()->values()->toArray() ?? [];
        @endphp

        @if(count($contrastColor) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 16  + ($trimsBookings->level == 2 ? 1 : 0)}}">
                            <b> Contrast Color(Unique Id)::</b>
                            {{ collect($contrastColor)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b>Item Name: </b>
                            {{ collect($contrastColor)->pluck('item_subgroup_name')->unique()->implode(', ') }}
                            <b> Style: </b>
                            {{ collect($contrastColor)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($contrastColor)->unique('po_no')->pluck('po_no')->join(', ') }} <br>
                            @endif

                        </td>
                    </tr>
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th>Gmts Item</th>
                        <th>Item Name</th>
                        <th>Gmts Color</th>
                        <th>Gmts Size</th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th>Body Part</th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th>Item Color</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th>PANTONE / CODE</th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th>Item Size</th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th>CARE LABEL TYPE</th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th>AGE GROUP</th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th>Description</th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th>Computer Ref</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th>FACTORY REF NO</th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>PRODUCTION BATCH</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th>LENGTH (inch)</th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th>WIDTH (inch)</th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th>LENGTH (CM)</th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th>Width (CM)</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CARD COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th>PLASTER FASTENER & ADJUSTABLE STRAPS QUALITY</th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th>QUALITY</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>FIBER COMPOSITION</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>
                                CARE SYMBOL
                            </th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>CARE INSTRUCTION</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>
                                SWATCH
                            </th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>
                                POLY BAG ART WORK
                            </th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th>Item Sizes</th>
                        @endif
                        <th>Order Qty</th>
                        <th>UOM</th>
                        <th>Attachment</th>
                        @if(!request('pdf-type'))
                            <th>Rate</th>
                            <th>BD TAKA</th>
                            <th>Amount</th>
                        @endif
                        <th>Remarks</th>
                    </tr>
                    @php
                        $totalAmount = 0;
                        $totalBdTaka = 0;
                        $totalOrderQty = 0;
                        $avgRate = collect($contrastColor)->pluck('details')->avg('rate');
                    @endphp
                    @foreach(collect($contrastColor)->groupBy('item_name') as $item => $itemWise)
                        @php
                            $subTotalAmount = 0;
                            $subTotalBdTaka = 0;
                            $subTotalOrderQty = 0;
                            $subAvgRate = collect($itemWise)->pluck('details')->avg('rate');
                        @endphp
                        @foreach(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name') as $index => $data)
                            @if($index === 0)
                                @php
                                    $count = collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count();
                                @endphp
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    <td rowspan="{{ $count }}">{{ $data['gmts_item_name'] ?? '' }}</td>
                                    <td rowspan="{{ $count }}">{{ $item }}</td>
                                    <td>{{ $data['color'] ?? '' }}</td>
                                    <td>{{ $data['size'] ?? '' }}</td>
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type']
                                        ? \SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking::CARE_LABELS[$data['care_label_type']]
                                        : '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt=""
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @else
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    <td>{{ $data['color'] ?? '' }}</td>
                                    <td>{{ $data['size'] ?? '' }}</td>
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type']
                                        ? \SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking::CARE_LABELS[$data['care_label_type']]
                                        : '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt=""
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @endif
                            @php
                                $totalAmount += $data['amount'] ?? 0;
                                $totalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $totalOrderQty += $data['wo_order_qty'] ?? 0;

                                $subTotalAmount += $data['amount'] ?? 0;
                                $subTotalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $subTotalOrderQty += $data['wo_order_qty'] ?? 0;
                            @endphp
                        @endforeach
                        @if(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count() > 0)
                            <tr>
                                {{--                        <th>Style</th>--}}
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                @if(in_array('body_part', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('pantone_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_label_type', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('size_range', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_description', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('factory_ref_no', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('production_batch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('team_id', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fold_over', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zip_puller_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_tape_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_instruction', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_bag_art_work', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_sizes', $extra_fields_key))
                                    <th></th>
                                @endif
                                <th>{{ number_format(round($subTotalOrderQty), 2) }}</th>
                                <th></th>
                                <th></th>
                                @if(!request('pdf-type'))
                                    <th>{{ number_format($subAvgRate, 2) }}</th>
                                    <th>{{ number_format($subTotalBdTaka, 2) }}</th>
                                    <th>{{ number_format($subTotalAmount, 2) }}</th>
                                @endif
                                <th></th>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th></th>
                        @endif
                        <th>{{ number_format(round($totalOrderQty), 2) }}</th>
                        <th></th>
                        <th></th>
                        @if(!request('pdf-type'))
                            <th>{{ number_format($avgRate, 2) }}</th>
                            <th>{{ number_format($totalBdTaka, 2) }}</th>
                            <th>{{ number_format($totalAmount, 2) }}</th>
                        @endif
                        <th></th>
                    </tr>
                </table>
            </div>
        @endif


        @php
            $asPerGmtsColor = collect($trimsBookingsDetailsAsPerGmtsColor)->flatten(1);
            $extra_fields_key = collect($asPerGmtsColor)->pluck('extra_field_keys')->flatten(1)->unique()->values()->toArray() ?? [];
        @endphp

        @if(count($asPerGmtsColor) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 16 + ($trimsBookings->level == 2 ? 1 : 0)}}">
                            <b> As per Gmts Color(Unique Id)::</b>
                            {{ collect($asPerGmtsColor)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b> Style: </b>
                            {{ collect($asPerGmtsColor)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($asPerGmtsColor)->unique('po_no')->pluck('po_no')->join(', ') }}
                            @endif

                        </td>
                    </tr>
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th>Gmts Item</th>
                        <th>Item Name</th>
                        <th>Gmts Color</th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th>Body Part</th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th>Item Color</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th>PANTONE / CODE</th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th>Item Size</th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th>CARE LABEL TYPE</th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th>AGE GROUP</th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th>Description</th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th>Computer Ref</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th>FACTORY REF NO</th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>PRODUCTION BATCH</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th>LENGTH (inch)</th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th>WIDTH (inch)</th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th>LENGTH (CM)</th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th>Width (CM)</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CARD COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th>PLASTER FASTENER & ADJUSTABLE STRAPS QUALITY</th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th>QUALITY</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>FIBER COMPOSITION</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>
                                CARE SYMBOL
                            </th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>CARE INSTRUCTION</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>
                                SWATCH
                            </th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>
                                POLY BAG ART WORK
                            </th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th>
                                Item Sizes
                            </th>
                        @endif
                        <th>Order Qty</th>
                        <th>UOM</th>
                        <th>Attachment</th>
                        @if(!request('pdf-type'))
                            <th>Rate</th>
                            <th>BD TAKA</th>
                            <th>Amount</th>
                        @endif
                        <th>Remarks</th>
                    </tr>
                    @php
                        $totalAmount = 0;
                        $totalBdTaka = 0;
                        $totalOrderQty = 0;
                        $avgRate = collect($asPerGmtsColor)->pluck('details')->avg('rate');
                    @endphp
                    @foreach(collect($asPerGmtsColor)->groupBy('item_name') as $item => $itemWise)
                        @php
                            $subTotalAmount = 0;
                            $subTotalBdTaka = 0;
                            $subTotalOrderQty = 0;
                            $subAvgRate = collect($itemWise)->pluck('details')->avg('rate');
                        @endphp
                        @foreach(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name') as $index => $data)
                            @if($index === 0)
                                @php
                                    $count = collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count();
                                @endphp
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    <td rowspan="{{ $count }}">{{ $data['gmts_item_name'] ?? '' }}</td>
                                    <td rowspan="{{ $count }}">{{ $item }}</td>
                                    <td>{{ $data['color'] ?? '' }}</td>
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type']
                                        ? \SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking::CARE_LABELS[$data['care_label_type']]
                                        : '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt="No Image Found"
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @else
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    <td>{{ $data['color'] ?? '' }}</td>
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type']
                                        ? \SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking::CARE_LABELS[$data['care_label_type']]
                                        : '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt="No Image Found"
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @endif
                            @php
                                $totalAmount += $data['amount'] ?? 0;
                                $totalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $totalOrderQty += $data['wo_order_qty'] ?? 0;

                                $subTotalAmount += $data['amount'] ?? 0;
                                $subTotalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $subTotalOrderQty += $data['wo_order_qty'] ?? 0;
                            @endphp
                        @endforeach
                        @if(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count() > 0)
                            <tr>
                                {{--                        <th>Style</th>--}}
                                <th></th>
                                <th></th>
                                <th></th>
                                @if(in_array('body_part', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('pantone_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_label_type', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('size_range', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_description', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('factory_ref_no', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('production_batch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('team_id', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fold_over', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zip_puller_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_tape_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_instruction', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_bag_art_work', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_sizes', $extra_fields_key))
                                    <th></th>
                                @endif
                                <th>{{ number_format(round($subTotalOrderQty), 2) }}</th>
                                <th></th>
                                <th></th>
                                @if(!request('pdf-type'))
                                    <th>{{ number_format($subAvgRate, 2) }}</th>
                                    <th>{{ number_format($subTotalBdTaka, 2) }}</th>
                                    <th>{{ number_format($subTotalAmount, 2) }}</th>
                                @endif
                                <th></th>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th></th>
                        <th></th>
                        <th></th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th></th>
                        @endif
                        <th>{{ number_format(round($totalOrderQty), 2) }}</th>
                        <th></th>
                        <th></th>
                        @if(!request('pdf-type'))
                            <th>{{ number_format($avgRate, 2) }}</th>
                            <th>{{ number_format($totalBdTaka, 2) }}</th>
                            <th>{{ number_format($totalAmount, 2) }}</th>
                        @endif
                        <th></th>
                    </tr>
                </table>
            </div>
        @endif


        @php

            $sizeSensitivity = collect($trimsBookingsDetailsSizeSensitivity)->flatten(1);
            $extra_fields_key = collect($sizeSensitivity)->pluck('extra_field_keys')->flatten(1)->unique()->values()->toArray() ?? [];
        @endphp

        @if(count($sizeSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 16 + ($trimsBookings->level == 2 ? 1 : 0)}}">
                            <b>Size Sensitivity(Unique Id):</b>
                            <b> (Unique Id):</b>
                            {{ collect($sizeSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b> Style: </b>
                            {{ collect($sizeSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($sizeSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                            @endif

                        </td>
                    </tr>
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th>Gmts Item</th>
                        <th>Item Name</th>
                        <th>Gmts Size</th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th>Body Part</th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th>Item Color</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th>PANTONE / CODE</th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th>Item Size</th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th>CARE LABEL TYPE</th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th>AGE GROUP</th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th>Description</th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th>Computer Ref</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th>FACTORY REF NO</th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>PRODUCTION BATCH</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th>LENGTH (inch)</th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th>WIDTH (inch)</th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th>LENGTH (CM)</th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th>Width (CM)</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CARD COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th>PLASTER FASTENER & ADJUSTABLE STRAPS QUALITY</th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th>QUALITY</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>FIBER COMPOSITION</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>
                                CARE SYMBOL
                            </th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>CARE INSTRUCTION</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>
                                SWATCH
                            </th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>
                                POLY BAG ART WORK
                            </th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th>
                                POLY BAG ART WORK
                            </th>
                        @endif
                        <th>Order Qty</th>
                        <th>UOM</th>
                        <th>Attachment</th>
                        @if(!request('pdf-type'))
                            <th>Rate</th>
                            <th>BD TAKA</th>
                            <th>Amount</th>
                        @endif
                        <th>Remarks</th>
                    </tr>
                    @php
                        $totalAmount = 0;
                        $totalBdTaka = 0;
                        $totalOrderQty = 0;
                        $avgRate = collect($sizeSensitivity)->pluck('details')->avg('rate');
                    @endphp
                    @foreach(collect($sizeSensitivity)->groupBy('item_name') as $item => $itemWise)
                        @php
                            $subTotalAmount = 0;
                            $subTotalBdTaka = 0;
                            $subTotalOrderQty = 0;
                            $subAvgRate = collect($itemWise)->pluck('details')->avg('rate');
                        @endphp
                        @foreach(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name') as $index => $data)
                            @if($index === 0)
                                @php
                                    $count = collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count();
                                @endphp
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    <td rowspan="{{ $count }}">{{ $data['gmts_item_name'] ?? '' }}</td>
                                    <td rowspan="{{ $count }}">{{ $item }}</td>
                                    <td>{{ $data['size'] ?? '' }}</td>
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['item_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type']
                                        ? \SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking::CARE_LABELS[$data['care_label_type']]
                                        : '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt=""
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @else
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    <td>{{ $data['size'] ?? '' }}</td>
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['item_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type']
                                        ? \SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking::CARE_LABELS[$data['care_label_type']]
                                        : '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt=""
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @endif
                            @php
                                $totalAmount += $data['amount'] ?? 0;
                                $totalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $totalOrderQty += $data['wo_order_qty'] ?? 0;

                                $subTotalAmount += $data['amount'] ?? 0;
                                $subTotalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $subTotalOrderQty += $data['wo_order_qty'] ?? 0;
                            @endphp
                        @endforeach
                        @if(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count() > 0)
                            <tr>
                                {{--                        <th>Style</th>--}}
                                <th></th>
                                <th></th>
                                <th></th>
                                @if(in_array('body_part', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('pantone_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_label_type', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('size_range', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_description', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('factory_ref_no', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('production_batch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('team_id', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fold_over', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zip_puller_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_tape_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_instruction', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_bag_art_work', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_sizes', $extra_fields_key))
                                    <th></th>
                                @endif
                                <th>{{ number_format(round($subTotalOrderQty), 2) }}</th>
                                <th></th>
                                <th></th>
                                @if(!request('pdf-type'))
                                    <th>{{ number_format($subAvgRate, 2) }}</th>
                                    <th>{{ number_format($subTotalBdTaka, 2) }}</th>
                                    <th>{{ number_format($subTotalAmount, 2) }}</th>
                                @endif
                                <th></th>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th></th>
                        <th></th>
                        <th></th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th></th>
                        @endif
                        <th>{{ number_format(round($totalOrderQty), 2) }}</th>
                        <th></th>
                        <th></th>
                        @if(!request('pdf-type'))
                            <th>{{ number_format($avgRate, 2) }}</th>
                            <th>{{ number_format($totalBdTaka, 2) }}</th>
                            <th>{{ number_format($totalAmount, 2) }}</th>
                        @endif
                        <th></th>
                    </tr>
                </table>
            </div>
        @endif

        @php
            $colorAndSizeSensitivity = collect($trimsBookingsDetailsColorAndSizeSensitivity)->flatten(1);
            $extra_fields_key = collect($colorAndSizeSensitivity)->pluck('extra_field_keys')->flatten(1)->unique()->values()->toArray() ?? [];
        @endphp

        @if(count($colorAndSizeSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 16 + ($trimsBookings->level == 2 ? 1 : 0)}}">
                            <b>Color & Size Sensitivity(Unique Id):</b>
                            <b> (Unique Id):</b>
                            {{ collect($colorAndSizeSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b> Style: </b>
                            {{ collect($colorAndSizeSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($colorAndSizeSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                            @endif

                        </td>
                    </tr>
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th>Gmts Item</th>
                        <th>Item Name</th>
                        <th>Gmts Color</th>
                        <th>Gmts Size</th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th>Body Part</th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th>Item Color</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th>PANTONE / CODE</th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th>Item Size</th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th>CARE LABEL TYPE</th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th>AGE GROUP</th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th>Description</th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th>Computer Ref</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th>FACTORY REF NO</th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>PRODUCTION BATCH</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th>LENGTH (inch)</th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th>WIDTH (inch)</th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th>LENGTH (CM)</th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th>Width (CM)</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CARD COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th>PLASTER FASTENER & ADJUSTABLE STRAPS QUALITY</th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th>QUALITY</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>FIBER COMPOSITION</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>
                                CARE SYMBOL
                            </th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>CARE INSTRUCTION</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>
                                SWATCH
                            </th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>
                                POLY BAG ART WORK
                            </th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th>
                                Item Sizes
                            </th>
                        @endif
                        <th>Order Qty</th>
                        <th>UOM</th>
                        <th>Attachment</th>
                        @if(!request('pdf-type'))
                            <th>Rate</th>
                            <th>BD TAKA</th>
                            <th>Amount</th>
                        @endif
                        <th>Remarks</th>
                    </tr>
                    @php
                        $totalAmount = 0;
                        $totalBdTaka = 0;
                        $totalOrderQty = 0;
                        $avgRate = collect($colorAndSizeSensitivity)->pluck('details')->avg('rate');
                    @endphp
                    @foreach(collect($colorAndSizeSensitivity)->groupBy('item_name') as $item => $itemWise)
                        @php
                            $subTotalAmount = 0;
                            $subTotalBdTaka = 0;
                            $subTotalOrderQty = 0;
                            $subAvgRate = collect($itemWise)->pluck('details')->avg('rate');
                        @endphp
                        @foreach(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name') as $index => $data)
                            @if($index === 0)
                                @php
                                    $count = collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count();
                                @endphp
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    <td rowspan="{{ $count }}">{{ $data['gmts_item_name'] ?? '' }}</td>
                                    <td rowspan="{{ $count }}">{{ $item }}</td>
                                    <td>{{ $data['color'] ?? '' }}</td>
                                    <td>{{ $data['size'] ?? '' }}</td>
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type']
                                        ? \SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking::CARE_LABELS[$data['care_label_type']]
                                        : '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt=""
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @else
                                <tr>
                                    {{--                                <td>{{ $data['style'] ?? '' }}</td>--}}
                                    <td>{{ $data['color'] ?? '' }}</td>
                                    <td>{{ $data['size'] ?? '' }}</td>
                                    @if(in_array('body_part', $extra_fields_key))
                                        <td>{{ $data['body_part'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_color', $extra_fields_key))
                                        <td>{{ $data['color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('combo_color', $extra_fields_key))
                                        <td>{{ $data['combo_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('pantone_code', $extra_fields_key))
                                        <td>{{ $data['pantone_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_size', $extra_fields_key))
                                        <td>{{ $data['size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_label_type', $extra_fields_key))
                                        <td>{{ $data['care_label_type']
                                        ? \SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking::CARE_LABELS[$data['care_label_type']]
                                        : '' }}</td>
                                    @endif
                                    @if(in_array('size_range', $extra_fields_key))
                                        <td>{{ $data['size_range'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_description', $extra_fields_key))
                                        <td>{{ $data['item_description'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('brand', $extra_fields_key))
                                        <td>{{ $data['brand'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('ref', $extra_fields_key))
                                        <td>{{ $data['ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('style_ref', $extra_fields_key))
                                        <td>{{ $data['style_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('factory_ref_no', $extra_fields_key))
                                        <td>{{ $data['factory_ref_no'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('production_batch', $extra_fields_key))
                                        <td>{{ $data['production_batch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fabric_ref', $extra_fields_key))
                                        <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('po_ref', $extra_fields_key))
                                        <td>{{ $data['po_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('item_code', $extra_fields_key))
                                        <td>{{ $data['item_code'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_inch', $extra_fields_key))
                                        <td>{{ $data['length_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_inch', $extra_fields_key))
                                        <td>{{ $data['width_inch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('length_cm', $extra_fields_key))
                                        <td>{{ $data['length_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('width_cm', $extra_fields_key))
                                        <td>{{ $data['width_cm'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('measurement', $extra_fields_key))
                                        <td>{{ $data['measurement'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('qty_per_carton', $extra_fields_key))
                                        <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('thread_count', $extra_fields_key))
                                        <td>{{ $data['thread_count'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('cons_per_mtr', $extra_fields_key))
                                        <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('team_id', $extra_fields_key))
                                        @php
                                            $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                        @endphp
                                        <td>{{ $team ?? '' }}</td>
                                    @endif
                                    @if(in_array('league', $extra_fields_key))
                                        <td>{{ $data['league'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('division', $extra_fields_key))
                                        <td>{{ $data['division'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('age_or_size', $extra_fields_key))
                                        <td>{{ $data['age_or_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fold_over', $extra_fields_key))
                                        <td>{{ $data['fold_over'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_thickness', $extra_fields_key))
                                        <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('sizer', $extra_fields_key))
                                        <td>{{ $data['sizer'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('binding_color', $extra_fields_key))
                                        <td>{{ $data['binding_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('contrast_cord_color', $extra_fields_key))
                                        <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zip_puller_ref', $extra_fields_key))
                                        <td>{{ $data['zip_puller_ref'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                        <td>{{ $data['zipper_puller_teeth_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_tape_color', $extra_fields_key))
                                        <td>{{ $data['zipper_tape_color'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('zipper_size', $extra_fields_key))
                                        <td>{{ $data['zipper_size'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fusing_status', $extra_fields_key))
                                        <td>{{ $data['fusing_status'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                        <td>{{ $data['plaster_fastener_adjustable_straps_quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('quality', $extra_fields_key))
                                        <td>{{ $data['quality'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('fiber_composition', $extra_fields_key))
                                        <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('care_symbol', $extra_fields_key))
                                        <td>
                                            @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                                <img style="height: 120px; width: 200px" alt=""
                                                     src="{{ asset("storage/".$data['care_symbol'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('care_instruction', $extra_fields_key))
                                        <td>{{ $data['care_instruction'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('swatch', $extra_fields_key))
                                        <td>{{ $data['swatch'] ?? '' }}</td>
                                    @endif
                                    @if(in_array('poly_bag_art_work', $extra_fields_key))
                                        <td>
                                            @if(($data['poly_bag_art_work']) && file_exists(storage_path('app/public/' . $data['poly_bag_art_work'])))
                                                <img style="height: 70px; width: 80px" alt=""
                                                     src="{{ asset("storage/".$data['poly_bag_art_work'])  }}"
                                                     class="img-fluid">
                                            @else
                                                <img style="height: 50px; width: 50px"
                                                     src="{{ asset('/images/no_image.jpg') }}"
                                                     alt="No image found">
                                            @endif
                                        </td>
                                    @endif
                                    @if(in_array('item_sizes', $extra_fields_key))
                                        <td>{{ $data['item_sizes'] ?? '' }}</td>
                                    @endif
                                    <td>{{ round($data['wo_order_qty']) ?? '' }}</td>
                                    <td>{{ $data['uom'] ?? '' }}</td>
                                    <td>
                                        @if(($data['attachment']) && file_exists(storage_path('app/public/' . $data['attachment'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['attachment'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                    @if(!request('pdf-type'))
                                        <td>{{ $data['rate'] ?? '' }}</td>
                                        <td>{{ $data['bd_taka'] ?? '' }}</td>
                                        <td>{{ $data['amount'] ?? '' }}</td>
                                    @endif
                                    <td>{{ $data['remarks'] ?? '' }}</td>
                                </tr>
                            @endif
                            @php
                                $totalAmount += $data['amount'] ?? 0;
                                $totalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $totalOrderQty += $data['wo_order_qty'] ?? 0;

                                $subTotalAmount += $data['amount'] ?? 0;
                                $subTotalBdTaka += $data['bd_taka'] != '' ? $data['bd_taka'] ?? 0 : 0;
                                $subTotalOrderQty += $data['wo_order_qty'] ?? 0;
                            @endphp
                        @endforeach
                        @if(collect($itemWise)->pluck('details')->whereNotNull('gmts_item_name')->count() > 0)
                            <tr>
                                {{--                        <th>Style</th>--}}
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                @if(in_array('body_part', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('pantone_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_label_type', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('size_range', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_description', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('factory_ref_no', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('production_batch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_inch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('length_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('width_cm', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('team_id', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fold_over', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zip_puller_ref', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_tape_color', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('quality', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('care_instruction', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('poly_bag_art_work', $extra_fields_key))
                                    <th></th>
                                @endif
                                @if(in_array('item_sizes', $extra_fields_key))
                                    <th></th>
                                @endif
                                <th>{{ number_format(round($subTotalOrderQty), 2) }}</th>
                                <th></th>
                                <th></th>
                                @if(!request('pdf-type'))
                                    <th>{{ number_format($subAvgRate, 2) }}</th>
                                    <th>{{ number_format($subTotalBdTaka, 2) }}</th>
                                    <th>{{ number_format($subTotalAmount, 2) }}</th>
                                @endif
                                <th></th>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        {{--                        <th>Style</th>--}}
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        @if(in_array('body_part', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('pantone_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_label_type', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('size_range', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_description', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('factory_ref_no', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('production_batch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_inch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('length_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('width_cm', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('plaster_fastener_adjustable_straps_quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('quality', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th></th>
                        @endif
                        @if(in_array('item_sizes', $extra_fields_key))
                            <th></th>
                        @endif
                        <th>{{ number_format(round($totalOrderQty), 2) }}</th>
                        <th></th>
                        <th></th>
                        @if(!request('pdf-type'))
                            <th>{{ number_format($avgRate, 2) }}</th>
                            <th>{{ number_format($totalBdTaka, 2) }}</th>
                            <th>{{ number_format($totalAmount, 2) }}</th>
                        @endif
                        <th></th>
                    </tr>
                </table>
            </div>
        @endif

        @php
            $withoutSensitivity = collect($trimsBookingsDetailsWithoutSensitivity)->flatten(1);
            $contrastColor = collect($trimsBookingsDetailsContrastColorSensitivity)->flatten(1);
            $asPerGmtsColor = collect($trimsBookingsDetailsAsPerGmtsColor)->flatten(1);
            $sizeSensitivity = collect($trimsBookingsDetailsSizeSensitivity)->flatten(1);
            $colorAndSizeSensitivity = collect($trimsBookingsDetailsColorAndSizeSensitivity)->flatten(1);
        @endphp
        <div style="margin-top: 15px;">
            <table class="borderless">
                <thead>
                @php
                    $mtrPerCone = collect($withoutSensitivity)->whereNotNull('mtr_per_cone')->pluck('mtr_per_cone')->count();
                @endphp
                @if($mtrPerCone > 0)
                    @foreach(collect($withoutSensitivity)->pluck('item_name')->unique() as $item)
                        <tr>
                            <td class="text-left">
                                <span
                                    style="font-size: 12pt;"> {{ $item }} = EACH CONE MUST CONTAIN</span>
                                {{ collect($withoutSensitivity)->where('item_name', $item)->first()['mtr_per_cone'] }} M
                                <br>
                            </td>
                        </tr>
                    @endforeach
                @endif
                @php
                    $mtrPerCone = collect($contrastColor)->whereNotNull('mtr_per_cone')->pluck('mtr_per_cone')->count();
                @endphp
                @if($mtrPerCone > 0)
                    @foreach(collect($contrastColor)->pluck('item_name')->unique() as $item)
                        <tr>
                            <td class="text-left">
                                <span
                                    style="font-size: 12pt;"> {{ $item }} = EACH CONE MUST CONTAIN</span>
                                {{ collect($contrastColor)->where('item_name', $item)->first()['mtr_per_cone'] }} M
                                <br>
                            </td>
                        </tr>
                    @endforeach
                @endif
                @php
                    $mtrPerCone = collect($asPerGmtsColor)->whereNotNull('mtr_per_cone')->pluck('mtr_per_cone')->count();
                @endphp
                @if($mtrPerCone > 0)
                    @foreach(collect($asPerGmtsColor)->pluck('item_name')->unique() as $item)
                        <tr>
                            <td class="text-left">
                                <span
                                    style="font-size: 12pt;"> {{ $item }} = EACH CONE MUST CONTAIN</span>
                                {{ collect($asPerGmtsColor)->where('item_name', $item)->first()['mtr_per_cone'] }} M
                                <br>
                            </td>
                        </tr>
                    @endforeach
                @endif
                @php
                    $mtrPerCone = collect($sizeSensitivity)->whereNotNull('mtr_per_cone')->pluck('mtr_per_cone')->count();
                @endphp
                @if($mtrPerCone > 0)
                    @foreach(collect($sizeSensitivity)->pluck('item_name')->unique() as $item)
                        <tr>
                            <td class="text-left">
                                <span
                                    style="font-size: 12pt;"> {{ $item }} = EACH CONE MUST CONTAIN</span>
                                {{ collect($sizeSensitivity)->where('item_name', $item)->first()['mtr_per_cone'] }} M
                                <br>
                            </td>
                        </tr>
                    @endforeach
                @endif
                @php
                    $mtrPerCone = collect($colorAndSizeSensitivity)->whereNotNull('mtr_per_cone')->pluck('mtr_per_cone')->count();
                @endphp
                @if($mtrPerCone > 0)
                    @foreach(collect($colorAndSizeSensitivity)->pluck('item_name')->unique() as $item)
                        <tr>
                            <td class="text-left">
                                <span
                                    style="font-size: 12pt;"> {{ $item }} = EACH CONE MUST CONTAIN</span>
                                {{ collect($colorAndSizeSensitivity)->where('item_name', $item)->first()['mtr_per_cone'] }}
                                M
                                <br>
                            </td>
                        </tr>
                    @endforeach
                @endif
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
                            style="font-size: 12pt; font-weight: bold;">Total Booking Amount (in word)</span>: {{ ucwords($numberInWord->format(number_format($totalAmount, 2))) ?? '' }} {{$trimsBookings->currency ?? ''}}
                    </td>
                </tr>
                </thead>
            </table>
        </div>

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

    <div style="height: 60px"></div>
    <div class="signature">
        <table class="borderless">
            <tbody>
            <tr>
                <td class="text-center"><u>MERCHANDISER</u></td>
                <td class='text-center'><u>SR. MERCHANDISER</u></td>
                <td class="text-center"><u>MER. MANAGER</u></td>
                <td class="text-center"><u>MER. AGM</u></td>
                <td class="text-center"><u>MER. DGM</u></td>
                <td class="text-center"><u>GM OPERATION</u></td>
            </tr>
            <tr>
                @if(isset($signature->details))
                    @foreach(collect($signature->details)->sortBy('sequence') as $detail)
                        <td class="text-center">{{$detail->designation}}</td>
                    @endforeach
                @endif
            </tr>
            </tbody>
        </table>
    </div>
</div>
