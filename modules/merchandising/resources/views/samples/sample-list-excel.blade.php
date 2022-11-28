<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr>
                <th class="text-center" colspan="8">Sample List</th>
            </tr>
            <tr><td> </td></tr>
            <tr class="table-header">
                <th>SL</th>
                <th>Year</th>
                <th>Requisition Id</th>
                <th>Buyer Name</th>
                <th>Style Name</th>
                <th>Product Department</th>
                <th>Dealing Merchant</th>
                <th>Sample Stage</th>
            </tr>
            </thead>
            <tbody>
            @forelse($samples as $key => $sample)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sample->year }}</td>
                <td>{{ $sample->requisition_id }}</td>
                <td>{{ $sample->buyer->name }}</td>
                <td>{{ $sample->style_name }}</td>
                <td>{{ $sample->department->product_department }}</td>
                <td>{{ $sample->merchant->full_name }}</td>
                <td>{{ $sample->stage }}</td>
            </tr>
            @empty
            <tr>
                <td class="text-center p-a" colspan="8">No Data Found</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
