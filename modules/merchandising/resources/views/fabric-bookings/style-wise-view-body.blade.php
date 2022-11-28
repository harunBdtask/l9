<div>
    <div>
{{--        <table class="borderless">--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <td class="text-center">--}}
{{--                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>--}}
{{--                    <b>{{ factoryAddress() }}</b><br>--}}
{{--                    <span>Tel: +8809610-864328, Mail: info@gears-group.com</span>--}}
{{--                    <br>--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--        </table>--}}
        <br>
        <hr>
    </div>
    <center>
        <table style="border: 1px solid black;width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Fabric Booking Sheet</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
    <br>

    <div class="body-section" style="margin-top: 0px;">
        <table>
            {{--            <tr>--}}
            {{--                <th colspan="2" style="text-align: center"><b><u>PURCHASE ORDER</u></b></th>--}}
            {{--            </tr>--}}
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
                <th style="width: 250px">APPROVED BY:</th>
                <td></td>
            </tr>
            <tr>
                <th style="width: 250px">TEAM LEADER:</th>
                <td>{{$fabricBookings->team_leader ?? ''}}</td>
                <th style="width: 250px">DEALING MERCHANT:</th>
                <td>{{ $fabricBookings->dealing_merchant ?? '' }}</td>
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
                        <th>{{ localizedFor('Style') }} </th>
                        <th>{{ localizedFor('PO') }} </th>
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
                                        $colorWiseIndexCondition = true;
                                        $colorWiseIndex = 0;
                                    @endphp
                                    @foreach($colorWiseGroup as $colorWise => $colorWiseDetails)
                                        @foreach($colorWiseDetails as $key => $details)
                                            @php
                                                $fabricColorWise = collect($colorWiseDetails)->where('fabric_color', $details['fabric_color'] ?? '');
                                                if ($colorWiseIndex >= count($fabricColorWise)) {
                                                    $colorWiseIndex = 0;
                                                    $colorWiseIndexCondition = true;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $details['style_name'] }}</td>
                                                <td>{{ $details['po'] ?? ''  }}</td>
                                                @if($rowSpanForDescription == true)
                                                <td style="min-width: 200px;" rowspan="{{ $countDescription }}"  class="text-center">
                                                    <strong>
                                                        {{ $details['fabric_composition_v4'] ?? ''  }},
                                                        GSM-{{ $details['gsm'] ?? ''}},
                                                        Width-{{ $details['cuttable_width'] ?? '' }}
                                                    </strong>
                                                </td>
                                                @endif
                                                @if($colorWiseIndexCondition == true)
                                                    @php
                                                        $colorWiseIndexCondition = false;
                                                    @endphp
                                                    <td rowspan="{{ count($fabricColorWise) }}">{{ $details['fabric_color'] ?? ''}}</td>
                                                @endif
                                                <td class="text-right">{{ round($details['actual_wo_qty']) }}</td>
                                                <td>{{ $details['uom_value'] }}</td>
                                                <td>{{ '' }}</td>
                                            </tr>
                                            @php
                                                $colorWiseIndex += 1;
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

    <div>

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
</div>
