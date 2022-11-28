<div>
    {{--    <div>--}}
    {{--        <table class="borderless">--}}
    {{--            <thead>--}}
    {{--            <tr>--}}
    {{--                <td class="text-left">--}}
    {{--                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>--}}
    {{--                    {{ factoryAddress() }}--}}
    {{--                    <br>--}}
    {{--                </td>--}}
    {{--                <td>--}}
    {{--                    Booking No: <span> {{ $trimsBookings->unique_id ?? '' }}</span><br>--}}
    {{--                    Booking Date: <span> {{ $trimsBookings->booking_date ?? ''}}</span><br>--}}
    {{--                    Revise No: <span> {{ '1' }}</span><br>--}}
    {{--                </td>--}}
    {{--            </tr>--}}
    {{--            </thead>--}}
    {{--        </table>--}}
    {{--        <hr>--}}
    {{--    </div>--}}
    <center>
        <table style="border: 1px solid black;width: 25%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Fabric Purchase Order</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
    <br>
    @php
        $fabricDetailsSum=0;
        $total_amount_sum = 0;

    @endphp

    <div class="body-section" style="margin-top: 0px;">
        <div>
            <table>
                {{--                <tr>--}}
                {{--                    <th>Company Name</th>--}}
                {{--                    <td>{{ optional($fabricBookings)->factory->factory_name ?? ''}}</td>--}}
                {{--                    <th>Buyer Name</th>--}}
                {{--                    <td>{{ optional($fabricBookings)->buyer->name ?? '' }}</td>--}}
                {{--                    <th>Booking No</th>--}}
                {{--                    <td>{{ $fabricBookings->unique_id ?? '' }}</td>--}}
                {{--                </tr>--}}
                <tr>
                    <th>Buyer</th>
                    <td>{{ $fabricBookings->buyer->name ?? '' }}</td>
                    <th>Booking No</th>
                    <td>{{ $fabricBookings->unique_id ?? '' }}</td>
                    <th>Booking Date</th>
                    <td>{{ $fabricBookings->booking_date ?? '' }}</td>
                </tr>

                <tr>
                    <th>Supplier Name:</th>
                    <td>{{  optional($fabricBookings)->supplier->name ?? '' }}</td>
                    <th>Season:</th>
                    <td>{{ $fabricBookings->season ?? ''}}</td>
                    <th>Delivery Date:</th>
                    <td>{{ $fabricBookings->delivery_date ?? ''}}</td>
                </tr>

                <tr>
                    <th>Address:</th>
                    <td colspan="3">{{ optional($fabricBookings)->supplier->address_1 ?? ''  }}</td>
                    <th>Approval Status:</th>
                    <td>{{ $fabricBookings ? ($fabricBookings->ready_to_approve == '1' ? 'yes' : 'No') : '' }}</td>
                </tr>

                <tr>
                    <th>Attention</th>
                    <td>{{ $fabricBookings->attention ?? '' }}</td>
                    <th>Dept :</th>
                    <td>{{ $fabricBookings->productDept ?? '' }}</td>
                    <th>Dealing Merchant</th>
                    <td>{{ $fabricBookings->dealing_merchant ?? '' }}</td>
                </tr>
                <tr>
                    <th>Currency:</th>
                    <td>{{ optional($fabricBookings)->currency->currency_name ?? '' }}</td>
                    <th>Garments Qty :</th>
                    <td>{{ $fabricBookings->budget_qty ?? 0 }}</td>
                    <th>Created DateTime:</th>
                    <td>{{\Carbon\Carbon::parse($fabricBookings->created_at)->format('F d, Y - h:i:s A') ?? ''}}</td>

                </tr>
                <tr>
                    <th>Remarks</th>
                    <td colspan="3">{{ $fabricBookings->remarks ?? '' }}</td>
                    <th>Last Edit DateTime:</th>
                    @php
                         $updated_at =  collect($fabricBookings->detailsBreakdown)->sortByDesc('updated_at')->first()['updated_at']  ?? $fabricBookings->updated_at;
                    @endphp
                    <td>{{\Carbon\Carbon::parse($updated_at)->format('F d, Y - h:i:s A') ?? ''}}</td>
                </tr>
                {{--                <tr>--}}
                {{--                    <th>Fabric Composition:</th>--}}
                {{--                    <td colspan="5">{{ $fabricBookings->fabric_composition ?? ''}}</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <th>Remarks</th>--}}
                {{--                    <td colspan="5">{{ $fabricBookings->remarks ?? '' }}</td>--}}
                {{--                </tr>--}}

            </table>
        </div>


        @if(isset($fabricBookings) && count((optional($fabricBookings)->details)) > 0)
            <div style="margin-top: 15px;">
                @php
                    $fabricDetailsUomWise = collect($fabricBookings->details)->groupBy('uom');
                    //dump($fabricDetailsUomWise)
                @endphp
                <table style="margin-bottom: 10px;">
                    <tr>
                        <th colspan="20">PO No.: {{$fabricBookings->po_no}}</th>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td colspan="20" class="text-center"><b>Fabric Details ({{ ($fabricDetailsUomWise)->keys()[0] }}
                                )</b></td>
                    </tr>
                    <tr>
                        <th>SL</th>
                        <th>Style</th>
                        <th>Gmts Item</th>
                        <th>Body Parts</th>
                        <th>Fabrication</th>
                        <th>GSM</th>
                        <th>Fab. Dia</th>
                        <th>Color Type</th>
                        <th>Gmts Color</th>
                        <th>Fab. Color</th>
                        <th>LD</th>
                        {{--                        <th>CAD Cons</th>--}}
                        <th>Act. Fab. Qty</th>
                        <th>P. Loss %</th>
                        <th>Grey Cons</th>
                        <th>UOM</th>
                        <th>T. Fab. Qty</th>
                        <th>T. Fab Qty (Yds)</th>
                        <th>Avg Rate</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                    @if(isset($fabricBookings) && optional($fabricBookings)->details)
                        @foreach($fabricDetailsUomWise as $index => $details)
                            @if( !($loop->first))
                                <tr>
                                    <th colspan="17" class="text-center">Fabric Details ({{ $index }})</th>
                                </tr>
                            @endif
                        @php $total_actual_value = 0; @endphp
                            @foreach($details as $key => $item)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $item['style_name'] ?? ''  }}</td>
                                    <td>{{ $item['gmts_item']  ?? '' }}</td>
                                    <td>{{ $item['body_parts']  ?? ''}}</td>
                                    <td>{{ $item['composition'] ?? '' }}</td>
                                    <td>{{ $item['gsm'] ?? '' }}</td>
                                    <td>{{ $item['dia'] ?? '' }}</td>
                                    <td>{{ $item['color_type'] ?? '' }}</td>
                                    <td>{{ $item['gmts_color']  ?? ''}}</td>
                                    <td>{{ $item['fabric_color']  ?? ''}}</td>
                                    <td>{{ $item['remarks']  ?? ''}}</td>
                                    {{--                                    <td class="text-right">{{ $item['cad_consumption'] ?? '' }}</td>--}}
                                    <td class="text-right">
                                        @if($item['process_loss'] > 0)
                                            @php
                                                $actual_value = ($item['total_fabric_qty']/(100+$item['process_loss']))*100 ?? 0.00 ;
                                                $total_actual_value += $actual_value;
                                            @endphp
                                            {{number_format($actual_value,4)}}
                                        @else
                                            @php
                                                $actual_value = $item['total_fabric_qty']  ?? 0.00 ;
                                                $total_actual_value += $actual_value;
                                            @endphp
                                            {{ number_format($actual_value,4) }}
                                        @endif
                                    </td>
                                    <td class="text-right">{{ $item['process_loss'] ?? '' . '%' }} </td>
                                    <td>{{ $item['fabric_consumption'] ?? '' }}</td>
                                    <td>{{ $item['uom']  ?? ''}}</td>
                                    <td class="text-right">{{ $item['total_fabric_qty']  ?? ''}}</td>
                                    <td class="text-right">{{ $item['yards']  ?? ''}}</td>
                                    <td class="text-right">{{ $item['rate']  ?? ''}}</td>
                                    <td class="text-right">{{ number_format($item['amount'],4)  ?? ''}}</td>
                                    <td>{{ $item['remarks2']  ?? ''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="11" class="text-center">Total</th>
                                <td class="text-right">{{ number_format($total_actual_value,4) }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">{{ collect($details)->sum('total_fabric_qty') }}</td>
                                <td></td>
                                @php
                                    $sum = collect($details)->sum('amount');
                                    $fabricDetailsSum += $sum;
                                @endphp
                                <td></td>
                                <td class="text-right">{{ number_format($fabricDetailsSum,4) }}</td>
                            </tr>
                        @endforeach

                    @endif
                </table>
            </div>

        @endif


        @if( ($fabricBookings->fabric_source == 1) && count($yarnDetails)>0)
            <div style="margin-top: 15px">
                <table>
                    <tr>
                        <th colspan="9" class="text-center"><b>Yarn Cost Details</b></th>
                    </tr>
                    <tr>
                        <th>Unique Id</th>
                        <th>Style</th>
                        <th>Fabric Description</th>
                        <th>Yarn Description</th>
                        <th>Yarn Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Total Yarn Qty</th>
                        <th>Total Amount</th>
                    </tr>

                    @php
                        $total_qty_sum = 0;
                        $total_yarn_qty_sum = 0;
                        $total_rate_sum = 0;
                        $amount_sum = 0;
                    @endphp
                    @foreach($yarnDetails as $key => $yarn)
                        <tr>
                            <td>{{ $yarn['budgetId'] ?? '' }}</td>
                            <td>{{ $yarn['style'] ?? '' }}</td>
                            <td>{{ $yarn['fabric_description'] ?? '' }}</td>
                            <td>{{ $yarn['yarn_description'] ?? '' }}</td>
                            @php
                                $rate = $yarn['rate'] ?? 0;
                                $yarn_qty = $yarn['yarn_qty'] ?? 0;
                                $amount = ($rate * $yarn_qty);
                                $total_yarn_qty = $yarn['total_yarn_qty'] ?? 0;
                                $total_amount =  ($total_yarn_qty * $rate);

                                $total_qty_sum += $yarn_qty;
                                $total_rate_sum += $rate;
                                $amount_sum += $amount;
                                $total_yarn_qty_sum += $total_yarn_qty;
                                $total_amount_sum += $total_amount
                            @endphp
                            <td class="text-right">{{ $yarn_qty  }}</td>
                            <td class="text-right">{{ $rate  }}</td>
                            <td class="text-right">{{ $amount  }}</td>
                            <td class="text-right">{{ $total_yarn_qty }}</td>
                            <td class="text-right">{{ number_format($total_amount, 4) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-center"><b>Total</b></td>
                        <td class="text-right">{{ $total_qty_sum }}</td>
                        <td></td>
                        <td class="text-right">{{ $amount_sum }}</td>
                        <td class="text-right">{{ $total_yarn_qty_sum }}</td>
                        <td class="text-right">{{ number_format($total_amount_sum, 4) }}</td>
                    </tr>
                </table>
            </div>
        @endif

        @if(count($collarDetails)> 0 && count($collarStripDetails) > 0)
            <div style="margin-top: 15px">
                <table>
                    <tr>
                        <th colspan="8" style="text-align: center">Collar Strip Details</th>
                    </tr>
                    <tr>
                        <th>Sl</th>
                        <th>Color</th>
                        <th>Stripe Color</th>
                        <th>Measurement</th>
                        <th>UOM</th>
                        <th>Total Feeder</th>
                        <th>Fab Req. Qty (kg)</th>
                        <th>Yarn Dyed</th>
                    </tr>
                    @foreach($collarStripDetails as $collarDetailsKey => $collarItem)
                        @foreach(collect($collarItem['details']) as $index => $item)
                            <tr>

                                @if($loop->first)
                                    <td rowspan="{{ count(collect($collarItem)->pluck('details')) }}">{{ ++$index }}</td>
                                    <td rowspan="{{ count(collect($collarItem)->pluck('details')) }}">{{ $item['color_name'] }}</td>
                                @endif
                                <td>{{ $item['strip_color'] }}</td>
                                <td style="text-align: right">{{ $item['measurement'] }}</td>
                                <td>{{ $item['uom'] }}</td>
                                <td style="text-align: right">{{ $item['total_feeder'] }}</td>
                                <td style="text-align: right">{{ $item['feb_req_qty'] }}</td>
                                <td>{{ $item['yarn_dyed'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right">{{ $collarItem['calculation']['measurement_sum'] ?? 0 }}</th>
                            <th></th>
                            <th style="text-align: right">{{ $collarItem['calculation']['total_feeder_sum'] ?? 0 }}</th>
                            <th style="text-align: right">{{ $collarItem['calculation']['feb_req_qty_sum'] ?? 0 }}</th>
                            <th></th>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        @if(count($collarDetails)>0)
            @php
                $size_wise_collar_details= collect($collarDetails)->pluck('details')->flatten(1)->groupBy('size');
                $total_size = count($size_wise_collar_details) + 13;
            @endphp
            <div style="margin-top: 15px">
                <table>
                    <tr>
                        <th colspan="{{ $total_size }}" class="text-center"><b>Collar Details</b></th>
                    </tr>
                    <tr>
                        <th rowspan="2">SL</th>
                        <th rowspan="2">Style</th>
                        <th rowspan="2">Gmts Item</th>
                        <th rowspan="2">Body Part</th>
                        <th rowspan="2">Actual Qty(Pcs)</th>
                        <th rowspan="2">Fabric Composition</th>
                        <th rowspan="2">Color Type</th>
                        <th rowspan="2">GMTS.Color</th>
                        <th rowspan="2">Fabric Color</th>
                        @foreach($size_wise_collar_details as $key => $details )
                            <th>{{ $key }}</th>
                        @endforeach
                        <th rowspan="2">Excess %</th>
                        <th rowspan="2">Req Qty (Pcs)</th>
                        <th rowspan="2">Price/Pcs</th>
                        <th rowspan="2">Amount</th>
                    </tr>
                    <tr>
                        @foreach($size_wise_collar_details as $key => $details )
                            @php
                                $item_size = collect($details)->first()['item_size'] ?? '';
                            @endphp
                            <th>{{ $item_size }}</th>
                        @endforeach
                    </tr>
                    @foreach($collarDetails as $key => $details)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $details['style'] }}</td>
                            <td>{{ $details['gmts_item'] }}</td>
                            <td>{{ $details['body_part_value'] }}</td>
                            <td>{{ $details['actual_qty'] }}</td>
                            <td>{{ $details['fabric_composition'] }}</td>
                            <td>{{ $details['color_type_value'] }}</td>
                            <td>{{ $details['gmts_color'] }}</td>
                            <td>{{ $details['fabric_color'] }}</td>
                            @foreach($size_wise_collar_details as $key => $item)
                                @php
                                    $color = $details['gmts_color'];
                                    $cuff = collect($item)->where('color',$color)->sum('total_qty');
                                @endphp
                                <td>{{ $cuff }}</td>
                            @endforeach
                            <td class="text-right">{{ isset($details['excess']) ? number_format($details['excess'], 2) : 0 }}</td>
                            <td class="text-right">{{ $details['required_qty'] }}</td>
                            <td class="text-right">{{ $details['rate'] }}</td>
                            <td class="text-right">{{ $details['rate'] * $details['required_qty'] }}</td>

                        </tr>
                    @endforeach
                </table>
            </div>
        @endif


        @if(count($cuffDetails)> 0 && count($cuffStripDetails) > 0)
            <div style="margin-top: 15px">
                <table>
                    <tr>
                        <th colspan="8" style="text-align: center">Cuff Strip Details</th>
                    </tr>
                    <tr>
                        <th>Sl</th>
                        <th>Color</th>
                        <th>Stripe Color</th>
                        <th>Measurement</th>
                        <th>UOM</th>
                        <th>Total Feeder</th>
                        <th>Fab Req. Qty (kg)</th>
                        <th>Yarn Dyed</th>
                    </tr>
                    @foreach($cuffStripDetails as $cuffDetailsKey => $cuffItem)
                        @foreach(collect($collarItem['details']) as $index => $item)
                            <tr>

                                @if($loop->first)
                                    <td rowspan="{{ count(collect($cuffItem)->pluck('details')) }}">{{ ++$index }}</td>
                                    <td rowspan="{{ count(collect($cuffItem)->pluck('details')) }}">{{ $item['color_name'] }}</td>
                                @endif
                                <td>{{ $item['strip_color'] }}</td>
                                <td style="text-align: right">{{ $item['measurement'] }}</td>
                                <td>{{ $item['uom'] }}</td>
                                <td style="text-align: right">{{ $item['total_feeder'] }}</td>
                                <td style="text-align: right">{{ $item['feb_req_qty'] }}</td>
                                <td>{{ $item['yarn_dyed'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right">{{ $collarItem['calculation']['measurement_sum'] ?? 0 }}</th>
                            <th></th>
                            <th style="text-align: right">{{ $collarItem['calculation']['total_feeder_sum'] ?? 0 }}</th>
                            <th style="text-align: right">{{ $collarItem['calculation']['feb_req_qty_sum'] ?? 0 }}</th>
                            <th></th>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        @if(count($cuffDetails)>0)
            @php
                $size_wise_cuff_details= collect($cuffDetails)->pluck('details')->flatten(1)->groupBy('size');
                $total_size = count($size_wise_cuff_details) + 13;
            @endphp
            <div style="margin-top: 15px">
                <table>
                    <tr>
                        <th colspan="{{ $total_size }}" class="text-center"><b>Cuff Details</b></th>
                    </tr>
                    <tr>
                        <th rowspan="2">SL</th>
                        <th rowspan="2">Style</th>
                        <th rowspan="2">Gmts Item</th>
                        <th rowspan="2">Body Part</th>
                        <th rowspan="2">Actual Qty(Pcs)</th>
                        <th rowspan="2">Fabric Composition</th>
                        <th rowspan="2">Color Type</th>
                        <th rowspan="2">GMTS.Color</th>
                        <th rowspan="2">Fabric Color</th>
                        @foreach($size_wise_cuff_details as $key => $details )
                            <th>{{ $key }}</th>
                        @endforeach
                        <th rowspan="2">Excess %</th>
                        <th rowspan="2">Req Qty (Pcs)</th>
                        <th rowspan="2">Price/Pcs</th>
                        <th rowspan="2">Amount</th>
                    </tr>
                    <tr>
                        @foreach($size_wise_cuff_details as $key => $details )
                            @php
                                $item_size = collect($details)->first()['item_size'] ?? '';
                            @endphp
                            <th>{{ $item_size }}</th>
                        @endforeach
                    </tr>
                    @foreach($cuffDetails as $key => $details)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $details['style'] }}</td>
                            <td>{{ $details['gmts_item'] }}</td>
                            <td>{{ $details['body_part_value'] }}</td>
                            <td>{{ $details['actual_qty'] }}</td>
                            <td>{{ $details['fabric_composition'] }}</td>
                            <td>{{ $details['color_type_value'] }}</td>
                            <td>{{ $details['gmts_color'] }}</td>
                            <td>{{ $details['fabric_color'] }}</td>
                            @foreach($size_wise_cuff_details as $key => $item)
                                @php
                                    $color = $details['gmts_color'];
                                    $cuff = collect($item)->where('color',$color)->sum('total_qty');
                                @endphp
                                <td>{{ $cuff }}</td>
                            @endforeach
                            <td class="text-right">{{ isset($details['excess']) ? number_format($details['excess'], 2) : 0 }}</td>
                            <td class="text-right">{{ $details['required_qty'] }}</td>
                            <td class="text-right">{{ $details['rate'] }}</td>
                            <td class="text-right">{{ $details['rate'] * $details['required_qty'] }}</td>

                        </tr>
                    @endforeach
                </table>
            </div>
        @endif


        {{--        @if(count($poDetails) > 0)--}}
        {{--            <div style="margin-top: 15px">--}}
        {{--                <table>--}}
        {{--                    <tr>--}}
        {{--                        <td colspan="9" class="text-center"><b>PO Details</b></td>--}}
        {{--                    </tr>--}}
        {{--                    <tr>--}}
        {{--                        <th>SL</th>--}}
        {{--                        <th>PO Number</th>--}}
        {{--                        <th>PO Wise Ship Date</th>--}}
        {{--                        <th>Budget Qty</th>--}}
        {{--                        <th>Booking Qty</th>--}}
        {{--                        <th>Short Booking Qty</th>--}}
        {{--                        <th>Total Booking Qty</th>--}}
        {{--                        <th>Balance Qty</th>--}}
        {{--                        <th>Remarks</th>--}}
        {{--                    </tr>--}}

        {{--                    @foreach($poDetails as $key => $item)--}}
        {{--                        <tr>--}}
        {{--                            <td>{{ ++$key  }}</td>--}}
        {{--                            <td>{{ $item['po_no'] ?? ''}}</td>--}}
        {{--                            <td>{{ $item['shipment_date'] ?? '' }}</td>--}}
        {{--                            <td class="text-right">{{ number_format($item['budget_qty'], 2) }}</td>--}}
        {{--                            <td class="text-right">{{  number_format($item['booking_qty'], 2) }}</td>--}}
        {{--                            <td class="text-right">{{ number_format($item['short_booking_qty'], 2) }}</td>--}}
        {{--                            @php--}}
        {{--                                $totalBooking =   ($item['booking_qty']) + ($item['short_booking_qty']);--}}
        {{--                            @endphp--}}

        {{--                            <td class="text-right">{{  number_format($totalBooking, 2) }}</td>--}}
        {{--                            <td class="text-right">{{ number_format($item['budget_qty']  - $totalBooking, 2) }}</td>--}}
        {{--                            <td></td>--}}
        {{--                        </tr>--}}
        {{--                    @endforeach--}}
        {{--                </table>--}}
        {{--            </div>--}}
        {{--        @endif--}}

    </div>

    <div style="margin-top: 16mm">
        @php
            $grandTotal = number_format(($total_amount_sum + $fabricDetailsSum), 4);
            $grandTotal = (float)str_replace(',', '', $grandTotal);
            $numberFormatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
            $inword =ucwords($numberFormatter->format($grandTotal));
        @endphp
        <span><b>Total Fabric Amount: {{ $grandTotal  }} {{ optional($fabricBookings)->currency->currency_name ?? '' }}</b></span><br>
        <span><b>In Words: {{ $inword  }} {{ optional($fabricBookings)->currency->currency_name ?? '' }}</b> </span>


        {{--        <span><b>Total Fabric Amount: </b>{{ $fabricDetailsSum }}</span>--}}
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

    {{--    <div style="margin-top: 16mm">--}}
    {{--        <table class="borderless">--}}
    {{--            <tbody>--}}
    {{--            <tr>--}}
    {{--                <td class="text-center"><u>Prepared By</u></td>--}}
    {{--                <td class='text-center'><u>Checked By</u></td>--}}
    {{--                <td class="text-center"><u>Approved By</u></td>--}}
    {{--            </tr>--}}
    {{--            </tbody>--}}
    {{--        </table>--}}
    {{--    </div>--}}
</div>
