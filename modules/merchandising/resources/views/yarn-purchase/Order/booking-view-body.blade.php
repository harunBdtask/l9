<style>
    table thead {
        display: table-row-group;
    }

    table, tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
    }
</style>

<div>
    <center>
        <table style="border: 1px solid black;width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Yarn Booking</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
    <br>
    <div class="body-section" style="margin-top: 0px;">
        <table class="borderless">
            <tr>
                <th>Date:</th>
                <td style="min-width: 400px">{{ \Carbon\Carbon::parse(optional($order)->wo_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>To:</th>
                <td>{{ $order->attention }}</td>
                <td></td>
                <td></td>
                <td>YBWO No:</td>
                <td>{{ $order->wo_no }}</td>
            </tr>
            <tr>
                <th>From:</th>
                <td>{{ $order->createdBy->screen_name }}</td>
            </tr>
            <tr>
                <th>Sub:</th>
                <td>Yarn Booking for <u>{{ $order->buyer->name }}</u> confirmed
                    order {{ $order->buyer->name }} Inquiry No
                    <b><u>{{ optional(collect($order->details))->first()->style_name  ?? '' }}</u></b>
                </td>
                <th></th>
                <td style="min-width: 200px"></td>
                <th style="border: 1px solid black">Yarn Need to be In-housed within</th>
                <td style="border: 1px solid black">
                    {{
                        \Carbon\Carbon::parse(optional($order)->delivery_date)->format('d M Y')
                    }}
                </td>
            </tr>
        </table>
        <br>

        <p>Dear Sir,</p>
        <p>
            Please find below Yarn requirement for Order
            with {{ $order->buyer->name }} Inquiry
            No <b><u>{{ optional(collect($order->details))->first()->style_name  ?? '' }}</u></b>.
        </p>


        @if(isset($order->details))
            <table class="reportTable">
                <tr>
                    <th>SL No</th>
                    <th>Unique No</th>
                    <th>Style / Order No</th>
                    <th>Fabric Desc.</th>
                    <th>Order Qty/Pcs</th>
                    <th>Yarn Count</th>
                    <th>Yarn Desc.</th>
                    <th>Yarn Qty</th>
                    <th>UOM</th>
                    @if(request('type') == 'rate')
                        <th>Rate</th>
                        <th>Amount</th>
                    @endif
                    <th>Delivery Start Date</th>
                    <th>Delivery End Date</th>
                    <th>Remarks</th>
                </tr>

                @foreach($order->details as $key => $details)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $details->unique_id }}</td>
                        <td>{{ $details->style_name }}</td>
                        <td>{{ $details->fabric_description }}</td>
                        <td>{{ $details->order->pq_qty_sum }}</td>
                        <td>{{ $details['yarnCount']['yarn_count'] ?? '' }}</td>
                        <td>
                            {{ $details['yarn_color'] ?? '' }}
                            {{ $details['yarnComposition']['yarn_composition']
                                ? ' - '. $details['yarnComposition']['yarn_composition'] : '' }}
                            {{ $details['percentage'] ? ' - '. $details['percentage'] : '' }}
                            {{  $details['yarnType']['yarn_type'] ? ' - '. $details['yarnType']['yarn_type'] :  '' }}
                        </td>
                        @php
                            $totalWorkOrderQty = (($details['process_loss'] * $details['wo_qty']) / 100)
                                                    + $details['wo_qty'];
                        @endphp
                        <td style="text-align: right">{{ $totalWorkOrderQty ?? 0 }}</td>
                        <td>{{ $details['unitOfMeasurement']['unit_of_measurement'] ?? '' }}</td>
                        @if(request('type') == 'rate')
                            <td style="text-align: right">{{ $details['rate'] ?? '' }}</td>
                            <td style="text-align: right">{{ number_format($details['total_amount'], 2) ?? '' }}</td>
                        @endif
                        <td>{{ \Carbon\Carbon::parse($details['delivery_start_date'])->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($details['delivery_end_date'])->format('d M Y') }}</td>
                        <td>{{ $details['remarks'] ?? '' }}</td>
                    </tr>
                @endforeach
                @if(request('type') == 'rate')
                    <tr>
                        <td colspan="6" class="text-right"><b>Total</b></td>
                        @php $total = $order->details->sum('total_amount'); @endphp
                        <td style="text-align: right"><b>{{ number_format($total, 2)  }}</b></td>
                        <td colspan="7"/>
                    </tr>
                @endif
            </table>
        @endif
        <br>
        <div>
            <table class="borderless">
                <tr>
                    <td style="width: 65%"></td>
                    <td style="border: 1px solid black">Garments production schedule</td>
                    <td style="border: 1px solid black">{{ $order->garment_production_schedule }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="border: 1px solid black" colspan="2">{{ $order->order_note }}</td>
                </tr>
            </table>
        </div>

    </div>
    <br>
    <br>

    <table class="borderless">
        <tbody>
        <tr>
            <td class="text-center"><u>Prepared By</u></td>
            <td class='text-center'><u>Checked By</u></td>
            <td class='text-center'><u>Checked By</u></td>
            <td class='text-center'><u>Authenticate By</u></td>
            <td class="text-center"><u>Approved By</u></td>
        </tr>
        <tr>
            <td class="text-center"><u>(Merchandiser)</u></td>
            <td class='text-center'><u>(Merchandiser)</u></td>
            <td class='text-center'><u>(Merchandiser)</u></td>
            <td class='text-center'><u>(VP. M&M)</u></td>
            <td class="text-center"><u>(Director M&M)</u></td>
        </tr>
        </tbody>
    </table>


    {{--    @include('skeleton::reports.downloads.signature')--}}
</div>
