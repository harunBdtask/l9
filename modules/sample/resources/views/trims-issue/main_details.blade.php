<div class="row">
    <div id="mainTable" class="col-sm-10 col-sm-offset-1 m-t">
        <table>
            <thead>
                <tr>
                    <th>Unique ID</th>
                    <th>Issue Challan No</th>
                    <th>Company Name</th>
                    <th>Issue Basis</th>
                    <th>Requisition ID</th>
                    <th>Buyer Name</th>
                    <th>Style Name</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $issueBasis = [];
                    if ($sampleTrimsIssue->issue_basis_id) {
                        $issueBasis = collect($sampleTrimsIssue->issueBasis)->where('id', $sampleTrimsIssue->issue_basis_id)->first();
                    }
                @endphp
                <tr>
                    <td>{{ $sampleTrimsIssue->unique_id ?? null }}</td>
                    <td>{{ $sampleTrimsIssue->issue_challan_no ?? null }}</td>
                    <td>{{ $sampleTrimsIssue->factory->factory_name ?? null }}</td>
                    <td>{{ $issueBasis['text'] ?? null }}</td>
                    <td>{{ $sampleTrimsIssue->sample->requisition_id ?? null }}</td>
                    <td>{{ $sampleTrimsIssue->buyer->name }}</td>
                    <td>{{ $sampleTrimsIssue->style_name ?? null }}</td>
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
            </tr>
            </thead>
            <tbody>
                @if (collect($sampleTrimsIssue->trimsIssueDetails)->isNotEmpty())
                @foreach ($sampleTrimsIssue->trimsIssueDetails as $value)
                <tr>
                    <td>{{ $value->details['description'] ?? null }}</td>
                    <td>{{ $value->details['color'] ?? null }}</td>
                    <td>{{ $value->details['remarks'] ?? null }}</td>
                    <td>{{ $value->calculations['req_qty'] ?? null }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="3" class="text-right">Total</th>
                    <td>{{ $sampleTrimsIssue->total_calculation['total_req_qty'] ?? null }}</td>
                </tr>
                @else
                <tr>
                    <td colspan="5" class="text-center">No Data Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
