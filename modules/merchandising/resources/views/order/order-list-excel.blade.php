<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr>
                <th class="text-center" colspan="14">Order List</th>
            </tr>
            <tr><td> </td></tr>
            <tr class="table-header">
                <th>Sl</th>
                <th>Company</th>
                <th>Buyer</th>
                <th>Unique Id</th>
                <th>Style</th>
                <th>Style Qty.</th>
                <th>Category</th>
                <th>UOM</th>
                <th>Season</th>
                <th>SMV</th>
                <th>PO No.</th>
                <th>Leader</th>
                <th>Currency</th>
                <th>Comm. File No</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $key => $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->factory->factory_short_name ?? '' }}</td>
                    <td>{{ $order->buyer->name ?? '' }}</td>
                    <td>{{ $order->job_no }}</td>
                    <td>{{ $order->style_name }}</td>
                    <td>{{ $order->pq_qty_sum ?? 0 }}</td>
                    <td>{{ $order->productCategory->category_name ?? '' }}</td>
                    <td>{{ $order->unit_of_measurement }}</td>
                    <td>{{ $order->season->season_name }}</td>
                    <td>{{ $order->smv }}</td>
                    <td>{{ $order->po_no }}</td>
                    <td>{{ $order->teamLeader->first_name ?? '' }} {{ $order->teamLeader->last_name ?? '' }}</td>
                    <td>{{ $order->currency->currency_name  ?? '' }}</td>
                    <td>{{ $order->common_file_name  ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-center p-a" colspan="14">No Data Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
