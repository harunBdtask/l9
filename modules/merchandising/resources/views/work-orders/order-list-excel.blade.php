
                <div class="row m-t">
                    <div class="col-sm-12" >
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>SL</th>
                                <th>Company Name</th>
                                <th>Location</th>
                                <th>Buyer</th>
                                <th>Booking No</th>
                                <th>Style</th>
                                <th>Unique ID</th>
                                <th>Booking Date</th>
                                <th>Delivery Date</th>
                                <th>Supplier</th>
                                <th>Pay Mode</th>
                                <th>Source</th>

                            </tr>
                            </thead>
                            <tbody>
                            @forelse($workOrders as $key => $workOrder)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ $key+1}}</td>
                                    <td>{{ $workOrder->factory->factory_short_name ?? $workOrder->factory->factory_name }}</td>
                                    <td>{{ $workOrder->location }}</td>
                                    <td class="text-left">{{ $workOrder->buyer->name }}</td>
                                    <td>{{ $workOrder->unique_id }}</td>
                                  
                                    <td>
                                    {{ $workOrder->bookingDetails->pluck('style')->unique()->join(', ') ?? null }}
                                    </td>
                                    <td>
                                        {{$workOrder->bookingDetails->pluck('budget_unique_id')->unique()->join(',') ?? null}}                                    </td>
                                    <td>{{ $workOrder->booking_date }}</td>
                                    <td>{{ $workOrder->delivery_date }}</td>
                                    <td>{{ $workOrder->supplier->name }}</td>
                                    <td>{{ $workOrder->pay_mode_value }}</td>
                                    <td>{{ $workOrder->source_value }}
                                    </td>
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
               
