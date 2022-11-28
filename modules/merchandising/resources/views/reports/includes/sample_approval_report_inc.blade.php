@if($sample_approval_report_data != null)
    @foreach ($sample_approval_report_data->getCollection() as $data)
        @php
            $master_order_id = $data->master_order_id;
            $artwork_no = $data->artwork_no ?? 'N/A';
            $buyer_name = $data->buyer_name ?? 'N/A';
            $master_order = $data->master_order_no ?? 'N/A';
            $style_name = $data->style_name ?? 'N/A';
            $submission_date = date("d/m/Y", strtotime($data->submission_date)) ?? 'N/A';
            $approval_date = date("d/m/Y", strtotime($data->approval_date)) ?? 'N/A';
            $approval_status = $data->approval_status ?? 'N/A';
            $approval_remarks = $data->approval_remarks ?? 'N/A';
            $sample_id = $data->sample_development_id;
            $po = $data->po ?? 'N/A';
            $po_id = $data->po_id;
        @endphp
        <tr>
            <td>{{$artwork_no}}</td>
            <td>{{$buyer_name}}</td>
            <td>{{$master_order}}</td>
            <td>{{$style_name}}</td>
            <td>{{$po}}</td>
            <td><a href="{{url('storage/artwork_files/' . $data->artwork_image)}}" target='_blank' class='text-success   btn-outline b-success'>View Attachment</a></td>
            <td>{{$submission_date}}</td>
            <td>{{$approval_date}}</td>
            <td>{{$approval_status}}</td>
            <td>{{$approval_remarks}}</td>
            <td><a class="btn btn-success btn-xs" href="{{url('/sample-approval-report-data-details/' . $sample_id . '/' . $master_order_id . '/' . $po_id)}}" target='_blank'>Details</a></td>
        </tr>
    @endforeach
    @if($sample_approval_report_data != null && $sample_approval_report_data->total() > 15)
        <tr>
            <td colspan="11" align="center">{{ $sample_approval_report_data->appends(request()->except('page'))->links() }}</td>
        </tr>
    @endif
@else
    <tr>
        <td colspan="11" align="center">No data</td>
    </tr>
@endif