<div class="row">
    <div id="mainTable" class="col-sm-10 col-sm-offset-1 m-t">
        <table>
            <thead>
                <tr>
                    <th>TNA Unique ID</th>
                    <th>Requisition ID</th>
                    <th>Buyer Name</th>
                    <th>Style Name</th>
                    <th>Booking NO</th>
                    <th>Control / Ref. NO</th>
                    <th>Total Lead Time</th>
                    <th>Company Name</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $sampleTNA->unique_id ?? null }}</td>
                    <td>{{ $sampleTNA->requisition_id ?? null }}</td>
                    <td>{{ $sampleTNA->buyer->name }}</td>
                    <td>{{ $sampleTNA->style_name ?? null }}</td>
                    <td>{{ $sampleTNA->booking_no ?? null }}</td>
                    <td>{{ $sampleTNA->control_ref_no ?? null }}</td>
                    <td>{{ $sampleTNA->total_lead_time ?? null }}</td>
                    <td>{{ $sampleTNA->factory->factory_name ?? null }}</td>
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
                <th>Operations Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Day</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
                @if ($sampleTNA->items)
                @foreach ($sampleTNA->items as $value)
                <tr>
                    <td>{{ $value['operation_name'] ?? null }}</td>
                    <td>
                        {{ $value['start_date'] ? \Carbon\Carbon::make($value['start_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>
                        {{ $value['end_date'] ? \Carbon\Carbon::make($value['end_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>{{ $value['total_day'] ?? null }}</td>
                    <td>{{ $value['remarks'] ?? null }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="3" class="text-right">Total</th>
                    <td>{{ $sampleTNA->total_calculation['in_total'] ?? null }}</td>
                    <td></td>
                </tr>
                @else
                <tr>
                    <td colspan="5" class="text-center">No Data Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
