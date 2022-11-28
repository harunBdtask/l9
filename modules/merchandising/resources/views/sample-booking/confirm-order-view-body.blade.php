<div>
    <center>
        <table style="border: 1px solid black;width: 25%;">
            <thead>
                <tr>
                    <td class="text-center">
                        <span style="font-size: 12pt; font-weight: bold;">Confirm Order</span>
                        <br>
                    </td>
                </tr>
            </thead>
        </table>
    </center>
    <br>
    @php
    $isRnd = request()->get('RnD');
    @endphp
    <div class="body-section" style="margin-top: 0px;">
        <div>
            <table>
                <tr>
                    <th>Booking No</th>
                    <td>{{ $sampleBookingConfirmOrder->booking_no }}</td>
                    <th>Fabric Source</th>
                    <td>{{ $sampleBookingConfirmOrder->fabric_source_value }}</td>
                    <th>Booking Date</th>
                    <td>{{ $sampleBookingConfirmOrder->booking_date }}</td>
                </tr>

                <tr>
                    <th>Supplier Name</th>
                    <td>{{ $sampleBookingConfirmOrder->supplier->name ?? '' }}</td>
                    <th>Dealing Merchant</th>
                    <td>{{ $sampleBookingConfirmOrder->dealingMerchant->team_name ?? '' }}</td>
                    <th>Delivery Date</th>
                    <td>{{ $sampleBookingConfirmOrder->delivery_date ?? ''}}</td>
                </tr>

                <tr>
                    <th>Address</th>
                    <td>{{ $sampleBookingConfirmOrder->supplier->address_1 ?? '' }}</td>
                    <th>Pay Mode</th>
                    <td>{{ $sampleBookingConfirmOrder->pay_mode_value }}</td>
                    <th>Fabric Nature</th>
                    <td>{{ $sampleBookingConfirmOrder->fabricNature->name ?? '' }}</td>
                </tr>

                <tr>
                    <th>Approval Status</th>
                    <td>{{ $sampleBookingConfirmOrder->attention }}</td>
                </tr>
            </table>
        </div>

        @if(count($sampleBookingConfirmOrderDetails) > 0)
        <div style="margin-top: 15px">
            <table>
                <tr>
                    @if (!$isRnd)
                    <th>Po No</th>
                    @endif
                    <th>Sample Name</th>
                    <th>Garment Item</th>
                    <th>Body Part</th>
                    <th>Body Part Type</th>
                    <th>Description</th>
                    <th>Fabric Nature</th>
                    <th>Color Type</th>
                    <th>Garments Color</th>
                    <th>Fabric Color</th>
                    <th>UOM</th>
                    <th>Required Qty</th>
                    <th>Process Loss (%)</th>
                    <th>Total Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Remark</th>
                </tr>

                @php
                $total_req_qty = 0;
                $total_qty = 0;
                $total_amount = 0;
                @endphp
                @foreach($sampleBookingConfirmOrderDetails as $detail)
                <tr>
                    @if (!$isRnd)
                    <td>{{ $detail['po_value'] }}</td>
                    @endif
                    <td>{{ $detail['sample_name'] }}</td>
                    <td>{{ $detail['gmts_item'] }}</td>
                    <td>{{ $detail['body_part'] }}</td>
                    <td>{{ $detail['body_part_type'] }}</td>
                    <td>
                        {{ $detail['fabric_description'] ? $detail['fabric_description'].', ' : '' }}
                        GSM-{{ $detail['gsm'] ? $detail['gsm'].', ' : '' }}
                        Dia-{{ $detail['dia'] }}
                    </td>
                    <td>{{ $detail['fabric_nature'] }}</td>
                    <td>{{ $detail['color_type'] }}</td>
                    <td>{{ $detail['gmts_color'] }}</td>
                    <td>{{ $detail['gmts_color'] }}</td>
                    <td>{{ $detail['uom'] }}</td>
                    <td class="text-right">{{ $detail['required_qty'] }}</td>
                    <td>{{ $detail['process_loss'] }}</td>
                    <td class="text-right">{{ $detail['total_qty'] }}</td>
                    <td>{{ $detail['rate'] }}</td>
                    <td class="text-right">{{ $detail['amount'] }}</td>
                    <td>{{ $detail['remarks'] }}</td>
                </tr>
                @php
                $total_req_qty += $detail['required_qty'];
                $total_qty += $detail['total_qty'];
                $total_amount += $detail['amount'];
                @endphp
                @endforeach
                <tr>
                    <td colspan="{{ $isRnd ? 10 : 11 }}" class="text-center"><b>Total</b></td>
                    <td class="text-right">{{ $total_req_qty }}</td>
                    <td></td>
                    <td class="text-right">{{ $total_qty }}</td>
                    <td></td>
                    <td class="text-right">{{ $total_amount }}</td>
                    <td></td>
                </tr>
            </table>
        </div>
        @endif
    </div>

    <div style="margin-top: 16mm">
        @php
        $numberFormatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
        $inword =ucwords($numberFormatter->format($sampleBookingConfirmOrderDetails->sum('amount')));
        @endphp
        <span><b>Total Booking Amount: {{ $sampleBookingConfirmOrderDetails->sum('amount') }}</b></span><br>
        <span><b>In Words: {{ $inword  }}</b> </span>
    </div>
</div>