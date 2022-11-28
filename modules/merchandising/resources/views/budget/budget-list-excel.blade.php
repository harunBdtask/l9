<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr class="text-center">
                <td colspan="20">Budget List</td>
            </tr>
            <tr class="table-header">
                <td><b>Sl</b></td>
                <td><b>Company Name</b></td>
                <td><b>Buyer</b></td>
                <td><b>Unique ID</b></td>
                <td><b>Style Name</b></td>
                <td><b>Job QTY.</b></td>
                <td><b>FOB</b></td>
                <td><b>Total Revenue</b></td>
                <td><b>Fabric Cost</b></td>
                <td><b>Trims/Accessories Cost</b></td>
                <td><b>CM Cost</b></td>
                <td><b>Wash, Test, Commercial and Others Cost</b></td>
                <td><b>Total Earnings</b></td>
                <td><b>Product Dept.</b></td>
                <td><b>UOM</b></td>
                <td><b>Incoterm Place</b></td>
                <td><b>Costing Date</b></td>
                <td><b>Approve Date</b></td>
                <td><b>Region</b></td>
                <td><b>Created By</b></td>
                <td><b>Create Date</b></td>
            </tr>
            </thead>
            <tbody>
            @forelse($budgets as $budget)
                @php
                    $fob = collect($budget->order->PurchaseOrders)->average('avg_rate_pc_set');
                    $revenue = collect($budget->order->PurchaseOrders)->map(function ($item) {
                            $rate = $item['avg_rate_pc_set'] ?? 0;
                            $qty = $item['po_quantity'] ?? 0;
                            return $rate * $qty;})->sum();
                    $po_quantity = collect($budget->order->PurchaseOrders)->map(function ($item) {
                           return $qty = $item['po_quantity'] ?? 0;})->sum();
                    $fabric_cost = $trims_cost = $total_cost = 0.00;
                    if(!empty($budget->costing['fabric_cost']))
                        {
                            if(!empty($budget->costing['fabric_cost']['budgeted_cost']))
                               {
                                    $fabric_cost = $budget->costing['fabric_cost']['budgeted_cost'] ?? 0.00;
                                }else{
                                $fabric_cost = 0.00;
                                }
                        }

                    if(!empty($budget->costing['trims_cost']))
                        {
                            if(!empty($budget->costing['trims_cost']['budgeted_cost']))
                                {
                                   $trims_cost = $budget->costing['trims_cost']['budgeted_cost'] ?? 0.00;
                               }else{
                                $trims_cost = 0.00;
                                }
                        }

                    if(!empty($budget->costing['total_cost']))
                     {
                         if(!empty($budget->costing['total_cost']['budgeted_cost']))
                             {
                                 $total_cost = $budget->costing['total_cost']['budgeted_cost'] ?? 0.00;
                                }else{
                                $total_cost = 0.00;
                                }
                        }

                    $cm_cost = 0.00;
                    if (!empty($budget->costing['cm_cost']) && !empty($budget->costing['cm_cost']['budgeted_cost'])) {
                        $cm_cost = $budget->costing['cm_cost']['budgeted_cost'] ?? 0.00;
                    }
                @endphp
                <tr>
                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $budget->factory->factory_short_name }}</td>
                    <td>{{ $budget->buyer->name }}</td>
                    <td>{{ $budget->job_no }}</td>
                    <td>{{ $budget->style_name }}</td>
                    <td>{{ $budget->job_qty }}</td>
                    <td>{{ $fob ?? 0.00}}</td>
                    <td>{{ $revenue ?? 0.00 }}</td>
                    <td>{{ $fabric_cost ?? 0.00}}</td>
                    <td>{{ $trims_cost ?? 0.00}}</td>
                    <td>{{ $cm_cost ?? 0.00}}</td>
                    <td>{{ $total_cost - (($fabric_cost + $trims_cost + $cm_cost) ?? 0.00)}}</td>
                    <td>{{ $revenue - ($fob *  $po_quantity) }}</td>
                    <td>{{ $budget->productDepartment->product_department ?? 'N/A'  }}</td>
                    <td>{{ $budget->unit_of_measurement }}</td>
                    <td>{{ $budget->incoterm_place ?? 'N/A' }}</td>
                    <td>{{ formatDate($budget->costing_date)  }}</td>
                    <td>{{ formatDate($budget->approve_date) }}</td>
                    <td>{{ $budget->region ?? 'N/A' }}</td>
                    <td>{{$budget->createdBy->screen_name ?? 'N/A' }}</td>
                    <td>{{\Carbon\Carbon::parse($budget->created_at)->format('M d, Y')}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="20">No Data Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
