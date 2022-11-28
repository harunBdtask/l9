<table class="borderless">
    <thead>
    <tr>
        <td colspan="18" style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">
            {{ factoryName() }}
        </td>
    </tr>
    <tr>
        <td colspan="18" style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">
            {{ factoryAddress() }}
        </td>
    </tr>
    <tr>
        <td colspan="18" style="text-align: left; font-weight: bold; font-size: 20px; height: 35px">
            Booking No: {{ $fabricBookings->unique_id ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="18" style="text-align: left; font-weight: bold; font-size: 20px; height: 35px">
            Booking Date: {{ $fabricBookings->booking_date ?? ''}}
        </td>
    </tr>
    </thead>
</table>
<div>
    @php
        $fabricDetailsSum=0;
        $total_amount_sum = 0;
    @endphp

    <div class="body-section" style="margin-top: 0px;">
        <div>
            <table>
                <tr>
                    <th>Buyer</th>
                    <td>{{ $fabricBookings->buyer->name ?? '' }}</td>
                    <th>Order/Style No</th>
                    <td>{{ $fabricBookings->style_name ?? '' }}</td>
                    <th>Booking No</th>
                    <td>{{  collect($fabricBookings->detailsBreakdown)->pluck('budget.order.reference_no')->whereNotNull()->unique()->values()->join(', ') ?? '' }}</td>
                </tr>

                <tr>
                    <th>Supplier Name:</th>
                    <td>{{  optional($fabricBookings)->supplier->name ?? '' }}</td>
                    <th>Season:</th>
                    <td>{{ $fabricBookings->season ?? ''}}</td>
                    <th>Booking Date</th>
                    <td>{{ $fabricBookings->booking_date ?? '' }}</td>

                </tr>

                <tr>
                    <th>Address:</th>
                    <td colspan="3">{{ optional($fabricBookings)->supplier->address_1 ?? ''  }}</td>
                    <th>Delivery Date:</th>
                    <td>{{ $fabricBookings->delivery_date ?? ''}}</td>

                </tr>

                <tr>
                    <th>Attention</th>
                    <td>{{ $fabricBookings->attention ?? '' }}</td>
                    <th>Dept :</th>
                    <td>{{ $fabricBookings->productDept ?? '' }}</td>
                    <th>Approval Status:</th>
                    <td>{{ $fabricBookings ? ($fabricBookings->ready_to_approve == '1' ? 'yes' : 'No') : '' }}</td>

                </tr>
                <tr>
                    <th>Currency:</th>
                    <td>{{ optional($fabricBookings)->currency->currency_name ?? '' }}</td>
                    <th>Garments Qty :</th>
                    <td>{{ $fabricBookings->budget_qty ?? 0 }}</td>
                    <th>Dealing Merchant</th>
                    <td>{{ $fabricBookings->dealing_merchant ?? '' }}</td>
                </tr>
                <tr>
                    <th>Created Date Time:</th>
                    <td>{{\Carbon\Carbon::parse($fabricBookings->created_at)->format('F d, Y - h:i:s A') ?? ''}}</td>
                    <th>Last Edit Date Time:</th>
                    @php
                        $updated_at =  collect($fabricBookings->detailsBreakdown)->sortByDesc('updated_at')->first()['updated_at']  ?? $fabricBookings->updated_at;
                    @endphp
                    <td>{{\Carbon\Carbon::parse($updated_at)->format('F d, Y - h:i:s A') ?? ''}}</td>
                    <th>Shipment Date</th>
                    <td>{{ $sortedShipmentDate }}</td>
                </tr>
                <tr>
                    <th>PO</th>
                    <td colspan="5">{{ $fabricBookings->po_no ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Remarks:</th>
                    <td colspan="5">{{ $fabricBookings->remarks ?? '' }}</td>
                </tr>
            </table>
        </div>

        @if(isset($fabricBookings) && count((optional($fabricBookings)->details)) > 0)
            <div style="margin-top: 15px;">
                @php
                    $fabricDetailsUomWise = collect($fabricBookings->details)->groupBy('uom');
                    //dump($fabricDetailsUomWise)
                @endphp
                <table>
                    <tr>
                        <td colspan="20" class="text-center"><b>Fabric Details ({{ ($fabricDetailsUomWise)->keys()[0] }}
                                )</b></td>
                    </tr>
                    <tr>
                        <th>Part</th>
                        <th>Gmts Color</th>
                        <th>Fab. Color</th>
                        <th>Pantone</th>
                        <th>Composition</th>
                        <th>Type</th>
                        <th>GSM</th>
                        <th>Size</th>
                        <th>Cut. Dia</th>
                        <th>Fin Dia</th>
                        <th>Fin Type</th>
                        <th>Part Qty</th>
                        <th>Consmp</th>
                        <th>Unit</th>
                        <th>Act.Fab.Qty</th>
                        <th>Loss</th>
                        <th>Total Fab. Qty</th>
                        <th>Remarks</th>
                    </tr>
                    @if(isset($fabricBookings) && optional($fabricBookings)->details)
                        @foreach($fabricDetailsUomWise as $index => $details)
                            @php $total_actual_value = 0;@endphp
                            @if( !($loop->first))
                                <tr>
                                    <th colspan="18" class="text-center">Fabric Details ({{ $index }})</th>
                                </tr>
                            @endif
                            @foreach($details as $key => $item)
                                <tr>
                                    <td>{{ $item['body_parts'] ?? ''  }}</td>
                                    <td>{{ $item['gmts_color']  ?? ''}}</td>
                                    <td>{{ $item['fabric_color']  ?? ''}}</td>
                                    <td>{{ $item['pantone']  ?? ''}}</td>
                                    <td>{{ $item['composition_for_mondol'] ?? '' }}</td>
                                    <td>{{ $item['construction'] ?? '' }}</td>
                                    <td>{{ $item['gsm'] ?? '' }}</td>
                                    <td>{{ $item['sizes'] ?? '' }}</td>
                                    <td>{{ $item['cuttable_dia'] ?? '' }}</td>
                                    <td>{{ $item['dia'] ?? '' }}</td>
                                    <td>{{ $item['dia_fin_type'] ?? '' }}</td>
                                    <td>{{ $item['sizeWisePoQty']  ?? ''}}</td>
                                    <td>{{ round($item['fabric_consumption'], 2)  ?? ''}}</td>
                                    <td>{{ $item['uom']  ?? ''}}</td>
                                    {{--                                    <td class="text-right">{{ $item['cad_consumption'] ?? '' }}</td>--}}
                                    <td class="text-right">
                                        @if($item['process_loss'] > 0)
                                            @php
                                                $actual_value = ($item['total_fabric_qty']/(100+$item['process_loss']))*100 ?? 0.00 ;
                                                $total_actual_value += $actual_value;
                                            @endphp
                                            {{ round($actual_value) }}
                                        @else
                                            @php
                                                $actual_value = $item['total_fabric_qty']  ?? 0.00 ;
                                                $total_actual_value += $actual_value;
                                            @endphp
                                            {{ round($actual_value) }}
                                        @endif
                                    </td>
                                    <td class="text-right">{{ $item['process_loss'] ?? '' . '%' }} </td>
                                    <td class="text-right">{{ $item['total_fabric_qty']  ?? ''}}</td>
                                    <td>{{ $item['remarks2']  ?? ''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="14" class="text-center">Total</th>
                                <td class="text-right">{{ round($total_actual_value) }}</td>
                                <td></td>
                                <td class="text-right">{{ collect($details)->sum('total_fabric_qty') }}</td>
                                <td></td>
                            </tr>
                        @endforeach

                    @endif
                </table>
            </div>

        @endif


    </div>

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
                                <td rowspan="{{ count($collarItem['details']) }}">{{ ++$index }}</td>
                                <td rowspan="{{ count($collarItem['details']) }}">{{ $item['color_name'] }}</td>
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
                        <td>{{ $fabricBookings->style_name ?? '' }}</td>
                        {{--                        <td>{{ $details['style'] }}</td>--}}
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
                    @foreach(collect($cuffItem['details']) as $index => $item)
                        <tr>

                            @if($loop->first)
                                <td rowspan="{{ count($cuffItem['details']) }}">{{ ++$index }}</td>
                                <td rowspan="{{ count($cuffItem['details']) }}">{{ $item['color_name'] }}</td>
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
                        <td>{{ $fabricBookings->style_name ?? ''  }}</td>
                        {{--                        <td>{{ $details['style'] }}</td>--}}
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

