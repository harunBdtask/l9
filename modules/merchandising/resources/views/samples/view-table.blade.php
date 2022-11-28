<div class="row">
    <center>
        <table style="border: 1px solid black;width: 25%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Sample View</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
    <br>
    <div class="col-md-10 col-md-offset-1">
        <table>
            <tbody>
            <tr>
                <td><b>Requisition Id</b></td>
                <td>{{ $sampleRequisition->requisition_id }}</td>
                <td><b>Sample Stage</b></td>
                <td>{{ $sampleRequisition->stage }}</td>
                <td><b>Requisition Date</b></td>
                <td>
                    {{ $sampleRequisition->req_date ? \Carbon\Carbon::make($sampleRequisition->req_date)->toFormattedDateString() : null  }}
                </td>
            </tr>
            <tr>
                <td><b>Buyer</b></td>
                <td>{{ $sampleRequisition->buyer->name ?? null }}</td>
                <td><b>{{ localizedFor('Style') }}</b></td>
                <td>{{ $sampleRequisition->style_name }}</td>
                <td><b>Location</b></td>
                <td>{{ $sampleRequisition->location }}</td>
            </tr>
            <tr>
                <td><b>Season</b></td>
                <td>{{ $sampleRequisition->season->season_name ?? null }}</td>
                <td><b>Dealing Merchant</b></td>
                <td>{{ $sampleRequisition->dealingMerchant->screen_name ?? null }}</td>
                <td><b>Product Dept.</b></td>
                <td>{{ $sampleRequisition->department->product_department }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <br>
    @php
        $isInputDateExists = (bool)array_filter($sampleRequisition->details->pluck('input_date')->toArray())
    @endphp
    <div class="col-md-12 m-t">
        <table>
            <thead>
            <tr>
                @if(isset($sampleRequisition->viewNo) && $sampleRequisition->viewNo == 2)
                    <th colspan="16" class="text-center">DETAILS</th>
                @else
                    <th colspan="15" class="text-center">DETAILS</th>
                @endif
            </tr>
            <tr>
                <th>Sample</th>
                <th>GMTS item</th>
                <th>SMV</th>
                <th>Gmts Color</th>
                @if(isset($sampleRequisition->viewNo) && $sampleRequisition->viewNo == 2)
                    <th>Gmts Size</th>
                @endif
                <th>Sample Req Qty</th>
                <th>BH Qty</th>
                <th>Submission Date</th>
                @if($isInputDateExists)
                    <th>Input Date</th>
                @endif
                <th>Expected Delivery Date</th>
                <th>Delivery Date</th>
                <th>Image</th>
            </tr>
            </thead>
            <tbody>
            @if($sampleRequisition->details && $sampleRequisition->details->count() > 0)
                @php $totalBhQty = 0; @endphp
                @foreach($sampleRequisition->details as $detail)
                    <tr>
                        <td>{{ $detail->sample->name }}</td>
                        <td>{{ $detail->gmtsItem->name }}</td>
                        <td>{{ $detail->smv }}</td>
                        <td>
                            @if(collect($detail->details)->count() > 0)
                                @foreach($detail->details as $item)
                                    <p class="list-item">{{ $item['gmts_color_name'] }}</p>
                                @endforeach
                            @endif
                        </td>
                        @if(isset($sampleRequisition->viewNo) && $sampleRequisition->viewNo == 2)
                            <td>
                                @if(collect($detail->details)->count() > 0)
                                    @foreach($detail->details as $item)
                                        <p class="list-item">{{ $item['gmts_size'] }}</p>
                                    @endforeach
                                @endif
                            </td>
                        @endif
                        <td class="text-right">
                            @if(collect($detail->details)->count() > 0)
                                @foreach($detail->details as $item)
                                    <p class="list-item">{{ $item['total_qty'] }}</p>
                                @endforeach
                            @endif
                        </td>
                        <td class="text-right">
                            @if(collect($detail->details)->count() > 0)
                                @foreach($detail->details as $item)
                                    <p class="list-item">{{ $item['bh_qty'] }}</p>
                                    @php $totalBhQty += $item['bh_qty']; @endphp
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $detail->submission_date ? \Carbon\Carbon::make($detail->submission_date)->toFormattedDateString() : null }}</td>
                        @if($isInputDateExists && $detail->input_date)
                            <td>{{ \Carbon\Carbon::make($detail->input_date)->toFormattedDateString() }}</td>
                        @endif
                        <td>{{ $detail->expected_delivery_date ? \Carbon\Carbon::make($detail->expected_delivery_date)->toFormattedDateString() : null }}</td>
                        <td>{{ $detail->delivery_date ? \Carbon\Carbon::make($detail->delivery_date)->toFormattedDateString() : null }}</td>
                        <td class="text-center">
                            @if($detail['image_path'] && File::exists('storage/'.$detail['image_path']))
                                <img
                                    src="{{asset('storage/'. $detail['image_path'])}}"
                                    alt="style image"
                                    width="250">
                            @else
                                <img src="{{ asset('images/no_image.jpg') }}" height="50" width="50"
                                     alt="no image">
                            @endif
                            <img src="" alt="">
                        </td>
                    </tr>
                @endforeach
                <tr style="background-color: gainsboro">

                    @if(isset($sampleRequisition->viewNo) && $sampleRequisition->viewNo == 2)
                        <td class="text-right" colspan="5"><b>Total</b></td>
                    @else
                        <td class="text-right" colspan="4"><b>Total</b></td>
                    @endif
                    <td class="text-right">
                        <b>{{ number_format($sampleRequisition->details->sum('required_qty'), 2) }}</b>
                    </td>
                    <td class="text-right">
                        <b>{{ number_format($totalBhQty, 2) }} </b>
                    </td>
                    <td colspan="4"></td>
                </tr>
            @else
                <tr>
                    <td colspan="16"><b>No Data Found</b></td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <br>
    <div class="col-md-12 m-t">
        <table>
            <thead>
            <tr>
                <th colspan="15" class="text-center">FABRIC DETAILS</th>
            </tr>
            <tr>
                <th>GMTS item</th>
                <th>Sample</th>
                <th>Body Part</th>
                <th>Body Part Type</th>
                <th>Nature</th>
                <th>Color Type</th>
                {{--                <th>Description</th>--}}
                <th>Source</th>
                <th>Fabric Composition</th>
                <th>DIA Type</th>
                <th>GSM</th>
                <th>GMTS Color</th>
                @if(isset($sampleRequisition->viewNo) && $sampleRequisition->viewNo == 2)
                    <th>LD</th>
                @endif
                <th>Sensitivity</th>
                {{--                <th>Color</th>--}}
                <th>UOM</th>
                <th>Avg Grey Qty</th>
                <th>Total Qty</th>
                <th>Total Amount</th>
                @if(isset($sampleRequisition->viewNo) && $sampleRequisition->viewNo == 2)
                    <th>Add. Notes</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if($sampleRequisition->fabrics && count($sampleRequisition->fabrics) > 0)
                @foreach($sampleRequisition->fabrics as $fabric)
                    {{-- @php
                        dump($fabric);
                    @endphp --}}
                    <tr>
                        <td>{{ $fabric->gmtsItem->name }}</td>
                        <td>{{ $fabric->sample->name }}</td>
                        <td>{{ $fabric->bodyPart->name }}</td>
                        <td>{{ $fabric->bodyPart->type }}</td>
                        <td>{{ $fabric->fabricNature->name }}</td>
                        <td>{{ $fabric->colorType->color_types }}</td>
                        <td>{{ $fabric->fabric_source }}</td>
                        <th>{{ $fabric->fabric_composition_value }}</th>
                        <td>{{ $fabric->dia_type }}</td>
                        <td>{{ $fabric->gsm }}</td>
                        <td>{{ $fabric->gmts_color_string }}</td>
                        @if(isset($sampleRequisition->viewNo) && $sampleRequisition->viewNo == 2)
                            <td>{{$fabric->ld_no ?? ''}}</td>
                        @endif
                        <td>{{ $fabric->sensitivity_value }}</td>
                        <td>{{ $fabric->umo_value }}</td>
                        <td class="text-right">{{ number_format($fabric->req_qty, 2) }}</td>
                        <td class="text-right">{{ number_format($fabric->total_qty, 2) }}</td>
                        <td class="text-right">{{ number_format($fabric->total_amount, 2) }}</td>
                        @if(isset($sampleRequisition->viewNo) && $sampleRequisition->viewNo == 2)
                            <td>{{ $fabric->remarks }}</td>
                        @endif
                    </tr>
                @endforeach
                <tr style="background-color: gainsboro">
                    @if(isset($sampleRequisition->viewNo) && $sampleRequisition->viewNo == 2)
                        <td class="text-right" colspan="15"><b>Total</b></td>
                        <td class="text-right">
                            <b>
                                {{ round($sampleRequisition->fabrics->sum('total_qty'), 2) }}
                            </b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($sampleRequisition->fabrics->sum('total_amount'), 2) }}</b>
                        </td>
                        <td></td>
                    @else
                        <td class="text-right" colspan="14"><b>Total</b></td>
                        <td class="text-right">
                            <b>{{ number_format($sampleRequisition->fabrics->sum('total_amount'), 2) }}</b>
                        </td>
                    @endif
                </tr>
            @else
                <tr>
                    <td colspan="17"><b>No Data Found</b></td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <br>
    <div class="col-md-12 m-t">
        <table>
            <thead>
            <tr>
                <th colspan="11" class="text-center">REQUIRED ACCESSORIES</th>
            </tr>
            <tr>
                <th>GMTS item</th>
                <th>Sample</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Brand/Supp. Ref</th>
                <th>UOM</th>
                <th>Req Qty</th>
                <th>Total Qty</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @if($sampleRequisition->accessories && $sampleRequisition->accessories->count() > 0)
                @foreach($sampleRequisition->accessories as $accessories)
                    <tr>
                        <td>{{ $accessories->garmentsItem->name }}</td>
                        <td>{{ $accessories->sample->name }}</td>
                        <td>{{ $accessories->item->item_group }}</td>
                        <td>{{ $accessories->description }}</td>
                        <td>{{ $accessories->brand_sup_ref }}</td>
                        <td>{{ $accessories->uom->unit_of_measurement }}</td>
                        <td class="text-right">{{ number_format($accessories->req_qty, 2) }}</td>
                        <td class="text-right">{{ number_format($accessories->total_qty, 2) }}</td>
                        <td class="text-right">{{ number_format($accessories->rate, 2) }}</td>
                        <td class="text-right">{{ number_format($accessories->amount, 2) }}</td>
                        <td>{{ $accessories->remarks }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: gainsboro">
                    <td class="text-right" colspan="9"><b>Total</b></td>
                    <td class="text-right">
                        <b>{{ number_format(collect($sampleRequisition->accessories)->sum('amount'), 2) }}</b>
                    </td>
                    <td></td>
                </tr>
            @else
                <tr>
                    <td colspan="11"><b>No Data Found</b></td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
