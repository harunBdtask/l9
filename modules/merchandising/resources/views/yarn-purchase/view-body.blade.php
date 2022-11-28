<div>
    <center>
        <table style="border: 1px solid black;width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Yarn Purchase Requisition</span>
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
                <th>Requisition Date</th>
                <td>{{optional($requisition)->requisition_date  ?? ''}}</td>
                <th>Required Date</th>
                <td>{{optional($requisition)->required_date ?? ''}}</td>
            </tr>
            <tr>
                <th>Factory Name</th>
                <td>{{optional($requisition)->factory->factory_name  ?? ''}}</td>
                <th>Requisition No</th>
                <td>{{optional($requisition)->requisition_no ?? ''}}</td>
            </tr>
            <tr>
                <th>Dealing Merchant</th>
                <td>{{optional($requisition)->merchant->screen_name  ?? ''}}</td>
                <th>Source</th>
                <td>{{optional($requisition)->source_value ?? ''}}</td>
            </tr>
        </table>
        <br>
        <br>
        @if(isset($requisition->details))
            <table class="reportTable">
                <tr>
                    <th colspan="14" class="text-center"><b>Yarn Purchase Requisition Details</b></th>
                </tr>
                <tr>
                    <th>Unique Id</th>
                    <th>Buyer Name</th>
                    <th>Style Name</th>
                    <th>Yarn Count</th>
                    <th>Yarn Color</th>
                    <th>%</th>
                    <th>Yarn Type</th>
                    <th>UOM</th>
                    <th>Requisition Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Yarn In-House-Date</th>
                    <th>Remarks</th>
                </tr>
                @php
                    $total_req_qty = 0;
                    $total_amount = 0;
                @endphp
                @foreach($requisition->details as $key => $details)
                    @php
                        $total_req_qty += $details['requisition_qty'];
                        $total_amount += $details['amount'];
                    @endphp
                    <tr>
                        <td>{{ $details['unique_id'] ?? '' }}</td>
                        <td>{{ $details['buyer']['name'] ?? '' }}</td>
                        <td>{{ $details['style_name'] ?? '' }}</td>
                        <td>{{ $details['yarnCount']['yarn_count'] ?? '' }}</td>
                        <td>{{ $details['yarn_color'] ?? '' }}</td>
                        <td class="text-right">{{ $details['percentage'] ?? '' }}</td>
                        <td>{{ $details['yarnType']['yarn_type'] ?? '' }}</td>
                        <td>{{ $details['unitOfMeasurement']['unit_of_measurement'] ?? '' }}</td>
                        <td class="text-right">{{ round($details['requisition_qty']) ?? 0 }}</td>
                        <td class="text-right">{{ $details['rate'] ?? 0 }}</td>
                        <td class="text-right">{{ number_format($details['amount'], 4) ?? 0 }}</td>
                        <td>{{ $details['yarn_in_house_date'] ?? '' }}</td>
                        <td>{{ $details['remarks'] ?? '' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8" class="text-right"><b>Total</b></td>
                    <td class="text-right"><b>{{ round($total_req_qty) }}</b></td>
                    <td></td>
                    <td class="text-right"><b>{{ number_format($total_amount, 4) }}</b></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        @endif
        <br>
        @if(count($requisition->terms_condition)>0)
            <table class="reportTable">
                <tr>
                    <th>#</th>
                    <th class="text-center">Terms & Conditions</th>
                </tr>
                @foreach($requisition->terms_condition as $key => $terms)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $terms }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

@include('skeleton::reports.downloads.signature')
