<div class="row">
    <div class="col-sm-12 table-responsive">
        <table class="reportTable">
            <thead>
            <tr style="background-color: aliceblue">
                <th colspan="4">Particulars</th>
                <th></th>
                <th colspan="9">Yarn Delivery</th>
            </tr>
            <tr style="background-color: aliceblue">
                <th>Buyer</th>
                <th>Style</th>
                <th>PO No</th>
                <th>Job No</th>
                <th>Int. PI No</th>
                <th>Issue Date</th>
                <th>Issue Challan</th>
                <th>Knitting Party</th>
                <th>Fab Type</th>
                <th>Yarn Des.</th>
                <th>LOT No</th>
                <th>Issue QTY</th>
                <th>Rate Per KG</th>
                <th>USD Value</th>
            </tr>
            </thead>
            <tbody>
            @forelse($reportData as $item)
                <tr style="height: 19px; text-align: left;">
                    <td>{{ $item['buyer_name'] }}</td>
                    <td>
                        <div style="width: 150px">{{ $item['style_name'] }}</div>
                    </td>
                    <td>{{ $item['po_no'] }}</td>
                    <td>{{ $item['issue']['buyer_job_no'] ?? '' }}</td>
                    <td>
                        <div style="width: 150px">
                        {{ $item['pi_no'] }}
                        </div>
                    </td>
                    <td>
                        <div style="width: 65px;">{{ date("d-m-Y", strtotime($item['issue_date'])) }}</div>
                    </td>
                    <td>{{ $item['issue_challan_no'] }}</td>
                    <td>
                        <div style="width: 105px">{{ $item['knitting_party'] }}</div>
                    </td>
                    <td>{{ $item['fab_type'] }}</td>
                    <td>
                        <div style="width: 150px">
                            {{ $item['yarn_count'] }},
                            {{ $item['yarn_composition'] }},
                            {{ $item['yarn_type'] }}
                            {{ $item['yarn_color'] ? ', '.$item['yarn_color'] : '' }}
                        </div>
                    </td>
                    <td>{{ $item['yarn_lot'] }}</td>
                    <td>{{ $item['issue_qty'] }}</td>
                    <td style="text-align: center;">{{ number_format($item['rate'], 4) }}</td>
                    <td style="text-align: right;">${{ number_format($item['usd_value'], 4) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14">No Data Found Here</td>
                </tr>
            @endforelse
            </tbody>
            @if(count($reportData))
                <tfoot>
                <tr>
                    <th colspan="10">Total</th>
                    <th></th>
                    <th style="text-align: right;">{{ collect($reportData)->sum('issue_qty') }}</th>
                    <th></th>
                    <th style="text-align: right;">${{ number_format(collect($reportData)->sum('usd_value'), 4) }}</th>
                </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
