<style>
    table thead {
        display: table-row-group;
    }

    table, tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
    }
</style>

<div>
    <div style="padding-top: 50px;">
        <table class="borderless">
            <thead>
            <tr>
                <td class="text-left">
                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                    {{ factoryAddress() }}
                    <br>
                </td>
                <th class="text-right" style="text-align: right;">
                    Order No: <b>{{optional($order)->wo_no ?? ''}}</b><br>
                    Order Date: <b>{{optional($order)->wo_date ?? ''}}</b><br>
                </th>
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
                    <span style="font-size: 12pt; font-weight: bold;">Yarn Purchase Order</span>
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
                <th>Company Name:</th>
                <td>{{optional($order)->factory->factory_name  ?? ''}}</td>
                <th>Buyer Name:</th>
                <td>{{optional($order)->buyer->name ?? ''}}</td>
                <th>Supplier Name:</th>
                <td>{{optional($order)->supplier->name ?? ''}}</td>
            </tr>
            <tr>
                <th>Style Name:</th>
                <td>{{optional(collect($order->details))->first()->style_name  ?? ''}}</td>
                <th>Wo Date:</th>
                <td>{{ \Carbon\Carbon::parse(optional($order)->wo_date)->format('d M Y') }}</td>
                <th>Delivery Date:</th>
                <td>{{ \Carbon\Carbon::parse(optional($order)->delivery_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Pay Mode:</th>
                <td>{{ $order->pay_mode_value }}</td>
                <th>Source:</th>
                <td>{{ $order->source_value }}</td>
                <th>WO Basis:</th>
                <td>{{ $order->wo_basic_value }}</td>
            </tr>
            <tr>
                <th>Attention:</th>
                <td>{{ $order->attention }}</td>
                <th>Remarks:</th>
                <td>{{ $order->remarks }}</td>
                <th>Currency:</th>
                <td>{{ $order->currency }}</td>
            </tr>
        </table>
        <br>
        <br>
        @if(isset($order->details))
            <table class="reportTable">
                <tr>
                    <th colspan="8" class="text-center"><b>Yarn Purchase Order Details</b></th>
                </tr>
                <tr>
                    <th>Yarn Description</th>
                    <th>Total WO Qty</th>
                    <th>UOM</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Delivery Start Date</th>
                    <th>Delivery End Date</th>
                    <th>Remarks</th>
                </tr>

                @foreach($order->details as $key => $details)
                    <tr>
                        @php
                            $totalWorkOrderQty = (($details['process_loss'] * $details['wo_qty']) / 100)
                                                    + $details['wo_qty'];
                        @endphp
                        <td>
                            {{ $details['yarnCount']['yarn_count'] ?? '' }}
                            {{ $details['yarn_color'] ? ' - '. $details['yarn_color'] : '' }}
                            {{ $details['yarnComposition']['yarn_composition'] ? ' - '. $details['yarnComposition']['yarn_composition'] : '' }}
                            {{ $details['percentage'] ? ' - '. $details['percentage'] : '' }}
                            {{  $details['yarnType']['yarn_type'] ? ' - '. $details['yarnType']['yarn_type'] :  '' }}
                        </td>
                        <td style="text-align: right">{{ $totalWorkOrderQty ?? '' }}</td>
                        <td>{{ $details['unitOfMeasurement']['unit_of_measurement'] ?? '' }}</td>
                        <td style="text-align: right">{{ $details['rate'] ?? '' }}</td>
                        <td style="text-align: right">{{ number_format($details['total_amount'], 2) ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($details['delivery_start_date'])->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($details['delivery_end_date'])->format('d M Y') }}</td>
                        <td>{{ $details['remarks'] ?? '' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-right"><b>Total</b></td>
                    @php $total = $order->details->sum('total_amount'); @endphp
                    <td style="text-align: right"><b>{{ number_format($total, 2)  }}</b></td>
                    <td colspan="3"/>
                </tr>
            </table>
        @endif
        <br>
        <div style="margin-top: 16mm">
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
    </div>
    <br>
    <br>
    @include('skeleton::reports.downloads.signature')
</div>
