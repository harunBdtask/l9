<div style="padding-top: 50px;">
    <div class="body-section" style="margin-top: 0px;">
        <table class="borderless">
            <thead>
            <tr>
                <td class="text-left">
                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                    {{ factoryAddress() }}
                    <br>
                </td>
                <td class="text-right" style="text-align: right;">
                    Booking No: <b> {{ $workOrder->unique_id ?? '' }}</b><br>
                    Booking Date: <b> {{ $workOrder->booking_date ? date('j-M-Y', strtotime($workOrder->booking_date)) : '' }}</b><br>
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
                        style="font-size: 12pt; font-weight: bold;">{{ 'Embellishment Work Order' }}</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
        <br>


        <table class="borderless">
            <tr>
                <th colspan="6" style="text-align: center"><b><u>PURCHASE ORDER</u></b></th>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <th style="width: 250px">SUPPLIER:</th>
                <td>{{ isset($workOrder) ? optional($workOrder->supplier)->name : '' }}</td>
                {{--            <th style="width: 250px;">EMBL. TYPE:</th>--}}
                {{--            <td>{{ $workOrder['process'] ?? '' }}</td>--}}
                <th style="width: 250px">BOOKING AMOUNT:</th>
                <td>{{'$ '.number_format($workOrder['total'],4)}}</td>
            </tr>
            <tr>
                <th style="width: 250px">BUYER:</th>
                <td>{{ isset($workOrder) ? optional($workOrder->buyer)->name : '' }}</td>
                {{--            <th style="width: 250px;">PROCESS NAME:</th>--}}
                {{--            <td>{{ $workOrder['emblType'] ?? '' }}</td>--}}
                <th style="width: 250px">BOOKING DATE:</th>
                <td>{{ $workOrder->booking_date ? date('j-M-Y', strtotime($workOrder->booking_date)) : '' }}</td>
            </tr>
            <tr>
                <th style="width: 250px">ATTN:</th>
                <td>{{ $workOrder->attention ?? '' }}</td>
                {{--            <th style="width: 250px;"></th>--}}
                {{--            <td></td>--}}
                <th style="width: 250px">DELIVERY DATE:</th>
                <td>{{ $workOrder->delivery_date ? date('j-M-Y', strtotime($workOrder->delivery_date)) : '' }}</td>
            </tr>
            <tr>
                <th style="width: 250px">ORDER DATE:</th>
                <td>{{ $workOrder->booking_date ? date('j-M-Y', strtotime($workOrder->booking_date)) : '' }}</td>
                <th style="width: 250px">APPROVAL STATUS:</th>
                <td>{{ $workOrder->is_approved == 1 ? 'Approved' : 'Unapproved' }}</td>
            </tr>
            <tr>
                <th style="width: 250px">ISSUED BY:</th>
                <td>{{ $workOrder->issued_by ?? '' }}</td>
                <th style="width: 250px">APPROVED BY:</th>
                <td>{{ $workOrder->approved_by ?? '' }}</td>
            </tr>
        </table>

        @if(isset($workOrder) && count($workOrder->gmtsColorWiseWorkOrder) >0)
            <div style="margin-top: 15px;">
                <table>
                    <tr>
                        <td colspan="10" class="text-center"><b>Work Order Details(As Per Gmts Color)</b></td>
                    </tr>
                    <tr>
                        <th>SL</th>
                        <th>Style</th>
                        <th>Gmts Item</th>
                        <th>Embl. Name</th>
                        <th>Embl. Type</th>
                        <th>Body Part</th>
                        <th>Gmts Color</th>
                        <th>Total Qty</th>
                        <th>UOM</th>
                        <th>Rate</th>
                        <th>Total Amount</th>
                    </tr>
                    @php
                        $totalWoQty = 0;
                        $totalAmount = 0
                    @endphp
                    @foreach($workOrder->gmtsColorWiseWorkOrder as $index => $item)
                        @php
                            $amount = ($item['wo_total_qty'] * $item['rate']);
                            $totalWoQty += $item['wo_total_qty'];
                            $totalAmount += $amount
                        @endphp
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $item['style'] }}</td>
                            <td>{{ $item['gmts_item'] }}</td>
                            <td>{{ $item['embl_name'] }}</td>
                            <td>{{ $item['embl_type'] }}</td>
                            <td>{{ $item['body_part'] }}</td>
                            <td>{{ $item['gmts_color'] }}</td>
                            <td class="text-right">{{ round($item['wo_total_qty']) }}</td>
                            <td>{{ $workOrder['costing_per'] == 1 ? 'DZN' : 'PCS' }}</td>
                            <td class="text-right">{{ $item['rate'] }}</td>
                            <td class="text-right">{{ '$ '.number_format($amount, 4) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="7"><b>Total</b></td>
                        <td class="text-right"><b>{{ round($totalWoQty) }}</b></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"><b>{{ '$ '.number_format($totalAmount, 4) }}</b></td>
                    </tr>
                </table>
            </div>

        @endif
        @if(isset($workOrder) && count($workOrder->colorSizeSensitivity) >0)
            <div style="margin-top: 15px;">
                <table>
                    <tr>
                        <td colspan="12" class="text-center"><b>Color Size Sensitivity</b></td>
                    </tr>
                    <tr>
                        <th>SL</th>
                        <th>Style</th>
                        <th>Gmts. Item</th>
                        <th>Embl. Name</th>
                        <th>Embl. Type</th>
                        <th>Body Part</th>
                        <th>Gmts. Color</th>
                        <th>Gmts Size</th>
                        <th>WO Qty</th>
                        <th>UOM</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                    @php
                        $totalWoQty = 0;
                        $totalAmount = 0
                    @endphp
                    @foreach($workOrder->colorSizeSensitivity as $index => $item)
                        @php
                            $amount = ($item['wo_total_qty'] * $item['rate']);
                              $totalWoQty += $item['wo_total_qty'];
                              $totalAmount += $amount
                        @endphp
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $item['style'] }}</td>
                            <td>{{ $item['gmts_item'] }}</td>
                            <td>{{ $item['embl_name'] }}</td>
                            <td>{{ $item['embl_type'] }}</td>
                            <td>{{ $item['body_part'] }}</td>
                            <td>{{ $item['gmts_color'] }}</td>
                            <td>{{ $item['size'] }}</td>
                            <td class="text-right">{{ round($item['wo_total_qty']) }}</td>
                            <td>{{ $workOrder['costing_per'] == 1 ? 'DZN' : 'PCS' }}</td>
                            <td class="text-right">{{ $item['rate'] }}</td>
                            <td class="text-right">{{ '$ '.number_format($amount, 4) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="8"><b>Total</b></td>
                        <td class="text-right"><b>{{ round($totalWoQty) }}</b></td>
                        <td colspan="2"></td>
                        <td class="text-right"><b>{{ '$ '.number_format($totalAmount, 4) }}</b></td>
                    </tr>
                </table>
            </div>
        @endif

        @if(isset($workOrder) && count($workOrder->contrastColorWiseWorkOrder) >0)
            <div style="margin-top: 15px;">
                <table>
                    <tr>
                        <td colspan="11" class="text-center"><b>Contrast Color Sensitivity</b></td>
                    </tr>
                    <tr>
                        <th>SL</th>
                        <th>Style</th>
                        <th>Gmts. Item</th>
                        <th>Embl. Name</th>
                        <th>Embl. Type</th>
                        <th>Body Part</th>
                        <th>Gmts. Color</th>
                        <th>Gmts Size</th>
                        <th>WO Qty</th>
                        <th>UOM</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                    @php
                        $totalWoQty = 0;
                        $totalAmount = 0
                    @endphp
                    @foreach($workOrder->contrastColorWiseWorkOrder as $index => $item)
                        @php
                            $amount = ($item['wo_total_qty'] * $item['rate']);
                            $totalWoQty += $item['wo_total_qty'];
                            $totalAmount += $amount
                        @endphp
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $item['style'] }}</td>
                            <td>{{ $item['gmts_item'] }}</td>
                            <td>{{ $item['embl_name'] }}</td>
                            <td>{{ $item['embl_type'] }}</td>
                            <td>{{ $item['body_part'] }}</td>
                            <td>{{ $item['gmts_color'] }}</td>
                            <td>{{ $item['size'] }}</td>
                            <td class="text-right">{{ round($item['wo_total_qty']) }}</td>
                            <td>{{ $workOrder['costing_per'] == 1 ? 'DZN' : 'PCS' }}</td>
                            <td class="text-right">{{ $item['rate'] }}</td>
                            <td class="text-right">{{ '$ '.number_format($amount, 4) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="8"><b>Total</b></td>
                        <td class="text-right"><b>{{ round($totalWoQty) }}</b></td>
                        <td class="text-right" colspan="2"></td>
                        <td class="text-right"><b>{{ '$ '.number_format($totalAmount, 4) }}</b></td>
                    </tr>
                </table>
            </div>
        @endif

        @if(isset($workOrder) && count($workOrder->sizeSensitivity) >0)
            <div style="margin-top: 15px;">
                <table>
                    <tr>
                        <td colspan="11" class="text-center"><b>Size Sensitivity</b></td>
                    </tr>
                    <tr>
                        <th>SL</th>
                        <th>Style</th>
                        <th>Gmts. Item</th>
                        <th>Embl. Name</th>
                        <th>Embl. Type</th>
                        <th>Body Part</th>
                        <th>Gmts. Color</th>
                        <th>Gmts Size</th>
                        <th>WO Qty</th>
                        <th>UOM</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                    @php
                        $totalWoQty = 0;
                        $totalAmount = 0
                    @endphp
                    @foreach($workOrder->sizeSensitivity as $index => $item)
                        @php
                            $totalWoQty += $item['wo_total_qty'];
                            $amount = ($item['wo_total_qty'] * $item['rate']);
                            $totalAmount += $amount
                        @endphp
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $item['style'] }}</td>
                            <td>{{ $item['gmts_item'] }}</td>
                            <td>{{ $item['embl_name'] }}</td>
                            <td>{{ $item['embl_type'] }}</td>
                            <td>{{ $item['body_part'] }}</td>
                            <td>{{ $item['gmts_color'] }}</td>
                            <td>{{ $item['size'] }}</td>
                            <td class="text-right">{{ round($item['wo_total_qty']) }}</td>
                            <td>{{ $workOrder['costing_per'] == 1 ? 'DZN' : 'PCS' }}</td>
                            <td class="text-right">{{ number_format($item['rate'],4) }}</td>
                            <td class="text-right">{{ '$ '.number_format($amount, 4) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="8"><b>Total</b></td>
                        <td class="text-right"><b>{{ round($totalWoQty) }}</b></td>
                        <td class="text-right" colspan="2"></td>
                        <td class="text-right"><b>{{ '$ '.number_format($totalAmount, 4) }}</b></td>
                    </tr>
                </table>
            </div>
        @endif

        @if(isset($workOrder) && count($workOrder->noSensitivity) >0)
            <div style="margin-top: 15px;">
                <table>
                    <tr>
                        <td colspan="11" class="text-center"><b>No Sensitivity</b></td>
                    </tr>
                    <tr>
                        <th>SL</th>
                        <th>Style</th>
                        <th>Gmts. Item</th>
                        <th>Embl. Name</th>
                        <th>Embl. Type</th>
                        <th>Body Part</th>
                        <th>Gmts. Color</th>
                        <th>Gmts Size</th>
                        <th>WO Qty</th>
                        <th>UOM</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                    @php
                        $totalWoQty = 0;
                        $totalAmount = 0
                    @endphp
                    @foreach($workOrder->noSensitivity as $index => $item)
                        @php
                            $amount = ($item['wo_total_qty'] * $item['rate']);
                              $totalWoQty += $item['wo_total_qty'];
                              $totalAmount += $amount
                        @endphp
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $item['style'] }}</td>
                            <td>{{ $item['gmts_item'] }}</td>
                            <td>{{ $item['embl_name'] }}</td>
                            <td>{{ $item['embl_type'] }}</td>
                            <td>{{ $item['body_part'] }}</td>
                            <td>{{ $item['gmts_color'] }}</td>
                            <td>{{ $item['size'] }}</td>
                            <td class="text-right">{{ round($item['wo_total_qty']) }}</td>
                            <td>{{ $workOrder['costing_per'] == 1 ? 'DZN' : 'PCS' }}</td>
                            <td class="text-right">{{ $item['rate'] }}</td>
                            <td class="text-right">{{ '$ '.number_format($amount, 4) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="8"><b>Total</b></td>
                        <td class="text-right"><b>{{ round($totalWoQty) }}</b></td>
                        <td class="text-right" colspan="2"></td>
                        <td class="text-right"><b>{{ '$ '.number_format($totalAmount, 4) }}</b></td>
                    </tr>
                </table>
            </div>
        @endif

    </div>

    <div style="margin-top: 10mm">
        <table class="borderless">
            <tbody>
            <tr>
                <td class="text-left" style="width: 160px"><b>Total Booking Amount :</b></td>
                <td> {{number_format($workOrder['total'],4)." ".$workOrder['currency']}}</td>
            </tr>
            </tbody>
        </table>
        <table class="borderless">
            <tbody>
            <tr>
                <td class="text-left" style="width: 223px"><b>Total Booking Amount (In Words):</b></td>
                <td> {{$workOrder['totalInWords']." ".$workOrder['currency']}}</td>
            </tr>
            </tbody>
        </table>
        <table class="borderless">
            <tbody>
            <tr>
                <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
            </tr>
            @if(isset($workOrder))
                @foreach($workOrder->termsCondition as $item)
                    <tr>
                        <td style="width: 1000px">{{ '* '. $item->terms_name }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

    @include('skeleton::reports.downloads.signature')
    <style>
        footer {
            top: 190% !important;
        }
    </style>
</div>

