<div class="body-section" style="margin-top: 0px;">
{{--    <table class="border">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <td class="text-center">--}}
{{--                <span style="font-size: 12pt; font-weight: bold;">Fabric Bookings</span>--}}
{{--                <br>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--    </table>--}}
    <br>

    <table>
        <tr>
            <th colspan="4" style="text-align: center"><b>Fabric Booking Sheet</b></th>
        </tr>
        <tr>
            <th style="width: 250px">SUPPLIER:</th>
            <td>{{ isset($fabricBookings) ? optional($fabricBookings->supplier)->name : '' }}</td>
            <th>Booking No</th>
            <td>{{ $fabricBookings->unique_id ?? '' }}</td>
        </tr>
        <tr>
            <th style="width: 250px">BUYER:</th>
            <td>{{ isset($fabricBookings) ? optional($fabricBookings->buyer)->name : ''}}</td>
            <th>Booking Date</th>
            <td>{{ $fabricBookings->booking_date ?? '' }}</td>
        </tr>
        <tr>
            <th style="width: 250px">ATTN:</th>
            <td>{{ isset($fabricBookings) ? $fabricBookings->attention : ''}}</td>
            <th>Delivery Date:</th>
            <td>{{ $fabricBookings->delivery_date ?? ''}}</td>
        </tr>
        <tr>
            <th style="width: 250px">ORDER DATE:</th>
            <td>{{ $fabricBookings->booking_date ?? '' }}</td>
            <th>Approval Status:</th>
            <td>{{ $fabricBookings ? ($fabricBookings->ready_to_approve == '1' ? 'yes' : 'No') : '' }}</td>
        </tr>
        <tr>
            <th style="width: 250px">ISSUED BY:</th>
            <td>{{ $fabricBookings->dealing_merchant ?? '' }}</td>
            <td></td>
            <td></td>

        </tr>
        <tr>
            <th style="width: 250px">APPROVED BY:</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    @if(isset($fabricBookings) && count((optional($fabricBookings)->details)) > 0)
        <div style="margin-top: 15px;">
            <table>
                <tr>
                    <td colspan="8" class="text-center"><b>FABRIC DETAILS</b></td>
                </tr>
                <tr>
                    <th>SL</th>
                    <th>STYLE</th>
                    <th>PO</th>
                    <th>FABRIC DESCRIPTION</th>
                    <th>COLOR</th>
                    <th>BOOKING QTY</th>
                    <th>UOM</th>
                    <th>REMARK</th>
                </tr>
                @if(isset($fabricBookings) && count($fabricBookings->details) >0)
                    @php
                        $index = 0;
                        $styleWiseGroup = collect($fabricBookings->details)->groupBy('style_name');
                       // dump($styleWiseGroup);
                    @endphp
                    @foreach($styleWiseGroup as $styleWise => $styleWiseDetails)
                        @php
                            $poWiseGroup = collect($styleWiseDetails)->groupBy('po_no');
                        @endphp
                        @foreach($poWiseGroup as $poWise => $poWiseDetails)
                            @php
                                $descriptionWiseGroup = collect($poWiseDetails)->groupBy('fabric_composition');
                            @endphp
                            @foreach($descriptionWiseGroup as $descriptionWise => $descriptionWiseDetails)
                                @php
                                    $colorWiseGroup = collect($descriptionWiseDetails)->groupBy('fabric_color');
                                    $countDescription =  count($descriptionWiseDetails);
                                    $rowSpanForDescription = true;
                                @endphp
                                @foreach($colorWiseGroup as $colorWise => $colorWiseDetails)
                                    @foreach($colorWiseDetails as $key => $details)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $details['style_name'] }}</td>
                                            <td>{{ $details['po'] ?? ''  }}</td>
                                            @if($rowSpanForDescription == true)
                                            <td style="min-width: 200px;text-align:center" rowspan="{{ $countDescription }}">
                                                <strong>
                                                    {{ $details['fabric_composition_v4'] ?? ''  }},
                                                    GSM-{{ $details['gsm'] ?? ''}},
                                                    DIA-{{ $details['cuttable_width'] ?? '' }}
                                                </strong>  
                                            </td>
                                            @endif
                                            <td>{{ $details['fabric_color'] }}</td>
                                            <td>{{ $details['actual_wo_qty'] }}</td>
                                            <td>{{ $details['uom_value'] }}</td>
                                            <td>{{ '' }}</td>
                                        </tr>
                                        @php
                                            $rowSpanForDescription = false;
                                        @endphp
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach

                @endif
            </table>
        </div>
    @endif

</div>


<div style="margin-top: 16mm">
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