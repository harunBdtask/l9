<div class="col-md-12">
    <table class="reportTable table-responsive">
        <thead>
        <tr>
            <th>Sl</th>
            {{--<th>Booking Status</th>--}}
            <th>Buyer</th>
            <th>Merchandiser</th>
            <th>Fabric Booking ID</th>
            <th>Budget UQ ID (Style NO)</th>
            <th>Item /Description</th>
            <th>Style Name</th>
            <th>Country</th>
            <th>PO No</th>
            <th>Combo</th>
            <th>Color</th>
            <th>LD NO</th>
            <th>Sample Bulk Approval</th>
            <th>Initial Strike off AOP</th>
            <th>Textile Concern Confirmation</th>
            <th>Initial Approval Date</th>
            <th>Total Quantity KG</th>
            <th>Total Quantity Yards</th>
            <th>Booking Qty in KG</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Booking Date</th>
            <th>Delivery Date</th>
            <th>Source</th>
            <th>Supplier</th>
            <th>Booking Type</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reportData as $data)
            @php
                $budgetUniqueIds = collect($data->details)->pluck('job_no')
                    ->unique()->join(', ');

                $itemDescriptions = collect($data->details)->pluck('garments_item_name')
                    ->unique()->join(', ');

                $stylesName = collect($data->details)->pluck('style_name')
                    ->unique()->join(', ');

                $poNos = collect($data->details)->pluck('po_no')
                    ->unique()->join(', ');

                $colorNames = collect($data->details)->pluck('color')
                    ->unique()->join(', ');

                $totalQtyInKg = collect($data->details)->map(function ($collection) {
                    return ($collection['total_fabric_qty'] / (100+$collection['process_loss'])) * 100 ?? 0.00;
                })->sum();

                $totalQtyInYards = collect($data->details)->pluck('yards')->sum();

                $bookingQtyInKg = collect($data->details)->pluck('total_fabric_qty')->sum();

                $rate = collect($data->details)->pluck('rate')->avg();

                $amount = collect($data->details)->pluck('amount')->sum();

                $ldNos = collect($data->details)->pluck('remarks')->filter(function ($collection) {
                    return $collection != null;
                })->join(', ');
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                {{--<td></td>--}}
                <td>{{ $data->buyer->name }}</td>
                <td>{{ $data->team_leader }}</td>
                <td>{{ $data->unique_id }}</td>
                <td>{{ $budgetUniqueIds }}</td>
                <td>{{ $itemDescriptions }}</td>
                <td>{{ $stylesName }}</td>
                <td>{{ $data->countries }}</td>
                <td>{{ $poNos }}</td>
                <td>{{ $data->combo }}</td>
                <td>{{ $colorNames }}</td>
                <td>{{ $ldNos }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ number_format($totalQtyInKg, 4) }}</td>
                <td>{{ number_format($totalQtyInYards, 4) }}</td>
                <td>{{ number_format($bookingQtyInKg, 4) }}</td>
                <td>{{ number_format($rate, 4) }}</td>
                <td>{{ number_format($amount, 4) }}</td>
                <td>{{ $data->booking_date }}</td>
                <td>{{ $data->delivery_date }}</td>
                <td>{{ $data->fabric_source_name }}</td>
                <td>{{ $data->supplier->name }}</td>
                <td>{{ $data->level_name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
