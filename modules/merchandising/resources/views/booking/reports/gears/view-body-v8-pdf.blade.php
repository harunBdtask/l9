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
                @if( (request('type') == 'v4') || (request('type') == 'v5') )
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
                <td>{{ $trimsBookings->delivery_to ?? ''}}</td>
            </tr>

        </table>

        @php
            $withoutSensitivity = collect($trimsBookingsDetailsWithoutSensitivity)->flatten(1);
            $extra_fields_key = collect($withoutSensitivity)->pluck('extra_field_keys')->flatten(1)->unique()->values()->toArray() ?? [];
        @endphp

        @if(count($withoutSensitivity) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr style="border:none">
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 17 + ($trimsBookings->level == 2 ? 1 : 0) }}">
                            <b> No Sensitivity(Unique Id)::</b>
                            {{ collect($withoutSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            {{--                            <b> Style: </b>--}}
                            {{--                            {{ collect($withoutSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}--}}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($withoutSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Style</th>
                        <th>Item</th>
                        @if($trimsBookings->level == 2)
                            <th> Po No:</th>
                        @endif
                        <th>Item Description</th>
                        <th>Computer Ref</th>
                        <th>Gmts. Color</th>
                        <th>Gmts. sizes</th>
                        <th>Size Details</th>
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>Production Batch</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>Fabric Composition</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>Care symbol</th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>Care Instruction</th>
                        @endif

                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>POLY BAG ART WORK</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>SWATCH</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF.</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER /TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CORD COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        <th>Wo Qty</th>
                        <th>Excess %</th>
                        <th>WO Total Qty.</th>
                        <th>MOQ Qty.</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach(collect($withoutSensitivity)->groupBy('item_name') as $item => $itemWise)
                        @foreach(collect($itemWise)->pluck('details') as $index => $data)
                            <tr>
                                <td>{{ $data['style'] ?? '' }}</td>
                                <td>{{ $item }}</td>
                                @if($trimsBookings->level == 2)
                                    <td>{{ collect($data['po_no'])->implode(',') ?? '' }}</td>
                                @endif
                                <td>{{ $data['item_description'] ?? '' }}</td>
                                <td>{{ $data['ref'] ?? '' }}</td>
                                <td>{{ $data['color'] ?? '' }}</td>
                                <td>{{ $data['size'] ?? '' }}</td>
                                <td>{{ $data['item_size'] ?? '' }}</td>
                                @if(in_array('production_batch', $extra_fields_key))
                                    <td>{{ $data['production_batch'] ?? '' }}</td>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <td>
                                        @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                            <img style="height: 70px; width: 80px" alt=""
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

                                @if(in_array('team_id', $extra_fields_key))
                                    @php
                                        $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                    @endphp
                                    <td>{{ $team ?? '' }}</td>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <td>{{ $data['division'] ?? '' }}</td>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <td>{{ $data['style_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <td>{{ $data['po_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <td>{{ $data['measurement'] ?? '' }}</td>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <td>{{ $data['thread_count'] ?? '' }}</td>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <td>{{ $data['league'] ?? '' }}</td>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <td>{{ $data['age_or_size'] ?? '' }}</td>
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
                                @if(in_array('fold_over', $extra_fields_key))
                                    <td>{{ $data['fold_over'] ?? '' }}</td>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <td>
                                        @if(($data['swatch']) && file_exists(storage_path('app/public/' . $data['swatch'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['swatch'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <td>{{ $data['sizer'] ?? '' }}</td>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <td>{{ $data['combo_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <td>{{ $data['item_code'] ?? '' }}</td>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <td>{{ $data['binding_color'] ?? '' }}</td>
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
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <td>{{ $data['zipper_size'] ?? '' }}</td>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <td>{{ $data['fusing_status'] ?? '' }}</td>
                                @endif
                                <td>{{ $data['wo_qty'] ?? '' }}</td>
                                <td>{{ $data['process_loss'] ?? '' }}</td>
                                <td>{{ $data['wo_total_qty'] ?? '' }}</td>
                                <td>{{ $data['moq_qty'] ?? '' }}</td>
                                <td>{{ $data['rate'] ?? '' }}</td>
                                <td>{{ $data['amount'] ?? '' }}</td>
                                <td>{{ $data['remarks'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
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
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 17  + ($trimsBookings->level == 2 ? 1 : 0)}}">
                            <b> Contrast Color(Unique Id)::</b>
                            {{ collect($contrastColor)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            <b>Item Name: </b>
                            {{ collect($contrastColor)->pluck('item_subgroup_name')->unique()->implode(', ') }}
                            {{--                            <b> Style: </b>--}}
                            {{--                            {{ collect($contrastColor)->unique('style_name')->pluck('style_name')->join(', ')  }}--}}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($contrastColor)->unique('po_no')->pluck('po_no')->join(', ') }} <br>
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <th>Style</th>
                        <th>Item</th>
                        @if($trimsBookings->level == 2)
                            <th> Po No:</th>
                        @endif
                        <th>Item Description</th>
                        <th>Computer Ref</th>
                        <th>Gmts. Color</th>
                        <th>Gmts. sizes</th>
                        <th>Size Details</th>
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>Production Batch</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>Fabric Composition</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>Care symbol</th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>Care Instruction</th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>POLY BAG ART WORK</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>SWATCH</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF.</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER /TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CORD COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        <th>Wo Qty</th>
                        <th>Excess %</th>
                        <th>WO Total Qty.</th>
                        <th>MOQ Qty.</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach(collect($contrastColor)->groupBy('item_name') as $item => $itemWise)
                        @foreach(collect($itemWise)->pluck('details') as $index => $data)
                            <tr>
                                <td>{{ $data['style'] ?? '' }}</td>
                                <td>{{ $item }}</td>
                                @if($trimsBookings->level == 2)
                                    <td>{{ collect($data['po_no'])->implode(',') ?? '' }}</td>
                                @endif
                                <td>{{ $data['item_description'] ?? '' }}</td>
                                <td>{{ $data['ref'] ?? '' }}</td>
                                <td>{{ $data['color'] ?? '' }}</td>
                                <td>{{ $data['size'] ?? '' }}</td>
                                <td>{{ $data['item_size'] ?? '' }}</td>
                                @if(in_array('production_batch', $extra_fields_key))
                                    <td>{{ $data['production_batch'] ?? '' }}</td>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <td>
                                        @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                            <img style="height: 70px; width: 80px" alt=""
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
                                @if(in_array('team_id', $extra_fields_key))
                                    @php
                                        $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                    @endphp
                                    <td>{{ $team ?? '' }}</td>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <td>{{ $data['division'] ?? '' }}</td>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <td>{{ $data['style_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <td>{{ $data['po_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <td>{{ $data['measurement'] ?? '' }}</td>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <td>{{ $data['thread_count'] ?? '' }}</td>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <td>{{ $data['league'] ?? '' }}</td>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <td>{{ $data['age_or_size'] ?? '' }}</td>
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
                                @if(in_array('fold_over', $extra_fields_key))
                                    <td>{{ $data['fold_over'] ?? '' }}</td>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <td>
                                        @if(($data['swatch']) && file_exists(storage_path('app/public/' . $data['swatch'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['swatch'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <td>{{ $data['sizer'] ?? '' }}</td>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <td>{{ $data['combo_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <td>{{ $data['item_code'] ?? '' }}</td>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <td>{{ $data['binding_color'] ?? '' }}</td>
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
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <td>{{ $data['zipper_size'] ?? '' }}</td>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <td>{{ $data['fusing_status'] ?? '' }}</td>
                                @endif
                                <td>{{ $data['wo_qty'] ?? '' }}</td>
                                <td>{{ $data['process_loss'] ?? '' }}</td>
                                <td>{{ $data['wo_total_qty'] ?? '' }}</td>
                                <td>{{ $data['moq_qty'] ?? '' }}</td>
                                <td>{{ $data['rate'] ?? '' }}</td>
                                <td>{{ $data['amount'] ?? '' }}</td>
                                <td>{{ $data['remarks'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
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
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 17 + ($trimsBookings->level == 2 ? 1 : 0)}}">
                            <b> As per Gmts Color(Unique Id)::</b>
                            {{ collect($asPerGmtsColor)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            {{--                            <b> Style: </b>--}}
                            {{--                            {{ collect($asPerGmtsColor)->unique('style_name')->pluck('style_name')->join(', ')  }}--}}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($asPerGmtsColor)->unique('po_no')->pluck('po_no')->join(', ') }}
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <th>Style</th>
                        <th>Item</th>
                        @if($trimsBookings->level == 2)
                            <th> Po No:</th>
                        @endif
                        <th>Item Description</th>
                        <th>Computer Ref</th>
                        <th>Gmts. Color</th>
                        <th>Gmts. sizes</th>
                        <th>Size Details</th>
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>Production Batch</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>Fabric Composition</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>Care symbol</th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>Care Instruction</th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>POLY BAG ART WORK</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>SWATCH</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF.</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER /TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CORD COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        <th>Wo Qty</th>
                        <th>Excess %</th>
                        <th>WO Total Qty.</th>
                        <th>MOQ Qty.</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach(collect($asPerGmtsColor)->groupBy('item_name') as $item => $itemWise)
                        @foreach(collect($itemWise)->pluck('details') as $index => $data)
                            <tr>
                                <td>{{ $data['style'] ?? '' }}</td>
                                <td>{{ $item }}</td>
                                @if($trimsBookings->level == 2)
                                    <td>{{ collect($data['po_no'])->implode(',') ?? '' }}</td>
                                @endif
                                <td>{{ $data['item_description'] ?? '' }}</td>
                                <td>{{ $data['ref'] ?? '' }}</td>
                                <td>{{ $data['color'] ?? '' }}</td>
                                <td>{{ $data['size'] ?? '' }}</td>
                                <td>{{ $data['item_size'] ?? '' }}</td>
                                @if(in_array('production_batch', $extra_fields_key))
                                    <td>{{ $data['production_batch'] ?? '' }}</td>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <td>
                                        @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                            <img style="height: 70px; width: 80px" alt=""
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
                                @if(in_array('team_id', $extra_fields_key))
                                    @php
                                        $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                    @endphp
                                    <td>{{ $team ?? '' }}</td>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <td>{{ $data['division'] ?? '' }}</td>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <td>{{ $data['style_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <td>{{ $data['po_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <td>{{ $data['measurement'] ?? '' }}</td>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <td>{{ $data['thread_count'] ?? '' }}</td>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <td>{{ $data['league'] ?? '' }}</td>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <td>{{ $data['age_or_size'] ?? '' }}</td>
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
                                @if(in_array('fold_over', $extra_fields_key))
                                    <td>{{ $data['fold_over'] ?? '' }}</td>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <td>
                                        @if(($data['swatch']) && file_exists(storage_path('app/public/' . $data['swatch'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['swatch'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <td>{{ $data['sizer'] ?? '' }}</td>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <td>{{ $data['combo_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <td>{{ $data['item_code'] ?? '' }}</td>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <td>{{ $data['binding_color'] ?? '' }}</td>
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
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <td>{{ $data['zipper_size'] ?? '' }}</td>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <td>{{ $data['fusing_status'] ?? '' }}</td>
                                @endif
                                <td>{{ $data['wo_qty'] ?? '' }}</td>
                                <td>{{ $data['process_loss'] ?? '' }}</td>
                                <td>{{ $data['wo_total_qty'] ?? '' }}</td>
                                <td>{{ $data['moq_qty'] ?? '' }}</td>
                                <td>{{ $data['rate'] ?? '' }}</td>
                                <td>{{ $data['amount'] ?? '' }}</td>
                                <td>{{ $data['remarks'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
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
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 17 + ($trimsBookings->level == 2 ? 1 : 0)}}">
                            <b>Size Sensitivity(Unique Id):</b>
                            <b> (Unique Id):</b>
                            {{ collect($sizeSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            {{--                            <b> Style: </b>--}}
                            {{--                            {{ collect($sizeSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}--}}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($sizeSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <th>Style</th>
                        <th>Item</th>
                        @if($trimsBookings->level == 2)
                            <th> Po No:</th>
                        @endif
                        <th>Item Description</th>
                        <th>Computer Ref</th>
                        <th>Gmts. Color</th>
                        <th>Gmts. sizes</th>
                        <th>Size Details</th>
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>Production Batch</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>Fabric Composition</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>Care symbol</th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>Care Instruction</th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>POLY BAG ART WORK</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>SWATCH</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF.</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER /TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CORD COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        <th>Wo Qty</th>
                        <th>Excess %</th>
                        <th>WO Total Qty.</th>
                        <th>MOQ Qty.</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach(collect($sizeSensitivity)->groupBy('item_name') as $item => $itemWise)
                        @foreach(collect($itemWise)->pluck('details') as $index => $data)
                            <tr>
                                <td>{{ $data['style'] ?? '' }}</td>
                                <td>{{ $item }}</td>
                                @if($trimsBookings->level == 2)
                                    <td>{{ collect($data['po_no'])->implode(',') ?? '' }}</td>
                                @endif
                                <td>{{ $data['item_description'] ?? '' }}</td>
                                <td>{{ $data['ref'] ?? '' }}</td>
                                <td>{{ $data['color'] ?? '' }}</td>
                                <td>{{ $data['size'] ?? '' }}</td>
                                <td>{{ $data['item_size'] ?? '' }}</td>
                                @if(in_array('production_batch', $extra_fields_key))
                                    <td>{{ $data['production_batch'] ?? '' }}</td>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <td>
                                        @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                            <img style="height: 70px; width: 80px" alt=""
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
                                @if(in_array('team_id', $extra_fields_key))
                                    @php
                                        $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                    @endphp
                                    <td>{{ $team ?? '' }}</td>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <td>{{ $data['division'] ?? '' }}</td>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <td>{{ $data['style_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <td>{{ $data['po_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <td>{{ $data['measurement'] ?? '' }}</td>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <td>{{ $data['thread_count'] ?? '' }}</td>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <td>{{ $data['league'] ?? '' }}</td>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <td>{{ $data['age_or_size'] ?? '' }}</td>
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
                                @if(in_array('fold_over', $extra_fields_key))
                                    <td>{{ $data['fold_over'] ?? '' }}</td>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <td>
                                        @if(($data['swatch']) && file_exists(storage_path('app/public/' . $data['swatch'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['swatch'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <td>{{ $data['sizer'] ?? '' }}</td>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <td>{{ $data['combo_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <td>{{ $data['item_code'] ?? '' }}</td>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <td>{{ $data['binding_color'] ?? '' }}</td>
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
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <td>{{ $data['zipper_size'] ?? '' }}</td>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <td>{{ $data['fusing_status'] ?? '' }}</td>
                                @endif
                                <td>{{ $data['wo_qty'] ?? '' }}</td>
                                <td>{{ $data['process_loss'] ?? '' }}</td>
                                <td>{{ $data['wo_total_qty'] ?? '' }}</td>
                                <td>{{ $data['moq_qty'] ?? '' }}</td>
                                <td>{{ $data['rate'] ?? '' }}</td>
                                <td>{{ $data['amount'] ?? '' }}</td>
                                <td>{{ $data['remarks'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endforeach

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
                        <td colspan="{{ count(array_intersect($extraFieldsKey, $extra_fields_key)) + 17 + ($trimsBookings->level == 2 ? 1 : 0)}}">
                            <b>Color & Size Sensitivity(Unique Id):</b>
                            <b> (Unique Id):</b>
                            {{ collect($colorAndSizeSensitivity)->unique('budget_unique_id')->pluck('budget_unique_id')->join(', ')  }}
                            {{--                            <b> Style: </b>--}}
                            {{--                            {{ collect($colorAndSizeSensitivity)->unique('style_name')->pluck('style_name')->join(', ')  }}--}}
                            @if($trimsBookings->level == 1)
                                <b> Po No: </b>
                                {{ collect($colorAndSizeSensitivity)->unique('po_no')->pluck('po_no')->join(', ') }}
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <th>Style</th>
                        <th>Item</th>
                        @if($trimsBookings->level == 2)
                            <th> Po No:</th>
                        @endif
                        <th>Item Description</th>
                        <th>Computer Ref</th>
                        <th>Gmts. Color</th>
                        <th>Gmts. sizes</th>
                        <th>Size Details</th>
                        @if(in_array('production_batch', $extra_fields_key))
                            <th>Production Batch</th>
                        @endif
                        @if(in_array('fiber_composition', $extra_fields_key))
                            <th>Fabric Composition</th>
                        @endif
                        @if(in_array('care_symbol', $extra_fields_key))
                            <th>Care symbol</th>
                        @endif
                        @if(in_array('care_instruction', $extra_fields_key))
                            <th>Care Instruction</th>
                        @endif
                        @if(in_array('team_id', $extra_fields_key))
                            <th>TEAM ID</th>
                        @endif
                        @if(in_array('division', $extra_fields_key))
                            <th>DIVISION</th>
                        @endif
                        @if(in_array('style_ref', $extra_fields_key))
                            <th>STYLE REF</th>
                        @endif
                        @if(in_array('po_ref', $extra_fields_key))
                            <th>PO REF</th>
                        @endif
                        @if(in_array('qty_per_carton', $extra_fields_key))
                            <th>QTY PER CARTON</th>
                        @endif
                        @if(in_array('measurement', $extra_fields_key))
                            <th>MEASUREMENT</th>
                        @endif
                        @if(in_array('fabric_ref', $extra_fields_key))
                            <th>FABRIC REF</th>
                        @endif
                        @if(in_array('thread_count', $extra_fields_key))
                            <th>THREAD COUNT</th>
                        @endif
                        @if(in_array('cons_per_mtr', $extra_fields_key))
                            <th>CONS PER MTR</th>
                        @endif
                        @if(in_array('league', $extra_fields_key))
                            <th>LEAGUE</th>
                        @endif
                        @if(in_array('age_or_size', $extra_fields_key))
                            <th>AGE OR SIZE</th>
                        @endif
                        @if(in_array('poly_bag_art_work', $extra_fields_key))
                            <th>POLY BAG ART WORK</th>
                        @endif
                        @if(in_array('fold_over', $extra_fields_key))
                            <th>FOLD OVER</th>
                        @endif
                        @if(in_array('poly_thickness', $extra_fields_key))
                            <th>POLY THICKNESS</th>
                        @endif
                        @if(in_array('swatch', $extra_fields_key))
                            <th>SWATCH</th>
                        @endif
                        @if(in_array('sizer', $extra_fields_key))
                            <th>SIZER</th>
                        @endif
                        @if(in_array('combo_color', $extra_fields_key))
                            <th>COMBO COLOR</th>
                        @endif
                        @if(in_array('item_code', $extra_fields_key))
                            <th>ITEM CODE</th>
                        @endif
                        @if(in_array('binding_color', $extra_fields_key))
                            <th>BINDING COLOR</th>
                        @endif
                        @if(in_array('zip_puller_ref', $extra_fields_key))
                            <th>ZIP PULLER REF.</th>
                        @endif
                        @if(in_array('zipper_puller_teeth_color', $extra_fields_key))
                            <th>ZIPPER PULLER /TEETH COLOR</th>
                        @endif
                        @if(in_array('zipper_tape_color', $extra_fields_key))
                            <th>ZIPPER TAPE COLOR</th>
                        @endif
                        @if(in_array('contrast_cord_color', $extra_fields_key))
                            <th>CONTRAST CORD COLOR</th>
                        @endif
                        @if(in_array('zipper_size', $extra_fields_key))
                            <th>ZIPPER SIZE</th>
                        @endif
                        @if(in_array('fusing_status', $extra_fields_key))
                            <th>FUSING STATUS</th>
                        @endif
                        <th>Wo Qty</th>
                        <th>Excess %</th>
                        <th>WO Total Qty.</th>
                        <th>MOQ Qty.</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach(collect($colorAndSizeSensitivity)->groupBy('item_name') as $item => $itemWise)
                        @foreach(collect($itemWise)->pluck('details') as $index => $data)
                            <tr>
                                <td>{{ $data['style'] ?? '' }}</td>
                                <td>{{ $item }}</td>
                                @if($trimsBookings->level == 2)
                                    <td>{{ collect($data['po_no'])->implode(',') ?? '' }}</td>
                                @endif
                                <td>{{ $data['item_description'] ?? '' }}</td>
                                <td>{{ $data['ref'] ?? '' }}</td>
                                <td>{{ $data['color'] ?? '' }}</td>
                                <td>{{ $data['size'] ?? '' }}</td>
                                <td>{{ $data['item_size'] ?? '' }}</td>
                                @if(in_array('production_batch', $extra_fields_key))
                                    <td>{{ $data['production_batch'] ?? '' }}</td>
                                @endif
                                @if(in_array('fiber_composition', $extra_fields_key))
                                    <td>{{ $data['fiber_composition'] ?? '' }}</td>
                                @endif
                                @if(in_array('care_symbol', $extra_fields_key))
                                    <td>
                                        @if(($data['care_symbol']) && file_exists(storage_path('app/public/' . $data['care_symbol'])))
                                            <img style="height: 70px; width: 80px" alt=""
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
                                @if(in_array('team_id', $extra_fields_key))
                                    @php
                                        $team = \SkylarkSoft\GoRMG\SystemSettings\Models\Team::find($data['team_id'])['team_name'] ?? null;
                                    @endphp
                                    <td>{{ $team ?? '' }}</td>
                                @endif
                                @if(in_array('division', $extra_fields_key))
                                    <td>{{ $data['division'] ?? '' }}</td>
                                @endif
                                @if(in_array('style_ref', $extra_fields_key))
                                    <td>{{ $data['style_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('po_ref', $extra_fields_key))
                                    <td>{{ $data['po_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('qty_per_carton', $extra_fields_key))
                                    <td>{{ $data['qty_per_carton'] ?? '' }}</td>
                                @endif
                                @if(in_array('measurement', $extra_fields_key))
                                    <td>{{ $data['measurement'] ?? '' }}</td>
                                @endif
                                @if(in_array('fabric_ref', $extra_fields_key))
                                    <td>{{ $data['fabric_ref'] ?? '' }}</td>
                                @endif
                                @if(in_array('thread_count', $extra_fields_key))
                                    <td>{{ $data['thread_count'] ?? '' }}</td>
                                @endif
                                @if(in_array('cons_per_mtr', $extra_fields_key))
                                    <td>{{ $data['cons_per_mtr'] ?? '' }}</td>
                                @endif
                                @if(in_array('league', $extra_fields_key))
                                    <td>{{ $data['league'] ?? '' }}</td>
                                @endif
                                @if(in_array('age_or_size', $extra_fields_key))
                                    <td>{{ $data['age_or_size'] ?? '' }}</td>
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
                                @if(in_array('fold_over', $extra_fields_key))
                                    <td>{{ $data['fold_over'] ?? '' }}</td>
                                @endif
                                @if(in_array('poly_thickness', $extra_fields_key))
                                    <td>{{ $data['poly_thickness'] ?? '' }}</td>
                                @endif
                                @if(in_array('swatch', $extra_fields_key))
                                    <td>
                                        @if(($data['swatch']) && file_exists(storage_path('app/public/' . $data['swatch'])))
                                            <img style="height: 70px; width: 80px" alt=""
                                                 src="{{ asset("storage/".$data['swatch'])  }}"
                                                 class="img-fluid">
                                        @else
                                            <img style="height: 50px; width: 50px"
                                                 src="{{ asset('/images/no_image.jpg') }}"
                                                 alt="No image found">
                                        @endif
                                    </td>
                                @endif
                                @if(in_array('sizer', $extra_fields_key))
                                    <td>{{ $data['sizer'] ?? '' }}</td>
                                @endif
                                @if(in_array('combo_color', $extra_fields_key))
                                    <td>{{ $data['combo_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('item_code', $extra_fields_key))
                                    <td>{{ $data['item_code'] ?? '' }}</td>
                                @endif
                                @if(in_array('binding_color', $extra_fields_key))
                                    <td>{{ $data['binding_color'] ?? '' }}</td>
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
                                @if(in_array('contrast_cord_color', $extra_fields_key))
                                    <td>{{ $data['contrast_cord_color'] ?? '' }}</td>
                                @endif
                                @if(in_array('zipper_size', $extra_fields_key))
                                    <td>{{ $data['zipper_size'] ?? '' }}</td>
                                @endif
                                @if(in_array('fusing_status', $extra_fields_key))
                                    <td>{{ $data['fusing_status'] ?? '' }}</td>
                                @endif
                                <td>{{ $data['wo_qty'] ?? '' }}</td>
                                <td>{{ $data['process_loss'] ?? '' }}</td>
                                <td>{{ $data['wo_total_qty'] ?? '' }}</td>
                                <td>{{ $data['moq_qty'] ?? '' }}</td>
                                <td>{{ $data['rate'] ?? '' }}</td>
                                <td>{{ $data['amount'] ?? '' }}</td>
                                <td>{{ $data['remarks'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                </table>
            </div>
        @endif


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
