<table class="reportTable" style="border: 1px solid #000; border-collapse: collapse;">
	<thead>
	@if(isset($type) && $type == 'xls')
		<tr>
			<th colspan="4">{{ sessionFactoryName() }}</th>
		</tr>
		<tr>
			<th colspan="4">Lot Wise Cutting Production</th>
		</tr>
	@endif
	<tr>
		<th colspan="4">
			Buyer: {{ $buyer ?? '' }}, &nbsp;&nbsp;&nbsp;
			Style: {{ $booking_no ?? '' }}, &nbsp;&nbsp;&nbsp;
			Order/Style: {{ $order_style_no ?? '' }}, &nbsp;&nbsp;&nbsp;
			PO: {{ $po_no ?? '' }}, &nbsp;&nbsp;&nbsp;
			Color: {{ $color ?? '' }} &nbsp;&nbsp;&nbsp;
		</th>
	</tr>
	<tr>
		<th>Serial No.</th>
		<th>Lot No.</th>
		<th>Size</th>
		<th>Quantity</th>
	</tr>
	</thead>
	<tbody>
	@if(!empty($results))
    @php
      $totalQty = 0;
    @endphp
		@foreach($results as $report)
      @php
        $totalQty += $report['qunatity'] ?? 0;
      @endphp
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{$report['lot_no']}}</td>
				<td>{{$report['size_name']}}</td>
				<td>{{$report['qunatity']}}</td>
			</tr>
		@endforeach
    <tr>
      <th colspan="3"style="text-align: right;">Total</th>
      <th style="text-align: left;">{{ $totalQty }}</th>
    </tr>
	@else
		<tr>
			<td colspan="4" style="font-weight: bold; text-align: center;">Not found</td>
		</tr>
	@endif
	</tbody>
</table>