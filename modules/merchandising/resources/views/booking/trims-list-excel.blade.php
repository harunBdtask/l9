<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr class="text-center">
                <th colspan="19">Trims Bookings List</th>
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
            </tr>
            </thead>
            <tbody>
            @forelse($trimsBookings as $trimsBooking)
                <tr style="{{ $trimsBooking->bookingDetails()->count() === 0 ? 'background: #c7c7c7' : '' }}">
                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                    <td align="left" style="padding: 3px">{{$trimsBooking->factory->factory_name }}</td>
                    <td align="left" style="padding: 3px">{{ $trimsBooking->buyer->name }}</td>
                    <td>{{ $trimsBooking->unique_id }}</td>
                    <td>{{ $trimsBooking->style }}</td>
                    <td>{{ $trimsBooking->item_description }}</td>
                    <td>{{ $trimsBooking->budget_job_no ?? 'N/A' }}</td>
                    <td>{{ $trimsBooking->po_no }}</td>
                    <td>{{ $trimsBooking->uom }}</td>
                    @php
                        if(!empty($trimsBooking->bookingDetails)){
                            if(!empty(collect($trimsBooking->bookingDetails)->first())){
                                if(!empty(collect($trimsBooking->bookingDetails)->first()->budget->trimCosting)){
                                  if(!empty(collect(collect($trimsBooking->bookingDetails)->first()->budget->trimCosting->details))){
                                      if(!empty(collect(collect($trimsBooking->bookingDetails)->first()->budget->trimCosting->details)['details'])){
                                          $value = collect(collect(collect($trimsBooking->bookingDetails)->first()->budget->trimCosting->details)['details']) ;
                                          $totalValue = $value->map(function ($v){
                                              return ['total_quantity' => (float) preg_replace('/[^0-9.]/', '', $v['total_quantity']),];
                                          })->pluck('total_quantity')->sum();
                                      }
                                  }
                                }
                           }
                        }
                    @endphp
                    <td>{{ $totalValue }}</td>
                    <td>{{ $trimsBooking->total_trims_booking_qty }}</td>
                    <td>{{ $trimsBooking->rate }}</td>
                    <td>{{ $trimsBooking->amount }}</td>
                    <td>{{ $trimsBooking->source_value }}</td>
                    <td>{{ $trimsBooking->booking_date }}</td>
                    <td>{{ $trimsBooking->delivery_date }}</td>
                    <td align="left">{{ $trimsBooking->supplier->name }}</td>
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
