<div class="row">
    <div id="mainTable" class="col-sm-10 col-sm-offset-1 m-t">
        <table>
            <thead>
                <tr>
                    <th>Unique ID</th>
                    <th>Issue Challan No</th>
                    <th>Company Name</th>
                    <th>Trims Issue Unique ID</th>
                    <th>Receive Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $sampleTrimsReceive->unique_id ?? null }}</td>
                    <td>{{ $sampleTrimsReceive->issue_challan_no ?? null }}</td>
                    <td>{{ $sampleTrimsReceive->factory->factory_name ?? null }}</td>
                    <td>{{ $sampleTrimsReceive->trims_issue_unique_id ?? null }}</td>
                    <td>{{ $sampleTrimsReceive->receive_date ?? null }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="detailsTable" class="col-sm-12 m-t">
        <table>
            <thead>
            <tr>
                <th colspan="5" class="text-center">DETAILS</th>
            </tr>
            <tr>
                <th>Description</th>
                <th>Color</th>
                <th>Remarks</th>
                <th>Req Qty</th>
                <th>Rcv Qty</th>
            </tr>
            </thead>
            <tbody>
                @if (collect($sampleTrimsReceive->trimsReceiveDetails)->isNotEmpty())
                @foreach ($sampleTrimsReceive->trimsReceiveDetails as $value)
                <tr>
                    <td>{{ $value->details['description'] ?? null }}</td>
                    <td>{{ $value->details['color'] ?? null }}</td>
                    <td>{{ $value->details['remarks'] ?? null }}</td>
                    <td>{{ $value->calculations['req_qty'] ?? null }}</td>
                    <td>{{ $value->calculations['rcv_qty'] ?? null }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="3" class="text-right">Total</th>
                    <td>{{ $sampleTrimsReceive->total_calculation['total_req_qty'] ?? null }}</td>
                    <td>{{ $sampleTrimsReceive->total_calculation['total_rcv_qty'] ?? null }}</td>
                </tr>
                @else
                <tr>
                    <td colspan="5" class="text-center">No Data Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
