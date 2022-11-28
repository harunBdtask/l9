<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr class="text-center">
                <th colspan="19">Fabric Bookings List</th>
            </tr>
            <tr><td></td></tr>
            <tr class="table-header">
                <td><b>Sl</b></td>
                <td><b>Company</b></td>
                <td><b>Buyer</b></td>
                <td><b>Booking ID</b></td>
                <td><b>Style</b></td>
                <td><b>Item/Description</b></td>
                <td><b>Budget UQ Id</b></td>
                <td><b>PO No</b></td>
                <td><b>UOM</b></td>
                <td><b>Total Qty</b></td>
                <td><b>Booking Qty</b></td>
                <td><b>Rate</b></td>
                <td><b>Amount</b></td>
                <td><b>Source</b></td>
                <td><b>Booking Date</b></td>
                <td><b>Delivery Date</b></td>
                <td><b>Supplier</b></td>
                <td><b>Level</b></td>
            </tr>
            </thead>
            <tbody>
{{--            collect(collect(collect($fabricBooking->detailsBreakdown)->first()->budget->fabricCosting->details)['details']['fabricForm'])->pluck('grey_cons_total_quantity')->sum()--}}
            @forelse($fabricBookings as $fabricBooking)
                <tr style="{{ $fabricBooking->detailsBreakdown()->count() === 0 ? 'background: #c7c7c7' : '' }}">
                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                    <td align="left" style="padding: 3px">{{ $fabricBooking->factory->factory_short_name ?? $fabricBooking->factory->factory_name }}</td>
                    <td align="left" style="padding: 3px">{{ $fabricBooking->buyer->name }}</td>
                    <td>{{ $fabricBooking->unique_id }}</td>
                    <td>{{ $fabricBooking->style_name }}</td>
                    <td>{{ $fabricBooking->item_description }}</td>
                    <td>{{ $fabricBooking->budget_job_no ?? 'N/A' }}</td>
                    <td>{{ $fabricBooking->po_no }}</td>
                    <td>{{ $fabricBooking->uom }}</td>
                    <td>{{ !empty($fabricBooking->detailsBreakdown) ?
                           ( !empty(collect($fabricBooking->detailsBreakdown)->first()) ?
                           ( !empty(collect($fabricBooking->detailsBreakdown)->first()->budget->fabricCosting) ?
                           ( !empty(collect($fabricBooking->detailsBreakdown)->first()->budget->fabricCosting->details) ?
                           ( !empty(collect(collect($fabricBooking->detailsBreakdown)->first()->budget->fabricCosting->details)['details']['fabricForm']) ?
                            collect(collect(collect($fabricBooking->detailsBreakdown)->first()->budget->fabricCosting->details)['details']['fabricForm'])->pluck('grey_cons_total_quantity')->sum()
                           : 0.00)
                           : 0.00)
                           : 0.00)
                           : 0.00)
                           : 0.00
                           }}
                    </td>
                    <td>{{ $fabricBooking->total_fabric_booking_qty }}</td>
                    <td>{{ $fabricBooking->rate ?? 0.00}}</td>
                    <td>{{ $fabricBooking->amount }}</td>
                    <td>{{ $fabricBooking->fabric_source_name }}</td>
                    <td>{{ $fabricBooking->booking_date }}</td>
                    <td>{{ $fabricBooking->delivery_date }}</td>
                    <td align="left">{{ $fabricBooking->supplier->name }}</td>
                    <td>{{ $fabricBooking->level_name }}</td>
                </tr>
            @empty
                <tr>
                    <th colspan="19">No Data Found</th>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
