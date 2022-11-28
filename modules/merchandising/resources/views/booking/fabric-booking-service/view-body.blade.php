<div style="padding-top: 50px;">
    <table class="borderless">
        <thead>
        <tr>
            <td class="text-left">
                <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                {{ factoryAddress() }}
                <br>
            </td>
            <td class="text-right" style="text-align: right;">
                Booking No: <b> {{ $bookings->booking_no ?? '' }}</b><br>
                Booking Date: <b> {{ $bookings->booking_date ?? '' }}</b><br>
            </td>
        </tr>
        </thead>
    </table>
    <hr>
    </div>
    <center>
        <table style="border: 1px solid black;width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Fabric Service Booking</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
    <br>

    <div class="body-section" style="margin-top: 0px;">
        <div class="row">
            <div class="col-md-4" style="float: left; position:relative; margin-top:30px">
                <table class="borderless">
                    <tbody>
                    <tr>
                        <td style="padding-left: 0;" class="text-left">
                            <strong>SUPPLIER :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-left"> {{ isset($bookings) ? optional($bookings->supplier)->name : ''}} </td>
                    </tr>

                    <tr>
                        <td style="padding-left: 0;" class="text-left">
                            <strong>BUYER :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-left">{{ isset($bookings) ? optional($bookings->buyer)->name : ''}} </td>
                    </tr>

                    <tr>
                        <td style="padding-left: 0;" class="text-left">
                            <strong>ATTN :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-left">{{ $bookings->attention ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-left">
                            <strong>ORDER DATE :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-left">{{ $bookings->booking_date ?? '' }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-md-4" style="float: left; position:relative; margin-top:30px">

            </div>
            <div class="col-md-4" style="float: right; position:relative;margin-top:30px">
                <table class="borderless">
                    <tbody>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>BOOKING DATE:</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ isset($bookings->booking_date) ? \Carbon\Carbon::make($bookings->booking_date)->toFormattedDateString() : '' }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>DELIVERY DATE:</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ isset($bookings->delivery_date) ? \Carbon\Carbon::make($bookings->delivery_date)->toFormattedDateString() : '' }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>APPROVAL STATUS:</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ $bookings->is_approved == 1 ? 'APPROVED' : 'UNAPPROVED' }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>PROCESS:</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right">{{ $bookings->processInfo->process_name ?? '' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @php
            $totalWoQty = 0;
            $totalAmount = 0;
        @endphp
        @if(isset($bookings) && count((optional($bookings)->FabricServiceDetails)) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr>
                        <td colspan="14" class="text-center"><b>FABRIC DETAILS</b></td>
                    </tr>

                    <tr>

                    </tr>
                    <tr>
                        <th rowspan="2" style="max-width: 15px">SL</th>
                        <th rowspan="2">STYLE <br> & PO</th>
                        <th rowspan="2">COLOR <br> & LABDIP</th>
                        <th rowspan="2">FABRIC DESCRIPTION</th>
                        <th rowspan="2">COUNT, LOT <br> COMPOSITION, BRAND</th>
                        <th colspan="3" class="text-center">DIA</th>
                        <th rowspan="2">S.L</th>
                        <th rowspan="2">GAUGE</th>
                        <th rowspan="2">WO QTY</th>
                        <th rowspan="2">UOM</th>
                        <th rowspan="2">RATE</th>
                        <th rowspan="2">AMOUNT</th>
                    </tr>
                    <tr>
                        <th>M/C</th>
                        <th>FINISH</th>
                        <th>GSM</th>
                    </tr>
                    @foreach($bookings->FabricServiceDetails as $key => $item)
                        @php
                            $totalWoQty += $item['wo_qty'];
                            $totalAmount += $item['amount'];
                        @endphp
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>
                                {{ $item['style_name'] }},
                                {{ $item['po_no'] }}
                            </td>
                            <td>
                                {{ $item['gmts_color'] }},
                                {{ $item['labdip_no'] }},
                            </td>
                            <td>{{ $item['fabric_description'] }}</td>
                            <td>
                                {{ $item['yarn_count'] }},
                                {{ $item['lot'] }},
                                {{ $item['yarn_composition'] }},
                                {{ $item['brand'] }},
                            </td>
                            <td>{{ $item['mc_dia'] }}</td>
                            <td>{{ $item['finish_dia'] }}</td>
                            <td>{{ $item['finish_gsm'] }}</td>
                            <td>{{ $item['stich_length'] }}</td>
                            <td>{{ $item['mc_gauge'] }}</td>
                            <td>{{ round($item['wo_qty']) }}</td>
                            <td>{{ $item['uom'] }}</td>
                            <td>{{ $item['rate'] }}</td>
                            <td>{{ number_format($item['amount'], 4) }}</td>

                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="10"><b>Total</b></td>
                        <td class="text-right"><b>{{ round($totalWoQty) }}</b></td>
                        <td class="text-right" colspan="2"></td>
                        <td class="text-right"><b>{{ $totalAmount }}</b></td>
                    </tr>
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
                <td class="text-left" style="width: 160px"><b>Total Booking Amount :</b></td>
                <td> {{number_format($totalAmount,4)." ".$currency[0]}}</td>
            </tr>
            </tbody>
        </table>
        <table class="borderless">
            <tbody>
            <tr>
                @php
                    $totalAmountt =  sprintf("%.4f",$totalAmount);
                    $totalAmount = ucwords((new NumberFormatter("en", NumberFormatter::SPELLOUT))->format($totalAmountt));
                @endphp
                <td class="text-left" style="width: 223px"><b>Total Booking Amount (In Words):</b></td>
                <td> {{$totalAmount." ".$currency[0]}}</td>
            </tr>
            </tbody>
        </table>
        <table class="borderless">
            <tbody>
            <tr>
                <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
            </tr>
            @if(isset($termsConditions))
                @foreach($termsConditions as $item)
                    <tr>
                        <td>{{ '* '. $item->terms_name }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    @include('skeleton::reports.downloads.signature')
</div>
