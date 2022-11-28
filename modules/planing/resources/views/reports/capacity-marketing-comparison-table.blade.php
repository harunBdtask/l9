<table class="reportTable">
    <thead>
    <tr>
        <th>Item Category</th>
        <th>Available Min</th>
        <th>Buyer</th>
        <th>QTY</th>
        <th>Required Min</th>
        <th>Total Required Min</th>
        <th>Balance Min</th>
    </tr>
    </thead>
    <tbody>
    @forelse($reports as $categoryWise)
        @foreach($categoryWise['category_wise_po_breakdown'] as $poBreakdown)
            <tr>
                @if($loop->first)
                    <td rowspan="{{ count($categoryWise['category_wise_po_breakdown']) }}">{{ $categoryWise['category'] }}</td>
                    <td rowspan="{{ count($categoryWise['category_wise_po_breakdown']) }}">{{ $categoryWise['available_min'] }}</td>
                @endif

                <td>{{ $poBreakdown['buyer'] }}</td>
                <td>{{ $poBreakdown['total_qty'] }}</td>
                <td>{{ $poBreakdown['total_po_capacity_available_in_min'] }}</td>
                @if($loop->first)
                    <td rowspan="{{ count($categoryWise['category_wise_po_breakdown']) }}">{{ $categoryWise['po_capacity_available_in_min_sum'] }}</td>
                    <td rowspan="{{ count($categoryWise['category_wise_po_breakdown']) }}">{{ $categoryWise['balance_min'] }}</td>
                @endif
            </tr>
        @endforeach
    @empty
        <tr>
            <td colspan="7">No Data Found</td>
        </tr>
    @endforelse
    </tbody>
</table>
