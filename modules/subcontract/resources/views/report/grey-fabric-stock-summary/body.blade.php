<table class="reportTable">
    <thead>
    <tr>
        <th>Party Name</th>
        <th>TTL Receive QTY</th>
        <th>TTL Batch QTY</th>
        <th>TTL Clossing Stock</th>
    </tr>
    </thead>
    <tbody>
    @foreach($buyers as $buyer)
        <tr>
            <td>{{ $buyer['name'] }}</td>
            <td>{{ $buyer['total_receive_qty'] }}</td>
            <td>{{ $buyer['total_batch_qty'] }}</td>
            <td>{{ $buyer['closing_stock'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
