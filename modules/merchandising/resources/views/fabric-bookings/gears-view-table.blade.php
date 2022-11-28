<div class="body-section" style="margin-top: 0px;">
    <table>
        <tr>
            <th>SUPPLIER:</th>
            <td> {{ isset($fabricBookings) ? optional($fabricBookings->supplier)->name : '' }}</td>
            <th>ORDER REF NO:</th>
            <td></td>
            <th>Booking No/Reference</th>
            <td>{{ $fabricBookings->unique_id ?? '' }}</td>
        </tr>
        <tr>
            <th>ISSUE DATE:</th>
            <td>{{ $fabricBookings->booking_date ?? '' }}</td>
            <th>{{ localizedFor('Style') }}</th>
            <td>{{ $fabricBookings->styles ?? '' }}</td>
            <th>Booking Date</th>
            <td>{{ $fabricBookings->booking_date ?? '' }}</td>
        </tr>
        <tr>
            <th>APPROVED BY:</th>
            <td></td>
            <th>TEAM LEADER:</th>
            <td>{{ $fabricBookings->team_leader ?? ''}}</td>
            <th>Delivery Date:</th>
            <td>{{ $fabricBookings->delivery_date ?? ''}}</td>
        </tr>
        <tr>
            <th>ISSUED BY:</th>
            <td>{{ $fabricBookings->dealing_merchant ?? '' }}</td>
            <th>UNDER BUYER PO:</th>
            <td>{{ $fabricBookings->po ?? '' }}</td>
            <th>Approval Status:</th>
            <td>{{ $fabricBookings ? ($fabricBookings->ready_to_approve == '1' ? 'yes' : 'No') : '' }}</td>
        </tr>
        <tr>
            <th>DEALING MERCHANT:</th>
            <td>{{ $fabricBookings->dealing_merchant ?? '' }}</td>
            <td colspan="4">
                {{ $fabricBookings->remarks ?? '' }}
            </td>

        </tr>
    </table>

    @if(isset($fabricBookings) && count((optional($fabricBookings)->details)) > 0)
        <div style="margin-top: 15px;">
            <table>
                <tr>
                    <td colspan="5" class="text-center"><b>FABRIC DETAILS</b></td>
                </tr>
                <tr>
                    <th class="text-center">FABRIC DESCRIPTION</th>
                    <th class="text-center">FABRIC COLOR</th>
                    <th class="text-center">BOOKING QTY (YDS)</th>
                    <th class="text-center">BOOKING QTY (KGS)</th>
                    <th class="text-center">Remarks</th>
                </tr>
                @if(isset($fabricBookings) && count($fabricBookings->details) >0)
                    @php
                        $totalKgsAmount = 0;
                        $totalYdsAmount = 0;
                    @endphp
                    @foreach($fabricBookings->details->groupBy('fabric_composition') as $description => $descriptionWiseDetails)
                        @php
                            $descriptionKey = 0;
                        @endphp
                        @foreach(collect($descriptionWiseDetails)->groupBy('fabric_color') as $teams)
                            @foreach($teams as $team)
                                <tr>
                                    @if($descriptionKey == 0)
                                        <td rowspan="{{ $descriptionWiseDetails->count() }}" class="text-center">
                                            <strong>
                                                {{ $team['fabric_composition'] ?? ''  }},
                                                GSM-{{ $team['gsm'] ?? ''}},
                                                Width-{{ $team['cuttable_width'] ?? '' }}
                                            </strong>
                                        </td>
                                    @endif
                                    @if($loop->first)
                                        <td class="text-center"
                                            rowspan="{{ $teams->count() }}">{{ $team['fabric_color'] ?? ''}}</td>
                                    @endif
                                    @php
                                        $totalKgsAmount += $team['uom'] == "1" ?  round($team['actual_wo_qty']) : 0;
                                        $totalYdsAmount += $team['uom'] == "2" ?  round($team['actual_wo_qty']) : 0
                                    @endphp
                                    <td class="text-right">
                                        @php
                                            $actualWoQty = collect($teams)->where('uom',2)->sum('actual_wo_qty')
                                        @endphp
                                        {{ $actualWoQty !== 0 ? round($actualWoQty).' '.$team['uom_value'] : '' }}
                                    </td>
                                    <td class="text-right">
                                        @php
                                            $actualWoQty = collect($teams)->where('uom',1)->sum('actual_wo_qty');
                                            $actualWoQtyInYards = collect($teams)->where('uom',2)->sum('actual_wo_qty');
                                            $yardsToKg = $team['kg_cr'] != 0 ? $actualWoQtyInYards / $team['kg_cr'] : 0;
                                            $totalKgsAmount += $yardsToKg;
                                        @endphp
                                        {{ $actualWoQty !== 0 ? round($actualWoQty).' '. $team['uom_value'] : round($yardsToKg) . " KGS"}}
                                    </td>
                                    <td>
                                        {{ $team['remarks'] }}
                                    </td>
                                </tr>
                                @php
                                    $descriptionKey++;
                                @endphp
                            @endforeach
                        @endforeach
                    @endforeach
                @endif
                <tr>
                    <th colspan="2" class="text-right"><b>Total</b></th>
                    <td class="text-right"><b>{{ round($totalYdsAmount) }} YDS</b></td>
                    <td class="text-right"><b>{{ round($totalKgsAmount) }} KGS</b></td>
                    <td></td>
                </tr>
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
